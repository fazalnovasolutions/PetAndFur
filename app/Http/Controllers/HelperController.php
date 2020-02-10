<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;

class HelperController extends Controller
{

    public $shopify;
    public $shop;

    public function getShopify(){
        $shop = ShopifyApp::shop();
//        $shop = Shop::where('shopify_domain', env('SHOPIFY_WEB_URL'))->first();
        $this->shopify = App::make('ShopifyAPI', [
            'API_KEY' => env('SHOPIFY_API_KEY'),
            'API_SECRET' => env('SHOPIFY_API_SECRET'),
            'SHOP_DOMAIN' => $shop->shopify_domain,
            'ACCESS_TOKEN' => $shop->shopify_token
        ]);
        return $this->shopify;
    }

    public function getShopifyDomain($domain){
        $shop = Shop::where('shopify_domain', $domain)->first();
        $this->shopify = App::make('ShopifyAPI', [
            'API_KEY' => env('SHOPIFY_API_KEY'),
            'API_SECRET' => env('SHOPIFY_API_SECRET'),
            'SHOP_DOMAIN' => $shop->shopify_domain,
            'ACCESS_TOKEN' => $shop->shopify_token
        ]);
        return $this->shopify;
    }


    public function getShop(){
        return  ShopifyApp::shop();
    }

    public function getShopDomain($domain){
        $this->shop = Shop::where('shopify_domain',$domain)->first();
        return $this->shop;
    }

    public function CheckDesigner(Order $order, User $current){
        if($order->designer_id !== $current->is_designer->id){
            $order->designer_id = $current->is_designer->id;
            $order->has_additional_details->designer_id = $current->is_designer->id;
            $order->save();
            $order->has_additional_details->save();
        }
    }
}
