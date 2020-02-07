<?php

namespace App\Http\Controllers;

use App\BackgroundCategory;
use App\NewPhoto;
use App\Order;
use App\OrderProduct;
use App\OrderProductAdditionalDetails;
use App\RequestFix;
use App\ReviewRating;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public  function  getLogin(){
        return view('customer.login');
    }
    public  function Logout(){
        session()->forget('order_name');
        session()->forget('email');
        return redirect()->route('customer.login');
    }
    public function checkOrder(Request $request){
//        dd($request);
        if($request->input('order_name') != null){
            if($this->startsWith($request->input('order_name'),'#')){
                $order =$request->input('order_name');
            }else{
                $order ='#'.$request->input('order_name');
            }

            session(['email' => $request->input('email')]);
            session(['order_name' => $order]);

            $check = Order::where('name',$order)
                ->where('email',$request->input('email'))->first();
        }
        else{
            $check = Order::where('name',session('order_name'))
                ->where('email',session('email'))->first();
        }

        if($check != null){
            return view('customer.order-overview')->with([
                'order' => $check
            ]);
        }
        else{
            return redirect()->back()->with('msg','No Order Found!');
        }

    }
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function ChangeBackground(Request $request){

        if(session('order_name') != null){
            $product = OrderProduct::where('id',$request->product)->first();
            if($product != null){
                if($product->has_design->status_id != 6 && $product->has_design->status_id != 8){
                    $properties = json_decode($product->properties);
                    $style = '';
                    foreach ($properties as $p){
                        if($p->name == 'Style'){
                            $style = $p->value;
                        }
                    }
                    if($style != null){
                        $category =  BackgroundCategory::where('name',$style)->first();
                        if($category != null){
                            return view('customer.change-background')->with([
                                'product' => $product,
                                'category' => $category,
                            ]);
                        }
                        else{
                            return redirect()->back();
                        }
                    }
                    else{
                        return redirect()->back();
                    }
                }
                else{
                    return redirect()->route('customer.check');
                }


            }
            else{
                return redirect()->back();
            }

        }
        else{
            return redirect()->route('customer.login');
        }


    }
    public function NewPhoto(Request $request){

        $target =  OrderProductAdditionalDetails::where('order_id',$request->input('order'))
            ->where('order_product_id',$request->input('product'))->first();

        if($target != null ){
            if ($request->hasFile('new_photo')) {
                $file = $request->file('new_photo');
                $name = Str::slug($file->getClientOriginalName());
                $new_photo = date("mmYhisa_") . $name;
                $file->move(public_path() . '/new_photos/', $new_photo);
            } else {
                $new_photo = '';
            }
            if($new_photo != ''){
                if($target->design != null){
                    $target->status = 'Update';
                    $target->status_id = '7';
                    $target->save();
                }
                $photo =  new NewPhoto();
                $photo->new_photo = $new_photo;
                $photo->order_id = $request->input('order');
                $photo->order_product_id = $request->input('product');
                $photo->save();
                $product = OrderProduct::find($request->input('product'));
                $product->latest_photo = $new_photo;
                $product->save();

                return redirect()->back();
            }
            else{
                return redirect()->back()->with('msg','Invalid New Photo!');
            }

        }

    }
    public function RequestFix(Request $request){

        $target =  OrderProductAdditionalDetails::where('order_id',$request->input('order'))
            ->where('order_product_id',$request->input('product'))->first();

        if($target != null){
            if($target->design != null){
                $target->status = 'Update';
                $target->status_id = '7';
                $target->save();
            }
            $r =  new RequestFix();
            $r->msg = $request->input('request_fix');
            $r->order_id = $request->input('order');
            $r->order_product_id = $request->input('product');
            $r->save();
            $product = OrderProduct::find($request->input('product'));
            $product->latest_request = $request->input('request_fix');
            $product->save();
            return redirect()->back();

        } else{
            return redirect()->back()->with('msg','No Order Found');
        }

    }
    public function SaveBackground(Request $request){
//        dd($request);
        $product = OrderProduct::find($request->input('product'));
        if($product != null){
            $product->background_id = $request->input('category');
            if($product->has_design != null){
                $target = $product->has_design;
                $target->status = 'Update';
                $target->status_id = '7';
                $target->save();
            }
            $product->save();
            if(!$request->ajax()){
                return redirect()->back();
            }

        }
        else{
            return redirect()->back();
        }
    }
    public function SaveApproved(Request $request){
        $product = OrderProduct::find($request->input('product'));
        if($product != null){
            $product->has_design->status ='Approved';
            $product->has_design->status_id = 6;
            $product->has_design->save();
            return response()->json([
                'status' => 'approved'
            ]);
        }
        else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function SaveReview(Request $request)
    {
       $product = OrderProduct::find($request->input('product'));
        if($product != null){
            if($product->has_order != null){
                if($product->has_order->has_designer != null){
                   $r = new ReviewRating();
                   $r->review = $request->input('review');
                   $r->rating = $request->input('rating');
                   $r->designer_id = $product->has_order->has_designer->id;
                   $r->order_id = $product->has_order->id;
                   $r->product_id = $product->id;
                   $r->save();
                   return redirect()->route('customer.check');
                }
                else{
                    return redirect()->back();
                }
            }
            else{
                return redirect()->back();
            }
        }
        else{
            return redirect()->back();
        }
    }
}
