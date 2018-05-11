<?php

namespace App\Http\Controllers;

use App\Helper\JsonHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create(Request $request){
        if($request->type === "registration"){
            $val = Validator::make($request->all(),[
                'username' => 'required|unique:users',
                'password' => 'required'
            ],[
                'username.exists' => 'username not available'
            ]);
            if($val->fails())
            {
                $error = implode("\n", $val->errors()->all());
                return JsonHelper::error([$error]);
            }
            $user = new User();
            $user->username = $request->username;
            $user->password = $request->password;
            if($user->save()){
                return JsonHelper::success("Account Creation Successful", [$user]);
            }else{
                return JsonHelper::error("Unable to create Account", [$user]);
            }
        }elseif($request->type == "login"){
            $user = User::where(['username' => $request->username, 'password' => $request->password])->first();
            if(isset($user)){
                return JsonHelper::success("Success", [$user]);
            }else{
                return JsonHelper::error("User Not Found");
            }
        }else{
            return JsonHelper::error("Invalid Request Type");
        }

    }

    public function getUser(Request $request){
        $ids = explode(',', $request->ids);
        $users = [];
        foreach ($ids as $id){
            $user = User::find($id);
            if(isset($user)){
                $users[] = $user;
            }
        }
        return JsonHelper::success("Users Found", $users);

    }
}
