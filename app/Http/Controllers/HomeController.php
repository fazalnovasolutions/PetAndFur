<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatNotification;
use App\Designer;
use App\DesignStyle;
use App\NewPhoto;
use App\Order;
use App\OrderAdditionalDetails;
use App\OrderProduct;
use App\OrderProductAdditionalDetails;
use App\ProductDesign;
use App\RequestFix;
use App\ReviewRating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function SuperAdminCreate()
    {
        $user =  User::create([
            'name' => 'Super Admin',
            'email' => 'admin@boompup.com',
            'password' => Hash::make('boompup@8'),
        ]);
        $user->assignRole('super-admin');

    }
    public function delete_all(Request $request){
        DesignStyle::truncate();
        NewPhoto::truncate();
        RequestFix::truncate();
        Order::truncate();
        OrderAdditionalDetails::truncate();
        OrderProduct::truncate();
        OrderProductAdditionalDetails::truncate();
        ProductDesign::truncate();
        Designer::truncate();
        Chat::truncate();
        User::truncate();
        ReviewRating::truncate();
        ChatNotification::truncate();
        DB::table('model_has_roles')->truncate();
        $this->SuperAdminCreate();
        return redirect()->route('login');
    }

}
