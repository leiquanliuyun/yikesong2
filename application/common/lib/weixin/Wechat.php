<?php
namespace app\common\lib\weixin;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/9
 * Time: 9:49
 */
class Wechat{

    private $app_id;
    private $app_secret;


    public function __construct()
    {
        $this->app_id = config('weixinpay.app_id');
        $this->app_secret = config('weixinpay.app_secret');
    }


    public function authLogin( $code , $device = '' ){
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        if( $device == 'wx' ){
            // 微信小程序登录的值
            $this->app_id = config('weixinpay.ot_app_id');
            $this->app_secret = config('weixinpay.ot_app_secret');
            $url = 'https://api.weixin.qq.com/sns/jscode2session?';
            $param = [
                'appid'=>$this->app_id,
                'secret'=>$this->app_secret,
                'js_code'=>$code,
                'grant_type'=>'authorization_code'
            ];
        }else{
            $param = [
                'appid'=>$this->app_id,
                'secret'=>$this->app_secret,
                'code'=>$code,
                'grant_type'=>'authorization_code'
            ];
        }


        $remote = curl_request( $url.http_build_query($param) );
        file_put_contents('./wechat_login.log' , $remote);
        $data = json_decode( $remote , true );
        if( isset( $data['openid'] ) ){
//            if( isset( $data['unionid'] ) && !empty($data['unionid']) ){
//                return $data['unionid'];
//            }
            if( $device == 'wx' ){
                // 微信小程序直接返回openid
                return $data['openid'];
            }
            return $this->getUnionid( $data['openid'] , $data['access_token'] );
        }
        return false;
    }


    private function getUnionid( $openid , $access_token ){
        $url = 'https://api.weixin.qq.com/sns/userinfo?';
        $param = [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        ];

        $remote = curl_request($url.http_build_query($param));
        $data = json_decode( $remote , true );
        if( isset( $data['unionid'] ) && !empty($data['unionid']) ){
            $data['auth_key'] =  $data['unionid'] ;
            return $data;
        }
        return false;
    }
}