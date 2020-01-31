<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public  function  getLogin(){
        return view('customer.login');
    }
    public function checkOrder(Request $request){

    }


    public function getOrderOverview( Request $request){

        return view('customer.order-overview');

    }
    public function ChangeBackground(){

        return view('customer.change-background');

    }


}
