<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatNotification;
use App\Order;
use App\OrderProductAdditionalDetails;
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
       $msgs =  Chat::where('order_id',$request->input('order'))->get();
       $html = view('chat',[
           'msgs' => $msgs,
           'apply' => $request->input('apply'),
//           'product' => $request->input('order_product_id'),
           'order' => $request->input('order')
       ])->render();
       return response()->json([
           'html' => $html
       ]);
    }

    public function saveChat(Request $request){

        if ($request->hasFile('content')) {
            $file = $request->file('content');
            $name = date('his') . '_' .str_replace(' ','',$file->getClientOriginalName()) ;
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
//        $new->order_product_id = $request->input('order_product_id');
        $new->setCreatedAt(now());
        $new->setUpdatedAt(now());
        $new->save();
        /*Generating Notifications*/
        $notification = new ChatNotification();
        $notification->name = $new->name;
        $notification->order_id = $new->order_id;
//        $notification->order_product_id = $new->order_product_id;
        $notification->chat_id = $new->id;
        $notification->status = 'unseen';
        $notification->save();

        $targets =  OrderProductAdditionalDetails::where('order_id',$request->input('order_id'))
           ->get();
        foreach ($targets as $target){
            if($target != null){
                if($target->status_id != 8 && $target->status_id != 6 ){
                    if($request->input('name') == 'Customer'){
                        $target->status ='Update';
                        $target->status_id = 7;
                        $target->status_text = 'Customer Send A Message!';
                        $target->save();
                    }
                    else{
                        $target->status ='In-Processing';
                        $target->status_id = 3;
                        $target->save();
                    }
                }

            }
        }
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

    public function getNotifications(Request $request){
        $order = Order::find($request->input('order'));
        if($order != null){
            $new_msg  =  ChatNotification::where('order_id',$order->id)->where('name',$request->input('target'))->where('status','unseen')->get();
            $count = [];
            if(count($new_msg) > 0){
                array_push($count,count($new_msg));
            }
            else{
                array_push($count,0);
            }
        }
        else{
            $count = -1;
        }

        return response()->json([
            'count' => $count,
        ]);
    }
    public function seenNotifications(Request $request)
    {
        $new_msgs = ChatNotification::where('order_id', $request->input('order_id'))->where('name', $request->input('target'))->where('status','unseen')->get();
        foreach ($new_msgs as $new_msg){
            $new_msg->status = 'seen';
            $new_msg->save();
        }
    }
}
