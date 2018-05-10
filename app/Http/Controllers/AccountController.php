<?php

namespace App\Http\Controllers;

use App\Helper\AppMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    //


    //GET
    public function register()
    {
        return view('Account.register',['title' => 'Register']);
    }

    public function login()
    {
        Auth::logout();
        return view('Account.login',['title' => 'Login']);
    }


    //Post
    public function registerPost(Request $request)
    {
        $this->validate($request,
            [
                'fullname' => 'required',
                'reg_type' => 'required',
                'pay_type' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            ],['confirm_password.same' => '  Passwords Does Not Match']
        );
        return redirect()->back();
    }

    public function loginPost(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);
        //dd($request->all());
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            //dd(Auth::user());
            if(Auth::user()->is_active)
            {
                if(Auth::user()->role_id == 3)
                {
                    return redirect()->action('UserController@Dashboard');
                }

                if(Auth::user()->role_id < 3)
                {
                    return redirect()->action('AdminController@Dashboard');
                }
            }
            else{
                Session::flash('error', 'Please contact the admin as your account has entered the dormancy period.');
                Auth::logout();
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error','Incorrect Username/Password');
            return redirect()->back();
        }
    }

    public function logout()
    {
        if(Auth::Check())
        {
            Auth::logout();
            return redirect()->action('AccountController@Login');

        }
        else{
            return redirect()->action('AccountController@Login');
        }
    }

    public function forgotPassword()
    {
        return view('Account.forget_password',['title' => 'Forgot Password','t' => 1]);
    }

    public function forgotPasswordPost(Request $request, AppMailer $mailer)
    {
        $this->validate($request,[
            'email' => 'required'
        ]);
        //dd($request->all());
        return redirect()->back();

    }

    public function resetLink($token)
    {
        $d = ResetPassword::FindByToken($token);
        if($d == null)
        {
            Session::flash('error','Incorrect Token, Please Try Resetting Your Password Again');
            return view('Account.forget_password',['title' => 'Reset Password','t' => 3]);
        }
        else{
            return view('Account.forget_password',['title' => 'Reset Password','t' => 2, 'token' =>  $token]);
        }
    }

    public function recoverPassword(Request $request, $token)
    {
        $this->validate($request,[
            'email' =>  'required',
            'new_password' => 'required',
            'conf_new_password' => 'required|same:new_password',
        ],['conf_new_password' => 'Password Mismatch']);

        $d = ResetPassword::FindByToken($token);
        if($d == null)
        {
            return redirect()->action('AccountController@ForgotPassword');
        }

        if($request->email === $d->email || $token === $d->token)
        {
            $m = User::FindByEmail($d->email);
            if($m != null)
            {
                $m->password = Hash::make($request->new_password);
                $m->save();
                $d->used = true;
                $d->save();
                Session::flash('success','Password Successfully Changed');
                return redirect()->action('AccountController@Login');
            }
            else{
                Session::flash('error','Sorry, We Could Not Find The Account You Provided');
                return redirect()->action('AccountController@Register');
            }
        }
    }
}
