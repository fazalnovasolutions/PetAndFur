<?php

namespace App\Http\Controllers;

use App\Designer;
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
        return view('admin.dashboard')->with([
            'designers' => $designers
        ]);
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
}
