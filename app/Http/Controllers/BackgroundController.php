<?php

namespace App\Http\Controllers;

use App\Background;
use App\BackgroundCategory;
use Illuminate\Http\Request;

class BackgroundController extends Controller
{
    public $helper;

    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function Background_Categories_Save(Request $request){

        $category = new BackgroundCategory();
        $category->name = $request->input('name');
        $category->color = $request->input('color');
        $category->shop_id = $this->helper->getShop()->id;
        $category->save();

        return back()->with('success', 'Background Category Added Successfully!!');
    }

    public function Backgrounds(Background $background, Request $request){
        $bg_query = $background->newQuery();
        if($request->input('category')){
            $bg_query->where('category_id', $request->input('category'));
        }
        if($request->has('category')){
            $backgrounds = $bg_query->orderBy('position','ASC')->paginate(30);
        }
        else{
            $backgrounds = $bg_query->paginate(30);
        }

        return view('admin.backgrounds')->with([
            'categories' => BackgroundCategory::all(),
            'backgrounds' => $backgrounds,
            'request' => $request
        ]);
    }

    public function Background_Save(Request $request){
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = date('his').'_'.$file->getClientOriginalName();
            $file_name = '/backgrounds/'.date("m-m-Y").'/';
            $file->move(public_path() .''.$file_name, $name);

            $background = new Background();
            $background->name = $request->input('name');
            $background->category_id = $request->input('category_id');
            $background->shop_id = $this->helper->getShop()->id;
            $background->image = $file_name.''.$name;
            $background->save();

            return redirect()->back()->with('success', 'Background Added Successfully!!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong, Please try again later.');
        }
    }

    public function Background_Delete($id){
        $design =Background::find($id);
        $design->delete();
        return redirect()->back()->with('success', 'Background Deleted Successfully');
    }

    public function Background_Category_Delete(Request $request){
        $category = BackgroundCategory::find($request->input('category'));
        if($category != null){
            if(count($category->has_backgrounds) > 0){
                $category->has_backgrounds()->delete();
                return redirect()->route('admin.background')->with('success', 'Category and Its Background Deleted Successfully!');

            }
            else{
                $category->delete();
                return redirect()->route('admin.background')->with('success', 'Category Deleted Successfully!');
            }
        }
        else{
            return redirect()->back()->with('success', 'Category Not Found!');

        }
    }
    public function BackgroundPositionUpdate(Request $request){
        $backgrounds = $request->input('backgrounds');
        $category = $request->input('category');
        foreach ($backgrounds as $index => $background){
            $b = Background::where('category_id',$category)
                ->where('id',$background)->first();
            if($b != null){
                $b->position = $index;
                $b->save();
            }
        }
    }

}
