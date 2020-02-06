<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $helper;

    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function getOrders(){

        return view('admin.order');

    }

    public function getHome(){

        $new_order_query = Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $new_order_query->whereHas('has_additional_details',function ($q){
            $q->where('status_id',1);
        });
        $new_orders  =count($new_order_query->get());

        $new_designs_query = Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $new_designs_query->whereHas('has_additional_details',function ($q){
            $q->where('status_id',2);
        });
        $designs  =count($new_designs_query->get());

        $approved_query = Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $approved_query->whereHas('has_additional_details',function ($q){
            $q->where('status_id',3);
        });
        $approved  =count($approved_query->get());

        $request_query = Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $request_query->whereHas('has_request_fixes',function ($q){
        });
        $requests  =count($request_query->get());
        return view('admin.home')->with([
            'new_orders' => $new_orders,
            'designs' => $designs,
            'approved' => $approved,
            'requested' => $requests
        ]);

    }

    public function getBackground(){


        return view('admin.background');
    }

    public function ManagementLogin(){

        return view('admin.login');

    }

    public function getDashboard(){

        return view('admin.dashboard');

    }
    public function getOrderDetails($id){

        return view('admin.order-details');
    }



}
