<?php
namespace app\library\pay;
use app\library\wechat\WxPay;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/18
 * Time: 16:00
 */
class PayFactory{


    const WECHA_PUBLIC_PAY = 1;



    public static function usePay( $type ){
        switch ( $type ){
            case self::WECHA_PUBLIC_PAY:
                return new WxPay();
                break;
            case 2:
                break;
        }
        return null;
    }



}