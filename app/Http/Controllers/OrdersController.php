<?php

namespace App\Http\Controllers;

use App\BackgroundCategory;
use App\ChatNotification;
use App\Customer;
use App\Designer;
use App\DesignerStack;
use App\DesignStyle;
use App\Mail\ApprovedMail;
use App\Mail\CompleteOrder;
use App\Mail\DesignMail;
use App\Mail\UpdateMail;
use App\NewOrderDesigner;
use App\Order;
use App\OrderAdditionalDetails;
use App\OrderProduct;
use App\OrderProductAdditionalDetails;
use App\ProductDesign;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            if (strpos($request->input('search'), ',')) {
                $order_names = explode(',',$request->input('search'));
            }
            else{
                $order_names = explode('、',$request->input('search'));
            }

            $query->whereIn('name',$order_names);

            $query->orWhere('email','LIKE','%'.$request->input('search').'%');
            $array = explode(' ',$request->input('search'));
            $query->orWhereIn('bill_first_name',$array);
            $query->orWhereIn('bill_last_name',$array);
            $query->orWhereIn('ship_first_name',$array);
            $query->orWhereIn('ship_last_name',$array);
        }
        if($request->input('product')){
            $query->whereHas('has_products',function ($product_query) use ($request){
                $product_query->where('title',$request->input('product'));
            });
        }
        if($request->has('type')){
            $query->whereHas('has_additional_details',function ($q) use ($request){
                $q->where('status_id','=',$request->input('type'));
            });
        }

        $orders = $query->orderBy('name', 'DESC')->paginate(30);
        $products = DB::table('order_products')
            ->select('title')
            ->where('shop_id',$this->helper->getShop()->id)
            ->groupBy('title')
            ->get();
        return  view('admin.order')->with([
            'orders' => $orders,
            'products' => $products,
            'type' =>$request->input('type'),
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
        $this->removeSessionFilters();

        if($request->has('type')){
            $query = Order::where('shop_id', $this->helper->getShop()->id)->newQuery();
            $query->whereHas('has_additional_details',function ($q) use ($request){
                $q->where('status_id','=',$request->input('type'));
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
            'type' =>$request->input('type'),
            'designers' => $this->getDesigners(),
            'statuses' => $this->getStatuses('order')

        ]);
    }

    public function OrderDetails($id){
//        dd(session()->all());
        $orders_count = count(Order::all());
        $categories = BackgroundCategory::where('shop_id', $this->helper->getShop()->id)->get();
        if(!\request()->has('type')){
            $order = Order::find($id);
            return view('admin.order-details')->with([
                'order' => $order,
                'categories' => $categories
            ]);
        }
        else{
            if(\request()->input('type') == 'next'){
                $count = $id+1;
                if($count <= $orders_count){
                    while($count <= $orders_count){
                        if(Order::where('id',$count)->exists()){
                            if(session()->has('status') || session()->has('designer')){
                                $new_order_query = Order::where('shop_id',$this->helper->getShop()->id)->where('id',$count)->newQuery();
                                if(session()->has('status')){
                                    $new_order_query->whereHas('has_design_details',function ($q){
                                        $q->where('status',session()->get('status'));
                                    });
                                }
                                if(session()->has('designer')){
                                    $new_order_query->whereHas('has_designer',function ($q){
                                        $q->where('name',session()->get('designer'));
                                    });
                                }
                                $order = $new_order_query->get();

                                if(count($order) > 0){
                                    return redirect()->route('order.detail',$count);
                                }

                            }
                            else{
                                return redirect()->route('order.detail',$count);
                            }
                        }
                        else{
                            $order = null;
                        }
                        $count++;
                    }
                    return redirect()->route('order.detail',$id);
                }

            }
            else{
                $count = $id-1;
                if($count != 0){
                    while(true){
                        if($count != 0){
                            if(Order::where('id',$count)->exists()){
                                if(session()->has('status') || session()->has('designer')){
                                    $new_order_query = Order::where('shop_id',$this->helper->getShop()->id)->where('id',$count)->newQuery();
                                    if(session()->has('status')){
                                        $new_order_query->whereHas('has_design_details',function ($q){
                                            $q->where('status',session()->get('status'));
                                        });
                                    }
                                    if(session()->has('designer')){
                                        $new_order_query->whereHas('has_designer',function ($q){
                                            $q->where('name',session()->get('designer'));
                                        });
                                    }
                                    $order = $new_order_query->get();

                                    if(count($order) > 0){
                                        return redirect()->route('order.detail',$count);
                                    }

                                }
                                else{
                                    return redirect()->route('order.detail',$count);
                                }
                            }
                            $count--;
                        }
                        else{
                            return redirect()->route('order.detail',$id);
                            break;
                        }
                    }
                }
            }
        }

    }

    public function new_orders(Request $request){
        $this->removeSessionFilters();

        if(!$request->has('page')){
            $page = 1;
        }
        else{
            $page = $request->input('page');
        }
        $req = $this->helper->getShop()->api()->rest('GET', '/admin/orders.json',['page'=>$page,'order'=>'created_at desc']);
//        dd($req);
        $designers = Designer::where('shop_id',$this->helper->getShop()->id)
            ->where('status',1)->get();
        if(count($designers) > 0) {
            $designers = $designers->sort(function ($a, $b) {
                $count_order_a = count($a->has_orders);
                $count_order_b = count($b->has_orders);
                if ($count_order_a == $count_order_b) {
                    return 0;
                }
                return ($count_order_a < $count_order_b) ? -1 : 1;
            });
//            dd($designers);
            /*Initializing a Designer Stack*/
            $designers_stack = new DesignerStack(count($designers));
            foreach ($designers as $designer) {
                $designers_stack->push([
                    "id" => $designer->id,
                    "count" => count($designer->has_orders),
                    "assign" => 10 - (count($designer->has_orders)%10),
                ]);
            }
//            dd($designers_stack);
        }
        else{
            $designers_stack = new DesignerStack(count($designers));
        }
        if(!$designers_stack->isEmpty()){
            $designer = $designers_stack->pop();
        }
        $count = 1;
        foreach ($req->body->orders as $order){

            if(Order::where('shopify_id',$order->id)->exists()){
                $order->sync = 'yes';
                $exist =  Order::where('shopify_id',$order->id)->first();
                $order->designer_id = $exist->designer_id;
                $order->designer = $exist->has_designer->name;
                $order->designer_color = $exist->has_designer->color;
                $order->designer_background = $exist->has_designer->background_color;
            }

            else{
                $order->sync = 'no';
//                dd($designer);
                if(!empty($designer)){
                    if(NewOrderDesigner::where('shopify_id',$order->id)->exists()){
                        $exiting_assign = NewOrderDesigner::where('shopify_id',$order->id)->first();
                        $order->designer_id = $exiting_assign->designer_id;
                        $exist = Designer::find($exiting_assign->designer_id);
                        if($exist != null){
                            $order->designer = $exist->name;
                            $order->designer_color = $exist->color;
                            $order->designer_background = $exist->background_color;
                        }
                        else{
                            $order->designer_id = $designer["id"];
                            $exist = Designer::find($designer["id"]);
                            $order->designer = $exist->name;
                            $order->designer_color = $exist->color;
                            $order->designer_background = $exist->background_color;
                            /*Update Order with New Designer if old is deleted*/
                            $new =  NewOrderDesigner::where('shopify_id',$order->id)->first();
                            $new->shopify_id = $order->id;
                            $new->designer_id = $designer["id"];
                            $new->save();
                        }

                    }
                    else{
                        $order->designer_id = $designer["id"];
                        $exist = Designer::find($designer["id"]);
                        $order->designer = $exist->name;
                        $order->designer_color = $exist->color;
                        $order->designer_background = $exist->background_color;
                        /*Update Order with New Designer*/

                        $new = new NewOrderDesigner();
                        $new->shopify_id = $order->id;
                        $new->designer_id = $designer["id"];
                        $new->save();
                    }



                    if($count == $designer["assign"]){
                        $designer["count"] = $designer["assign"] + $designer["count"];
                        $designer["assign"] = 10;
                        $designers_stack->push($designer);
                        $designer = $designers_stack->pop();
                        $count = 1;
                    }
                    else{
                        $count++;
                    }
                }
                else{
                    $order->designer_id = null;
                    $order->designer = 'no designer';
                    $order->designer_color = '#ffffff';
                    $order->designer_background = "#ff0000";
                }

            }
        }


        return view('admin.new-orders')->with([
            'orders'=>$req->body->orders,
            'page' => $page
        ]);
    }

    public function GetShopifyOrders(){
        $request = $this->helper->getShop()->api()->rest('GET', '/admin/orders.json');
//        dd($request);
        foreach ($request->body->orders as $order){
            $this->CreateOrder($order, $this->helper->getShop()->shopify_domain);
//            dd($order);
        }
        $this->AssignStatus($this->helper->getShop()->shopify_domain);
        $this->DesignerPicker($this->helper->getShop()->shopify_domain);
//        return redirect()->back();
    }


    public function UpdateOrder($order, $shop){
        if (Order::where('shopify_id', '=', $order->id)->exists()) {
            $order_add = Order::where('shopify_id', '=', $order->id)->first();

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
    }

    public function CreateOrder($order, $shop){
        if (Order::where('shopify_id', '=', $order->id)->exists()) {
            $order_add = Order::where('shopify_id', '=', $order->id)->first();
        }
        else {
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
        $line_item->design_count = 0;

        $line_item->save();
    }

    public function AssignStatus($shop){
        $orderQuery =  Order::where('shop_id',$this->helper->getShopDomain($shop)->id)->newQuery();
        $orderQuery->whereDoesntHave('has_additional_details',function ($product_query){
        });
        $orders = $orderQuery->get();
        $status = Status::where('name','Not Completed')->where('type','order')->first();
        if($status == null){
            $status_name = 'Not Completed';
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
            $query->where('status','Not Completed');
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
                    $design->status_id = '8';
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
            if($status->name == 'Completed'){
                Mail::to($order->email)->send(new CompleteOrder($order));
            }

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
                $name = str_replace(' ','',$file->getClientOriginalName());
                $design = date("mmYhisa_") . $name;
                $file->move(public_path() . '/designs/', $design);
            } else {
                $design = '';
            }

            $target->design = $design;
            $target->status ='In-Processing';
            $target->status_id = 3;
            $target->save();
            /*Product Design*/
            $product_design = new ProductDesign();
            $product_design->design = $target->design;
            $product_design->order_product_id = $target->order_product_id;
            $product_design->order_id = $target->order_id;
            $product_design->setUpdatedAt(now());
            $product_design->setCreatedAt(now());
            $product_design->save();
            /*incrementing count*/
            $product = OrderProduct::find($target->order_product_id);
            $product->design_count = $product->design_count+1;
            $product->save();

            $order = Order::find($request->input('order_id'));
            /*Check Designer*/
            $user = \App\User::find(Auth::id());
            $this->helper->CheckDesigner($order,$user);
            /*Seen Customer Messages*/
            $new_msgs = ChatNotification::where('order_id', $request->input('order_id'))->where('name', 'Customer')->where('status','unseen')->get();
            foreach ($new_msgs as $new_msg){
                $new_msg->status = 'seen';
                $new_msg->save();
            }

            try{
                Mail::to($order->email)->send(new DesignMail($order,$product->title));
                $order->last_email_at = now()->format('Y-m-d');
                $order->save();
            }
            catch (\Exception $e){
            }



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
            $line_item = OrderProduct::find($request->input('product_id'));
            $order = Order::find($line_item->order_id);
            $user = \App\User::find(Auth::id());
            $this->helper->CheckDesigner($order,$user);
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }

    public function update_notes(Request $request){
        $order = Order::find($request->input('order_id'));
        $user = \App\User::find(Auth::id());
        $this->helper->CheckDesigner($order,$user);
        if($order != null){
            $order->note = $request->input('notes');
            $order->save();
        }
        return redirect()->back();

    }

    public function sync_order(Request $request){
//        dd($request->designer_id);
        $req = $this->helper->getShop()->api()->rest('GET', '/admin/orders/'.$request->id.'.json');
        $this->CreateOrder($req->body->order, $this->helper->getShop()->shopify_domain);
        $this->AssignStatus($this->helper->getShop()->shopify_domain);
        /*Assign Designer*/
        $order =  Order::where('shopify_id',$request->id)->first();
        $order->designer_id = $request->designer_id;
        $order->save();
        $order->has_additional_details->designer_id = $request->designer_id;
        $order->has_additional_details->save();
        foreach ($order->has_products as $item){
            $design = new OrderProductAdditionalDetails();
            $design->status = 'No Design';
            $design->status_id = '8';
            $design->order_id = $order->id;
            $design->order_product_id = $item->id;
            $design->shop_id = $this->helper->getShop()->id;
            $design->save();
        }
//        $this->DesignerPicker($this->helper->getShop()->shopify_domain);
        return redirect()->route('order.detail',$order->id);
    }

    public function new_order_detail(Request $request){

        $req = $this->helper->getShop()->api()->rest('GET', '/admin/orders/'.$request->id.'.json');
//        dd($req);
        $exist = false;
        if(Order::where('shopify_id',$request->id)->exists()){
            $exist = true;
        }
        $designer = Designer::find($request->designer_id);
//        dd($req->body->order);
        return view('admin.new-order-detail')->with([
            'order'=>$req->body->order,
            'designer' => $designer,
            'categories' => BackgroundCategory::all(),
            'exist' => $exist
        ]);
    }

    public function extra_design_delete(Request $request){
        $extra_design = ProductDesign::find($request->id);
        $product =OrderProduct::find($extra_design->order_product_id);
        $product->design_count = $product->design_count-1;
        $product->save();
        $extra_design->delete();
        return redirect()->back();
    }

    public function design_delete(Request $request){
        $target =  OrderProductAdditionalDetails::where('order_id',$request->input('order'))
            ->where('order_product_id',$request->input('product'))->first();
        if($target != null){
            $product =OrderProduct::find($target->order_product_id);
            $product->design_count = $product->design_count-1;
            $product->save();

            $related_design = ProductDesign::where('design',$target->design)->first();
            $related_design->delete();

            if(count($product->has_many_designs) > 0){
                $target->design = $product->has_many_designs[0]->design;
                $target->save();
            }
            else{
                $target->design = null;
                $target->status ='No Design';
                $target->status_id = 8;
                $target->save();
            }



            $order = Order::find($request->input('order'));
            $user = \App\User::find(Auth::id());
            $this->helper->CheckDesigner($order,$user);
            return redirect()->back();
        }
        else{
            return redirect()->back();
        }
    }

    public function bulk_order_completed(Request $request){
        $this->removeSessionFilters();
       $orders = explode(',',$request->input('orders'));
       if(count($orders) > 0){
           foreach ($orders as $order){
               $exist = Order::find($order);
               if($exist->has_additional_details != null ){
                   $exist->has_additional_details->status = 'Completed';
                   $exist->has_additional_details->status_id = '2';
                   $exist->has_additional_details->save();
                   try{
                       Mail::to($exist->email)->send(new CompleteOrder($exist));
                   }
                   catch (\Exception $e){

                   }

               }
           }
           return redirect()->back();
       }
       else{
           return redirect()->back();
       }
    }

    public function sendEmail(Request $request){
    $order = Order::find($request->input('order'));
//    if($order != null){
//        $orderQ = $order->has_design_details()->where('order_id',$order->id)->whereIN('status',['In-Processing'])->get();
//        if(count($orderQ) > 0){
//            try{
//                Mail::to($order->email)->send(new UpdateMail($order));
//                $order->last_email_at = now()->format('Y-m-d');
//                $order->save();
//                return response()->json([
//                    'status'=>'success',
//                    'message' => 'Update Email Send Successfully!',
//                ]);
//            }
//            catch (\Exception $e){
//                return response()->json([
//                    'status'=>'error',
//                    'message' => 'Email Sending Failed. Server Issues!',
//                ]);
//            }
//
//        }
//        else{
//            return response()->json([
//                'status'=>'error',
//                'message' => 'Email Cannot Be Sent Because Order status is Approved | Update | No Design',
//            ]);
//        }
//    }
        if($order != null){
            try {
                Mail::to($order->email)->send(new UpdateMail($order));
                $order->last_email_at = now()->format('Y-m-d');
                $order->save();
                return response()->json([
                    'status'=>'success',
                    'message' => 'Update Email Send Successfully!',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email Sending Failed. Server Issues!',
                ]);
            }
        }
    else{
        return response()->json([
            'status'=>'error',
            'message' => 'Order Does Not Exist',
        ]);
    }

    }

    public function set_sms_service(Request $request){
        $order = Order::find($request->input('order'));
        if($order != null){
            $order->sms_feature = $request->input('setting');
            $order->save();
            return response()->json([
               'status' => 'success'
            ]);
        }
        else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function set_session(Request $request){
        if($request->input('type') == 'status'){
            session(['status' => $request->input('value')]);
        }
        else{
            session(['designer' => $request->input('value')]);
        }
    }
    public function removeSessionFilters(){
        session()->forget('designer');
        session()->forget('status');
    }
}
