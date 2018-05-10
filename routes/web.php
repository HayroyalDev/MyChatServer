<?php

use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Utility
Route::get('/clear', function ()
{
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    dd('cleared');
});

//Home
Route::get('/','HomeController@Home')->name('home');
Route::get('/about-and-faq','HomeController@About')->name('about');
Route::get('/contact','HomeController@Contact')->name('contact');

//Account
Route::get('/register','AccountController@register')->name('register');
Route::get('/register/referral/{r_link}','AccountController@registerRef')->name('register_referrals');
Route::post('/register','AccountController@registerPost')->name('register_post');
Route::get('/login','AccountController@login')->name('login');
Route::post('/login','AccountController@loginPost')->name('login_post');
Route::get('/logout','AccountController@logout')->name('logout');
Route::get('/forgot-password','AccountController@forgotPassword')->name('forgot_password');
Route::post('/forgot-password/post','AccountController@forgotPasswordPost')->name('forgot_password_post');
Route::get('/reset-password/{token}','AccountController@resetLink')->name('reset_link');
Route::post('/reset-password/{token}','AccountController@recoverPassword')->name('change_password');
Route::post('/mail/visitor','UtilityController@SendVMail')->name('Vmail');




Route::group(['prefix' => '/api/'], function (){

    Route::group(['prefix' => '/user/'], function(){
        Route::post('create','UserController@create');
        Route::post('search','UserController@search');
    });

    Route::group(['prefix' => 'message'], function(){
        Route::post('create','MessageController@create');
        Route::post('undelivered','MessageController@undelivered');
        Route::get('old','MessageController@old');
        Route::post('status', "MessageController@status");
    });
});
