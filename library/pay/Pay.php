<?php
namespace app\library\pay;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/18
 * Time: 16:10
 */
interface Pay{

    // 支付方法
    public function pay( $order_no , $money , $desc );

    // 退款操作
    public function refund();

    // 支付回调验签
    public function callbackCheck();
}