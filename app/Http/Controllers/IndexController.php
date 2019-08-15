<?php
namespace App\Http\Controllers;

//使用类
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//后台首页控制器
class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','floor','catry']]);
    }
    public function my(){
        $user=auth()->user();
        $id=$user->id;
        return $id;
    }
    //Vue首页 热扫商品
    public function index(){
        $user = DB::select("select * from goods ");
        return response()->json($user);
    }
    //Vue首页 左侧导航
    public function catry(){
        $categories  = DB::select("select * from goods_cate ");
        $cate=$this->demo($categories,0,0);
        return $cate;
    }
    function demo($arr,$id,$level)
    {
        $list =array();
        foreach ($arr as $k=>$v){
            if ($v->pid==$id){
                $v->level=$level;
                $v->son = $this->demo($arr,$v->id,$level+1);
                $list[] = $v;
            }
        }
        return $list;
    }
    //Vue首页 楼层商品
    public function floor(){
        $categories  = DB::select("select * from floor join product on floor.productid=product.product_id join goods on product.product_goods_id=goods.goods_id ");
        foreach($categories as $k=>$v) {   //对象转数组
            $sellers[$k] = (array)$v;
        }
        $arr=[];
        foreach ($sellers as $ka=>$va){
            $arr[$va['floor_num']][$va['floor_name']][]=$va;
        }
//        var_dump($arr);
        return $arr;
    }
    public function product(){
        echo 123;
//        $categories  = DB::select("select * from floor join product on floor.productid=product.product_id join goods on product.product_goods_id=goods.goods_id ");
//        foreach($categories as $k=>$v) {   //对象转数组
//            $sellers[$k] = (array)$v;
//        }
//        $arr=[];
//        foreach ($sellers as $ka=>$va){
//            $arr[$va['floor_num']][$va['floor_name']][]=$va;
//        }
////        var_dump($arr);
//        return $arr;
    }
}