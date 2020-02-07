<?php

namespace App\Http\Controllers;

use App\Designer;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
    public $helper;

    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function Dashboard(){
        $designers = Designer::where('shop_id', $this->helper->getShop()->id)->get();
        $query =  Order::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $query->whereHas('has_additional_details',function ($q){
            $q->where('status_id','!=',2);

        });
       $orders = $query->get();
       $ratings = [];
       foreach ($designers as $designer){
           array_push($ratings, $designer->has_reviews->avg('rating'));
       }
//       dd($ratings);
        return view('admin.dashboard')->with([
            'designers' => $designers,
            'orders' => $orders,
            'ratings' => $ratings
        ]);
    }
    public function ManualDesignPicker(Request $request){
        $order = Order::find($request->input('order'));
        if($order != null){
            if( $order->has_additional_details != null){
                $order->has_additional_details->designer_id = $request->input('designer');
                $order->has_additional_details->save();
                $order->designer_id = $request->input('designer');
                $order->save();
                return redirect()->back()->with('success', 'Designer Assigned Successfully');
            }
            else{
                return redirect()->back()->with('success', 'Designer Not Assigned');
            }
        }
        else{
            return redirect()->back()->with('success', 'Order Not Found');
        }
    }

    public function Designer_Save(Request $request){
        $designer = new Designer();
        $designer->name = $request->input('name');
        $designer->color = $request->input('color');
        $designer->background_color = $request->input('background_color');
        $designer->shop_id = $this->helper->getShop()->id;
        $designer->save();
        return redirect()->back()->with('success', 'Designer Added Successfully');
    }
    public function SetStatus(Request $request){
        $designer = Designer::find($request->input('designer'));
        $designer->status = $request->input('status');
        $designer->save();
        return redirect()->back()->with('success', 'Designer Status Changed Successfully');
    }

}
