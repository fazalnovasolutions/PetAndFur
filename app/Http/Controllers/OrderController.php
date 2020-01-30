<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders(){

        return view('admin.order');

    }

    public function getHome(){

        return view('admin.home');

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
