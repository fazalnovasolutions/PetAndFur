<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $helper;

    /**
     * ChatController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function getChat(Request $request){
       $msgs =  Chat::where('order_product_id',$request->input('order_product_id'))->get();
       $html = view('chat',[
           'msgs' => $msgs,
           'apply' => $request->input('apply'),
           'product' => $request->input('order_product_id'),
           'order' => $request->input('order')
       ])->render();
       return response()->json([
           'html' => $html
       ]);
    }

    public function saveChat(Request $request){

        if ($request->hasFile('content')) {
            $file = $request->file('content');
            $name = date('his') . '_' . $file->getClientOriginalName();
            $file_name = '/chat-images/' . date("m-m-Y") . '/';
            $file->move(public_path() . '' . $file_name, $name);
        }
        $new = new Chat();
        $new->name = $request->input('name');
        $new->type = $request->input('type');
        if ($request->hasFile('content')) {
            $new->content = $file_name.''.$name;
        }
        else{
            $new->content = $request->input('content');
        }

        $new->order_id = $request->input('order_id');
        $new->order_product_id = $request->input('order_product_id');
        $new->setCreatedAt(now());
        $new->setUpdatedAt(now());
        $new->save();

        $user = \App\User::find(Auth::id());
        if($user != null){
            $order = Order::find($request->input('order_id'));
            $this->helper->CheckDesigner($order,$user);
        }
        return response()->json([
            'status' => 'save',
        ]);

    }

    public function delete_msg(Request $request){
        $msg = Chat::find($request->input('id'));
        $msg->delete();
        return response()->json([
            'status' => 'deleted',
        ]);
    }
}
