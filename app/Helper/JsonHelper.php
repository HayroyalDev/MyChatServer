<?php
/**
 * Created by PhpStorm.
 * User: mavericks
 * Date: 5/6/18
 * Time: 6:25 PM
 */

namespace App\Helper;


class JsonHelper
{
    public static function success($message = "success", $data = null){
        return response()->json(['status' => 1, 'message' => $message, 'data' => $data]);
    }

    public static function error($message = "An Error Occurred", $data = null){
        return response()->json(['status' => 0, 'message' => $message, 'data' => $data]);
    }
}