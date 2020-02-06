<?php

namespace App\Http\Controllers;

use App\BackgroundCategory;
use App\Customer;
use App\Designer;
use App\DesignerStack;
use App\DesignStyle;
use App\Order;
use App\OrderAdditionalDetails;
use App\OrderProduct;
use App\OrderProductAdditionalDetails;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OhMyBrew\ShopifyApp\ShopifyApp;

class OrdersController extends Controller
{
    public $helper;
    public $shop;

    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function filter_orders(Request $request){
        $query = Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        if($request->input('search')){
            $query->where('name','LIKE','%'.$request->input('search').'%');
            $query->orWhere('email','LIKE','%'.$request->input('search').'%');
            $query->orWhere('bill_first_name','LIKE','%'.$request->input('search').'%');
            $query->orWhere('ship_first_name','LIKE','%'.$request->input('search').'%');
        }
        if($request->input('product')){
            $query->whereHas('has_products',function ($product_query) use ($request){
                $product_query->where('title',$request->input('product'));
            });
        }
        $orders = $query->paginate(30);
        $products = DB::table('order_products')
            ->select('title')
            ->where('shop_id',$this->helper->getShop()->id)
            ->groupBy('title')
            ->get();
        return  view('admin.order')->with([
            'orders' => $orders,
            'products' => $products,
            'designers' => $this->getDesigners(),
            'statuses' => $this->getStatuses('order')
        ])->render();


    }

    public function getDesigners(){
        return Designer::where('shop_id',$this->helper->getShop()->id)->get();
    }
    public function getStatuses($type){
        return Status::where('type',$type)->get();
    }

    public function Orders(Request $request){
        if($request->has('type')){
            $query = Order::where('shop_id', $this->helper->getShop()->id)->newQuery();
            $query->whereHas('has_additional_details',function ($q) use ($request){
                $q->where('status_id',$request->input('type'));
            });
          $orders = $query->orderBy('name', 'DESC')->paginate(30);
        }
        else{
            $orders = Order::where('shop_id', $this->helper->getShop()->id)->orderBy('name', 'DESC')->paginate(30);
        }

        $products = DB::table('order_products')
            ->select('title')
            ->where('shop_id',$this->helper->getShop()->id)
            ->groupBy('title')
            ->get();
        return view('admin.order')->with([
            'orders' => $orders,
            'products' => $products,
            'designers' => $this->getDesigners(),
            'statuses' => $this->getStatuses('order')

        ]);
    }

    public function OrderDetails($id){
        $order = Order::find($id);
        $categories = BackgroundCategory::where('shop_id', $this->helper->getShop()->id)->get();
        return view('admin.order-details')->with([
            'order' => $order,
            'categories' => $categories
        ]);
    }

    public function GetShopifyOrders(){

        $request = $this->helper->getShop()->api()->rest('GET', '/admin/orders.json');
        foreach ($request->body->orders as $order){
            $this->CreateOrder($order, $this->helper->getShop()->shopify_domain);
//            dd($order);
        }
        $this->AssignStatus($this->helper->getShop()->shopify_domain);
        $this->DesignerPicker($this->helper->getShop()->shopify_domain);
        return redirect()->back();
    }


    public function CreateOrder($order, $shop){
        if (Order::where('shopify_id', '=', $order->id)->exists()) {
            $order_add = Order::where('shopify_id', '=', $order->id)->first();
        } else {
            $order_add = new Order();
        }

        $order_add->shopify_id = $order->id;
        $order_add->email= $order->email;

        if(isset($order->shipping_address)) {
            $order_add->ship_company = $order->shipping_address->company;
            $order_add->ship_first_name = $order->shipping_address->first_name;
            $order_add->ship_last_name = $order->shipping_address->last_name;
            $order_add->ship_address_1 = $order->shipping_address->address1;
            $order_add->ship_address_2 = $order->shipping_address->address2;
            $order_add->ship_country = $order->shipping_address->country;
            $order_add->ship_city = $order->shipping_address->city;
            $order_add->ship_state = $order->shipping_address->province;
            $order_add->ship_zipcode = $order->shipping_address->zip;
        }
//
        $order_add->note= $order->note;
        $order_add->name= $order->name;
        $order_add->number= $order->number;
        $order_add->taxes_included= $order->taxes_included;
        $order_add->currency= $order->currency;
        $order_add->financial_status= $order->financial_status;
        $order_add->confirmed= $order->confirmed;

        if(isset($order->billing_address)) {
            $order_add->bill_company = $order->billing_address->company;
            $order_add->bill_first_name = $order->billing_address->first_name;
            $order_add->bill_last_name = $order->billing_address->last_name;
            $order_add->bill_address_1 = $order->billing_address->address1;
            $order_add->bill_address_2 = $order->billing_address->address2;
            $order_add->bill_country = $order->billing_address->country;
            $order_add->bill_city = $order->billing_address->city;
            $order_add->bill_state = $order->billing_address->province;
            $order_add->bill_zipcode = $order->billing_address->zip;
        }

        $order_add->total_tax= $order->total_tax;
        $order_add->subtotal_price= $order->subtotal_price;
        $order_add->total_discount= $order->total_discounts;

        $shipping_price = 0;
        if(isset($order->shipping_lines)){
            foreach ($order->shipping_lines as $shipping){
                $shipping_price = $shipping_price + $shipping->price;
            }
        }
        $order_add->total_shipping_price= $shipping_price;
        $order_add->total_price= $order->total_price;
        $order_add->tax_details = json_encode($order->tax_lines, true);
        $order_add->shipping_details = json_encode($order->shipping_lines, true);
        $order_add->checkout_token= $order->token;
        $order_add->status_url= $order->order_status_url;
        $order_add->shop_id = $this->helper->getShopDomain($shop)->id;

        $order_add->save();

        foreach ($order->line_items as $item){
            $this->OrderLineItems($order_add, $item, $shop);
        }

    }

