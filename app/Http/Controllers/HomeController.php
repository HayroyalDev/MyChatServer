<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function home()
    {
        if(Auth::check()){
            return redirect()->action('UserController@dashboard');
        }
        return view('Utility.default',['title' => 'Home']);
    }

    public function about()
    {
        return view('Utility.about',['title' => 'About']);
    }

    public function contact()
    {
        return view('Utility.contact',['title' => 'Contact']);
    }
}
