<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
////路由模型绑定
//Route::get('users/{user}', function (App\User $user) {
//    dd($user);
//});


//Auth 验证
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'MemberController@me');
    Route::post('buyCar', 'ProductController@buyCar');

});
//个人中心 验证
Route::group([

    'middleware' => 'api',
    'prefix' => 'Member'

], function ($router) {
    Route::post('me', 'MemberController@me');         //user信息
    Route::post('address', 'MemberController@address'); //地址管理
    Route::post('address1', 'MemberController@address1'); //地址管理
    Route::post('myaddress', 'MemberController@myaddress'); //查询地址表
    Route::post('deladdress', 'MemberController@deladdress'); //删除地址
    Route::post('getaddress', 'MemberController@getaddress');         //设默认地址

});
//首页
Route::group([

    'middleware' => 'api',
    'prefix' => 'Index'

], function ($router) {
    Route::post('my', 'IndexController@my');         //单纯拿userID

});
//购物车
Route::group([

    'middleware' => 'api',
    'prefix' => 'Mycar'

], function ($router) {
    Route::post('add_car', 'MycarConterller@add_car');         //加入购物车
    Route::post('myCar', 'MycarConterller@myCar');         //购物车数据
    Route::post('num_reduce', 'MycarConterller@num_reduce');         //购物车减
    Route::post('num_add', 'MycarConterller@num_add');         //购物车加
    Route::post('myCartwo', 'MycarConterller@myCartwo');         //购物车加
    Route::post('selectaddress', 'MycarConterller@selectaddress');         //默认地址
    Route::post('add_address', 'MycarConterller@add_address');         //默认地址


});
//支付宝
Route::group([

    'middleware' => 'api',
    'prefix' => 'pay'

], function ($router) {
    Route::get('index', 'PayController@index');
    Route::any('return', 'PayController@return');
    Route::any('notify', 'PayController@notify');

});



Route::post('product', 'ProductController@product');     ///查单品详情页 查出所有属性
Route::post('product_goods', 'ProductController@product_goods');  //获取单品的价格和库存详情

