<?php

namespace App\Http\Controllers;

use App\BackgroundCategory;
use App\Customer;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\ShopifyApp;

class OrdersController extends Controller
{
    public $helper;
    public $shop;

    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function Orders(){
        $orders = Order::where('shop_id', $this->helper->getShop()->id)->orderBy('name', 'DESC')->paginate(50);
        return view('admin.order')->with([
            'orders' => $orders
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
}
