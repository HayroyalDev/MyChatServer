<?php
/**
 * Created by PhpStorm.
 * User: mavericks
 * Date: 5/6/18
 * Time: 6:25 PM
 */

namespace App\Helper;


use Illuminate\Support\Facades\Log;

class JsonHelper
{
    public static function success($message = "success", $data = null){
        //Log::info("Data", [response()->json(['status' => 1, 'message' => $message, 'data' => $data])]);
        if(isset($data))
            return response()->json(['status' => 1, 'message' => $message, 'data' => $data]);
        else
            return response()->json(['status' => 1, 'message' => $message]);

    }

    public static function error($message = "An Error Occurred", $data = null){
        //Log::info("Error", [response()->json(['status' => 0, 'message' => $message, 'data' => $data])]);
        if(isset($data))
            return response()->json(['status' => 0, 'message' => $message, 'data' => $data]);
        else
            return response()->json(['status' => 0, 'message' => $message]);
    }
}