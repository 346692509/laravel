<?php
namespace App\Http\Controllers;

//使用类
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;
//个人中心控制器
class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
    public function address(Request $request) //查询地址数据库
    {
        $request=$request->input();
        $pid=$request['p_id'];
        $categories  = DB::select("select * from area where parent_id='$pid'");
        return $categories;

    }
    public function address1(Request $request)    //添加地址
    {
        $arr=$request->input();
        $user=auth()->user();
        $name=$arr['name'];
        $myaddress=$arr['myaddress'];
        $phone=$arr['phone'];
//        $res=DB::table('address')->insert(['user_id'=>$user->id,'address_name'=>$name,'phone'=>$arr['phone'],'address'=>$myaddress]);
        $res=DB::insert("insert into `address` (`user_id`, `address_name`, `phone`, `address`) values ($user->id,'$name','$phone','$myaddress')");
        if ($res){
            $res2=['status'=>'ok','code'=>'1','message'=>'添加地址成功'];
            return $res2;
        }
    }
    //我的地址数据
    public function myaddress(){
        $user=auth()->user();
        $arr = DB::select("select * from address where user_id='$user->id'");
        return $arr;
    }
    //删除地址
    public function deladdress(Request $request){
        $address_id=$request->input('address_id');
        $res = DB::delete("delete  from address where address_id='$address_id'");
        if ($res){
            $res2=['status'=>'ok','code'=>'1','message'=>'删除地址成功'];
            return $res2;
        }
    }
    //默认地址
    public function getaddress(Request $request){
        $address = $request->input('ress_id');
        $user=auth()->user();
        $res=DB::table('address')->where('user_id',$user->id)->update(['default'=>0]);
        $res1=DB::table('address')->where('user_id',$user->id)->where('address_id',$address)->update(['default'=>1]);
        if ($res&&$res1){
            $res2=['status'=>'ok','code'=>'1','message'=>'修改默认成功'];
            return $res2;
        }
    }

}