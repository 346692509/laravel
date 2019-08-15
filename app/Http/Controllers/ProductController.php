<?php
namespace App\Http\Controllers;

//使用类
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;
//商品详情页
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['product','product_goods']]);
    }
//    public function me()
//    {
//        return response()->json(auth()->user());
//    }
    public function product(Request $request){         ///查单品详情页 查出所有属性
        $goods_id = $request->input('goods_id');
        $goods_name = $request->input('goods_name');
        $categories  = DB::select("select * from goods join goods_attr on goods.goods_id=goods_attr.goods_id join attribute on goods_attr.attr_id=attribute.ibute_id join attr_details on goods_attr.attr_details_id=attr_details.id where goods_name='$goods_name' and goods.goods_id='$goods_id'");
        foreach($categories as $k=>$v) {   //对象转数组
            $sellers[$k] = (array)$v;
        }

        $arr=[];
        foreach ($sellers as $ka=>$va){
            $arr[$va['ibute_name']][$va['details_name']][]=$va;
        }
//        var_dump($arr);
        return $arr;
    }
    public function price(){

    }
    public function product_goods(Request $request){    //获取单品的价格和库存详情
        $attr_details_id = $request->input('id');
        $attr_details_id = substr($attr_details_id,1);
        $goods_name = $request->input('goods_name');
        $categories  = DB::select("select * from product where good_name='$goods_name' and attr_details_id= '$attr_details_id'");
        return $categories;
    }

}