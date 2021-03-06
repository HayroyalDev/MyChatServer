<?php

namespace App\Http\Controllers;

use App\Helper\JsonHelper;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;

class MessageController extends Controller
{
    //
    public function create(Request $request){
        $val = Validator::make($request->all(),[
            'from' => 'required|exists:users,id',
            'mid' => 'required',
            'to' => 'required|exists:users,id',
            'message' => 'required'
        ],[
            'mid.required' => "Message ID is required",
            'recipient_type.required' => "Recipient Type is required"
        ]);

        if($val->fails())
        {
            $error = implode("\n", $val->errors()->all());
            return JsonHelper::error($error);
        }
        $msg = new Message();
        $msg->mid = $request->mid;
        $msg->from = $request->from;
        $msg->to = $request->to;
        $msg->status = 1;
        $msg->message = $request->message;
        if($msg->save()){
            return JsonHelper::success("Message Sent", [$msg]);
        }else{
            return JsonHelper::error("Message Not Sent", [$msg]);
        }
    }

    public function undelivered(Request $request){
        $val = Validator::make($request->all(),[
          'to' => 'required|exists:users,id'
        ]);
        if($val->fails())
        {
            $error = implode("\n", $val->errors()->all());
            return JsonHelper::error([$error]);
        }
        $msg = Message::where(['to' => $request->to, 'status' => 1])->get();
        return JsonHelper::success("Messages Fetched", $msg);
    }

    public function status(Request $request){
        $val = Validator::make($request->all(),[
            'mid' => 'required|exists:messages,mid',
            'status' => 'required'
        ],[
            'mid.required' => "Message ID is required",
            'recipient_type.required' => "Recipient Type is required"
        ]);
        if($val->fails()){
            $error = implode("\n", $val->errors()->all());
            return JsonHelper::error([$error]);
        }

        $ids = array_unique(explode(',', $request->mid));
        $msgs = [];
        foreach ($ids as $mid){
            $msg = Message::where('mid',$mid)->first();
            if(isset($msg)){
                $msg->status = $request->status;
                if($msg->save()){
                    $msgs[] = $msg;
                }
                Log::info("Message Status", ["Message $mid Found"]);
            }
        }
        if(!empty($msgs)){
            return JsonHelper::success("Status Changed", $msgs);
        }else{
            return JsonHelper::error("No Data");
        }

    }

    public function aStatus(){
        foreach (Message::all() as $mss){
            $mss->status = 1;
            $mss->save();
        }
    }
}
