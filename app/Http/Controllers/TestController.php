<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function product(){

//        $goods_name = '华为p30';
//        $categories  = DB::select("select * from goods join goods_attr on goods.goods_id=goods_attr.goods_id join attribute on goods_attr.attr_id=attribute.ibute_id join attr_details on goods_attr.attr_details_id=attr_details.id where goods_name='$goods_name'");
//        foreach($categories as $k=>$v) {   //对象转数组
//            $sellers[$k] = (array)$v;
//        }
//        echo "<pre>";
////        var_dump($categories);
////        $arr=[];
//        $arr=[];
//        foreach ($sellers as $ka=>$va){
//            $arr[$va['ibute_name']][$va['details_name']][]=$va;
//
//        }
        $res= DB::select("select * from total_order join order_details on total_order.total_order_id=order_details.order_id where total_order.u_id=1 ");
        $price=0;
        $order='';
        foreach ($res as$k=>$v) {
            $price+=($v->price*$v->num);
            $order=$v->order_id;
        }
        echo $price;
        echo $order;
    }
    public function address()
    {
        $shopping_id=[0=>'28',1=>'31'];
        $arr=[];
        foreach ($shopping_id as$k=>$v) {
            $arr[] = DB::select("select * from mycar where shopping_id='$v'");
        }
        $cate=$this->demo($arr,$data);
        var_dump($cate);
//        return $cate;
//        $res2=['status'=>'ok','code'=>'1','data'=>$cate];
//        return $res2;
    }
    public function demo($arr,&$data){
        if (is_array($arr)){
            foreach($arr as $v){
                if (is_array($v)){
                    $this->demo($v,$data);
                } else{
                    $data[] = $v;
                }
            }
        }else{
            $data[] = $arr;
        }
        return $data;
    }

}
