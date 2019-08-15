<?php

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
Route::get('/index','IndexController@index');  //Vue首页 热扫商品
Route::get('/catry','IndexController@catry');   //Vue首页 左侧导航
Route::get('/floor','IndexController@floor');   //Vue首页 楼层商品
Route::get('/product','IndexController@product');
Route::get('/address','TestController@address');
Route::get('/product','TestController@product');
Route::get('/demo','MycarConterller@demo');

Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'AuthController@user');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh');
});
