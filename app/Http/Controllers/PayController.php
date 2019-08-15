<?php

namespace App\Http\Controllers;

use Yansongda\Pay\Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['return','notify']]);
    }


    protected $config = [
        'alipay' => [
            'app_id' => '2016101000650515',
            'notify_url' => 'http://iev8iz.natappfree.cc/blog/public/api/pay/notify',
            'return_url' => 'http://localhost/blog/public/api/pay/return',
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAq70KnAjyillVlcVpHZ154oD77X01Ob9uPxaglXXKwWaKqFRxB1vMvuBcB6PcGjcbCi1vDk8Co7AnUZjgKDuXgaHaHd+GuKEstqmkgtnCXw+deQ1IUrlWluvZJ28QxYgauEY3W8nOLRUnjo/tOtCBfvwWx+4xHUAAvV/1qTIYJQ/TvOGVn7Eqrd4GQFwpdZpT4hzVSzIMVMBCtJRQTR50C7OfAseMD9/w/1Zy24YwTg0DmPkHdAc+8Lpqd88n3KJt2Mw3XF8Hc+GJ3Uth6cCOM8jQchP+HfHZNzX0xPoE/YVvb/kSVxsthMW7ECYnLdXJRqgrD5sWq1U6J/hU1EShOwIDAQAB',
            'private_key' => 'MIIEpAIBAAKCAQEAp4WWfUzU3qLw1yVnYATFVaaV1+9zp6s4g5k4lwwWCp8nFdJLsnyCKj+uYDl6JHv4y31sUEdvhYU85xoHJHSPHEhlIzFKmebxqNsCb/AHluWrGRanuaAqvXey9fw+Q5TPZFNe8snsibM3jimZDv8tS3oJ/PxmYda7ilY3X0KzqIP8HxnqevwwwjXN2aqK2w1s2N+0XeuKUvmCUTo8TrePZd//d+EVjROWZcmzY8fydYAazGQQC5mjYXWzRwCP+GZk7/Mh2WfNLcznkOenrYT8qYX2jkVuv8Tj6JrNvG5SpZQcyW8fl7TuY2vZB8qFVXN0bRgGnUxOZ4T7yFe5yEOkkQIDAQABAoIBAFeekZW75+MCyoFdOPKJzET+OoWU4qMh9Q8vxt+KHMU1dRX+xkT2xoYehLAjn/UROpIEafCbTINDqxUdEftJU8jxPKTplSFmH6O32VB+9RlByvn2VYfLdE9hGiN66X4jiU3qJVFLuAJV0Ir0yu+DHEfL7wl5Yntt+u1iJtgWZS2P8ihexdXgy+m6N0hyFjsmQQZ4RUeI2Bq5HROGJPKwsGurVx5dsrzsNBEfhfbTaNIF6KRtyrRTiXbbufEd3ueQFGfZQYFwXF4Q1etOUBEPwU+aaIyTy8FI8O+utMurZh+TNCkt166NZr96VuYe7a5kZyN4cw+eYgZiwKrZsGCOfbkCgYEA2H8T73hJxVSvcbwi2/akZNu2VqqGHzQlOU4f+zHbaIoiNLCBE6ecGfVRxBgC/JynY35WlFZ27+aJe3T4eI6947Hyr530/4QDDF8QkIpkofdN5Jb4IVo1oKeeAk31oDpkGcK4c8EgJAWiNuHSpsouxLq9z7gSh2tZrMUu1aRLFnsCgYEAxhbSqA/tF7BV8USrOjo3zG5P8woO1Udbu46Orq6dbJ//ExMZFGnIrLCcFp8mkwPjfs/RxdZl1AJjwByJiF5E5Gwg992aKbrWVBdbD43V4Ix2DmstolGVhQAikOROO8Tuppwri2C4/MGWJM+irADZbe5uTOG4P7o/a6Yhn24M6WMCgYEAlPv+kBFLUwzQH4jExHKa9v1sIYAABIEyDJmC0K6iuvI0T+YXLZtuhT6ZIkUT6Rs05fcPRc2q07Tmb5szUmOIsaTHyar96sjCAEV7dLyIPB2f8OsjnnAh74jp59QWcEk+kau6m44PvDpUQ0hsWnNQc/m+na+isKZjc83OSR9ivPkCgYEAqtQn2zPQ3er+UtFLcS2fzTccz5XO8PNXEsqAFfkUXgck7Ig9yrLnkrXEBmY91+80k7oZSCn7KvjcmWKC4Q2bD/qH+/op5u7vwxlZOHKzDbFUZl7bve3pqgdRx+574WOWBC0OCGbjYWZ5W0mNGhSpbfp/OOI4CQ1PgECHIny/uc0CgYBkaETHBsL1HHKkgXlOqaIcfZnQ1L14zaln4fuVYjxHlNyBOecg8YQgJi4ukrDszzn624s+A4KS0wwY34hrJjHVLzgKYXd5Vx6hM7BJxMtJt5ZF+5TGhPy2pZfufRL4JYubeSbcoZi//qwcihW4i+08205wPQJP0GZh1QaQoLZkpQ==',
        ],
    ];

    public function index(Request $request)
    {
        $user=auth()->user();
        $order_id=$request->get('order_id');
        $res= DB::select("select * from total_order join order_details on total_order.total_order_id=order_details.order_id where order_id='$order_id' and u_id=$user->id");
        $price=0;
        $order='';
        foreach ($res as$k=>$v) {
            $price+=($v->price*$v->num);
            $order=$v->order_id;
        }

        $config_biz = [
            'out_trade_no' => $order,
            'total_amount' => $price,
            'subject'      => 'test subject',
        ];

        $pay = new Pay($this->config);

        return $pay->driver('alipay')->gateway()->pay($config_biz);
    }

    public function return(Request $request)
    {
        $pay = new Pay($this->config);
//        return $pay->driver('alipay')->gateway()->verify($request->all());
        $arr=$pay->driver('alipay')->gateway()->verify($request->all());
        if($arr){
            $order_id=$arr['out_trade_no'];
            $total_price=$arr['total_amount'];
            $order_time=$arr['timestamp'];
            header("location:http://localhost:8080/#/BuyCarthree?order_id=$order_id&total_price=$total_price&order_time=$order_time");
        }else{
            header("location:http://localhost:8080/#/BuyCarthree");
        }

    }

    public function notify(Request $request)
    {
        $pay = new Pay($this->config);

        if ($pay->driver('alipay')->gateway()->verify($request->all())) {
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
//            DB::table('total_order')->where('total_order_id',$request->out_trade_no)->updata(['status'=>1]);
            file_put_contents(storage_path('notify.txt'), "收到来自支付宝的异步通知\r\n", FILE_APPEND);
            file_put_contents(storage_path('notify.txt'), '订单号：' . $request->out_trade_no . "\r\n", FILE_APPEND);
            file_put_contents(storage_path('notify.txt'), '订单金额：' . $request->total_amount . "\r\n\r\n", FILE_APPEND);
        } else {
//            DB::table('total_order')->where('total_order_id',$request->out_trade_no)->updata(['status'=>1]);
            file_put_contents(storage_path('notify.txt'), "收到异步通知\r\n", FILE_APPEND);
        }

        echo "success";
    }
}