<?php
namespace App\Http\Controllers;

//使用类
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;
//购物车详情页
class MycarConterller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    //添加购物车成功
    public function add_car(Request $request){
        $attr_details_id = $request->input('id');
        $attr_details_id = substr($attr_details_id,1);
        $goods_name = $request->input('goods_name');
        $user=auth()->user();
        $num = $request->input('num');
        $categories  = DB::select("select * from product where good_name='$goods_name' and attr_details_id= '$attr_details_id'");  //查询商品的属性
        foreach($categories as $k=>$v) {   //对象转数组
            $sellers[$k] = (array)$v;
        }
        $arr=DB::select("select * from mycar where user_id='$user->id' and shopping_id=".$sellers[0]['product_id']);
        if (empty($arr)){
            $res=DB::table('mycar')->insert(['user_id'=>$user->id,'shopping_id'=>$sellers[0]['product_id'],'shopping_price'=>$sellers[0]['price'],'shopping_cate'=>$sellers[0]['attr_details_name'],'shopping_ku'=>$sellers[0]['inventory'],'shopping_num'=>$num,'goods_name'=>$sellers[0]['good_name']]);
        }else{
            $res=DB::table('mycar')->where('user_id',$user->id)->where('shopping_cate',$sellers[0]['attr_details_name'])->update(['shopping_num'=>$arr[0]->shopping_num+$num]);
        }
        if ($res){
            $res2=['status'=>'ok','code'=>'1','message'=>'添加购物车成功'];
            return $res2;
        }
    }
    public function myCar(Request $request){
        $attr_details_id = $request->input();
        if (empty($attr_details_id)){

        }
        $user=auth()->user();
        $categories  = DB::select("select * from mycar where user_id='$user->id'");
        return $categories;
    }
    //购物车数量减
    public function num_reduce(Request $request){
        $shopping_id = $request->input('shopping_id');
        $arr1 = DB::select("select * from mycar where shopping_id='$shopping_id'");
        if ($arr1[0]->shopping_num==1){
            $res=DB::table('mycar')->where('shopping_id',$shopping_id)->delete();
            if ($res){
                $res2=['status'=>'ok','code'=>'1','message'=>'-1'];
                return $res2;
            }
        }else{
            $arr=DB::table('mycar')->where('shopping_id',$shopping_id)->update(['shopping_num'=>$arr1[0]->shopping_num-1]);
        }
    }
    //购物车数量加
    public function num_add(Request $request){
        $shopping_id = $request->input('shopping_id');
        $arr1 = DB::select("select * from mycar where shopping_id='$shopping_id'");
        $arr=DB::table('mycar')->where('shopping_id',$shopping_id)->update(['shopping_num'=>$arr1[0]->shopping_num+1]);
    }
    //购物车第二部商品
    public function myCartwo(Request $request){
        $shopping_id = $request->input('id');
        $shopping_id=substr($shopping_id,0,strlen($shopping_id)-1);
        $shopping_id=explode(',',$shopping_id);
        $arr=[];
        foreach ($shopping_id as$k=>$v) {
            $arr[] = DB::select("select * from mycar where shopping_id='$v'");
        }
        $cate=$this->demo($arr,$data);
        return $cate;
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
    //查询默认地址
    public function selectaddress(){
        $user=auth()->user();
        $arr = DB::select("select * from address where user_id='$user->id' and `default`='1'");
        return $arr;
    }
    public function add_address(Request $request){
        $shopping_id = $request->input('shopping_id');
        $default_address = $request->input('default_address');
        $totalPrice = $request->input('totalPrice');
        $address=$default_address[0]['address'];
        $order_id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $user=auth()->user();
        $shopping_id=substr($shopping_id,0,strlen($shopping_id)-1);
        $shopping_id=explode(',',$shopping_id);
        foreach ($shopping_id as$k=>$v) {
            $res= DB::select("select * from mycar where shopping_id='$v' and user_id='$user->id'");
            $res1=DB::table('order_details')->insert(['goods_name'=>$res[0]->goods_name,'goods_type'=>$res[0]->shopping_cate,'address'=>$address,'price'=>$res[0]->shopping_price,'num'=>$res[0]->shopping_num,'order_id'=>$order_id]);
        }
        if ($res1&&$res1){
            $res2=DB::table('total_order')->insert(['time'=>strtotime(date("Y/m/d H:i:s")),'status'=>0,'u_id'=>$user->id,'total_order_id'=>$order_id]);
        }
        if ($res&&$res1&&$res2){
            $res3=['status'=>'ok','code'=>'1','message'=>'添加订单成功','order_id'=>$order_id];
            return $res3;
        }
    }

}