    public function OrderLineItems($order, $item, $shop){
        $line_items = OrderProduct::where([
            'shopify_id' => $item->id,
            'order_id' => $order->id,
            'variant_id' => $item->variant_id
        ])->get();

        if (isset($line_items) && count($line_items) >= 1) {
            $line_item = OrderProduct::where([
                'shopify_id' => $item->id,
                'order_id' => $order->id,
                'variant_id' => $item->variant_id
            ])->first();
        } else {
            $line_item = new OrderProduct();
        }
        $line_item->variant_id = $item->variant_id;
        $line_item->title = $item->title;
        $line_item->quantity = $item->quantity;
        $line_item->sku= $item->sku;
        $line_item->variant_title = $item->variant_title;
        $line_item->name = $item->name;
        $line_item->price = $item->price;
        $line_item->properties = json_encode($item->properties);

        $line_item->shopify_id = $item->id;
        $line_item->product_id = $item->product_id;
        $line_item->order_id = $order->id;
        $line_item->shop_id = $this->helper->getShopDomain($shop)->id;

        $line_item->save();
    }

    public function AssignStatus($shop){
        $orderQuery =  Order::where('shop_id',$this->helper->getShopDomain($shop)->id)->newQuery();
        $orderQuery->whereDoesntHave('has_additional_details',function ($product_query){
        });
        $orders = $orderQuery->get();
        $status = Status::where('name','New Order')->where('type','order')->first();
        if($status == null){
            $status_name = 'New Order';
            $status_id = '1';
        }
        else{
            $status_name = $status->name;
            $status_id = $status->id;
        }
        foreach ($orders as $index =>  $order){
            OrderAdditionalDetails::create([
                'status' => $status_name,
                'order_id' => $order->id,
                'status_id' => $status_id,
                'shop_id' => $this->helper->getShopDomain($shop)->id,
            ]);
        }
    }

    public function DesignerPicker($shop){
        /*Getting Orders that are new and has no designers*/
        $orderQuery =  Order::where('shop_id',$this->helper->getShopDomain($shop)->id)->newQuery();
        $orderQuery->whereHas('has_additional_details',function ($query){
            $query->where('status','New Order');
        });
        $orderQuery->whereDoesntHave('has_designer',function ($query){
        });
        $orders = $orderQuery->get();
        /*Getting Designers and Sorting them to desc order on the basis of their orders count*/
        $designers = Designer::where('shop_id',$this->helper->getShopDomain($shop)->id)
            ->where('status',1)->get();
        if(count($designers) > 0){
            $designers =  $designers->sort(function ($a, $b) {
                $count_order_a = count($a->has_orders);
                $count_order_b = count($b->has_orders);

                if ($count_order_a == $count_order_b) {
                    return 0;
                }
                return ($count_order_a < $count_order_b) ? -1 : 1;
            });
            /*Initializing a Designer Stack*/
            $designers_stack = new DesignerStack(count($designers));
            foreach ($designers as $designer){
                $designers_stack->push($designer->id);
            }
//        dd($designers_stack);
            /*Assigning Orders to Designer*/
            foreach ($orders as $order){
                $designer = $designers_stack->pop();
                $order->designer_id = $designer;
                $order->save();
                $order->has_additional_details->designer_id = $designer;
                $order->has_additional_details->save();
                foreach ($order->has_products as $item){
                    $design = new OrderProductAdditionalDetails();
                    $design->status = 'No Design';
                    $design->status_id = '9';
                    $design->order_id = $order->id;
                    $design->order_product_id = $item->id;
                    $design->shop_id = $this->helper->getShopDomain($shop)->id;
                    $design->save();
                }
                $designers_stack->push($designer);
            }
        }

    }

    public function change_order_status(Request $request){
        $order = Order::where('id',$request->input('id'))
            ->where('shop_id',$this->helper->getShop()->id)
            ->first();
        $status = Status::find($request->input('status'));
        if($order->has_additional_details != null && $status  != null){
            $order->has_additional_details->status = $status->name;
            $order->has_additional_details->status_id = $status->id;
            $order->has_additional_details->save();
            return http_response_code(200);
        }
        else{
            return http_response_code(500);
        }
    }

    public function design_upload(Request $request){
//        dd($request);
       $target =  OrderProductAdditionalDetails::where('order_id',$request->input('order_id'))
            ->where('order_product_id',$request->input('product_id'))->first();
       if($target != null){
           if ($request->hasFile('design')) {
               $file = $request->file('design');
               $name = Str::slug($file->getClientOriginalName());
               $design = date("mmYhisa_") . $name;
               $file->move(public_path() . '/designs/', $design);
           } else {
               $design = '';
           }
           $target->design = $design;
           $target->status ='In-Processing';
           $target->status_id = 4;
           $target->save();
           return redirect()->back();
       }
       else{
           return redirect()->back();
       }
    }

    public function change_style(Request $request){
//        dd($request);
        $category = BackgroundCategory::find($request->input('category'));
        if($category != null){
            DesignStyle::updateOrCreate([
               'order_product_id' => $request->input('product_id')
            ],[
                'style' => $category->name,
                'color' =>$category->color,
                'category_id' => $category->id
            ]);
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }
}
