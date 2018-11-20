<?php
namespace app\api\model;
use think\Cache;

/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/4/26
 * Time: 16:12
 * 发送短信验证码
 */
class SmsCode extends Sms{

    const KEY_PREFIX = '';      // 键名前缀
    const REPEAT_SEND = 60; // 重复发送时间， 单位秒
    const TIME_OUT = 1000000;    // 验证超时时间，单位秒
    // 验证码类型，1：注册
    const REGISTER_TYPE = 1;
    // 2：短信登录
    const LOGIN_TYPE = 2;
    // 3：找回密码（重置密码）
    const RESET_PASSWORD_TYPE = 3;
    // 4：修改手机号码
    const CHANGE_MOBILE = 4;
    // 5：用户绑定user_account
    const BIND_USER_ACCOUNT = 5;
    // 6：第三方登录绑定的手机号码
    const AUTH_LOGIN_BIND =6;
    // 7：设置平台交易密码，不用
    const SET_PAY_PWD = 7;

    public static function getTpl( $sms_type ){
        $tpl_code = '';
        switch ($sms_type){
            case self::REGISTER_TYPE:
                $tpl_code = self::REGISTER_VERIFY;
                break;
            case self::LOGIN_TYPE:
                $tpl_code = self::LOGIN_VERIFY;
                break;
            case self::AUTH_LOGIN_BIND:
                $tpl_code = self::AUTH_LOGIN_BIND_PHONE;
                break;
            case self::CHANGE_MOBILE:
                $tpl_code = self::AUTH_VERIFY;
                break;
            case self::BIND_USER_ACCOUNT:
                $tpl_code = self::AUTH_VERIFY;
                break;
            default:
                $tpl_code = self::AUTH_VERIFY;
                break;
        }
        return $tpl_code;
    }
    /**
     * 获取键名，前缀+类型+手机号码
     */
    public static function getSmsKey( $mobile , $type ){
        return self::KEY_PREFIX.$type."_".$mobile;
    }
    /**
     * 创建验证码并调用短信系统发送
     * @param $mobile string 手机号码
     * @param $type  integer 验证码类型：
     * @param $user_type int 这个参数额外增加的，用处为：测试的时候服务人员注册验证码跳过；
     * @return integer 返回小于1的数表示出错！
    */
    public static function create( $mobile , $type , $user_type = null ){
        // 键名，为了区分是哪种类型的验证码
        $key = self::getSmsKey( $mobile ,  $type );

        $res = Cache::get( $key );
        if( !empty($res) && (time() - $res['time']) < self::REPEAT_SEND  ){
            // 发送时间过于未超过重复发送时间
            return '请勿频繁发送！';
        }

        if( config('app_env') == 'dev'  || ($user_type == 2 && self::REGISTER_TYPE) ){
            // 开发环境验证码固定，商家申请，服务人员注册，暂时为固定的6个1
            $code = '111111';
        }else{
            $code = $res ? $res['code'] : randCode(6 , 1);//halt($code);
            // 调用发送短信系统
            if( !self::send( $mobile , ['code' => $code ] , self::getTpl( $type ) ) ){
                return '短信系统发送失败！';
            }
        }

        Cache::set( $key , ['code'=>$code , 'time'=>time()] ,  self::TIME_OUT );
        return true;
    }

    /**
     * 检查短信验证码
     * @param $mobile string 手机号码
     * @param $type  integer 区分验证码类型
     * @param $sms_code string 客户端输入的短信验证码
     * @return bool 验证码验证通过返回true
    */
    public static function checkSmsCode( $mobile, $type , $sms_code ){
        // 键名，为了区分是哪种类型的验证码
        $key = self::getSmsKey( $mobile ,  $type );

        $res = Cache::get($key);
        if( !empty($res) && $res['code'] == $sms_code ){
            // 验证码通过！
            return true;
        }
        return false;
    }

    /**
     * 校验成功之后删除验证码记录
     * @param $mobile string 手机号码
     * @param $type  integer 验证码类型
     * @void
    */
    public static function deleteSmsCode($mobile, $sms_type){
        // 键名，为了区分是哪种类型的验证码
        $key = self::getSmsKey( $mobile ,  $sms_type );
        Cache::rm( $key );
    }


    /**
     * 发送短信验证码前的验证
    */
    public static function sendBeforeCheck(){

    }
}