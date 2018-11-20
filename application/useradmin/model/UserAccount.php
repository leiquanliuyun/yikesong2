<?php
namespace app\useradmin\model;
/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/4/27
 * Time: 15:34
 */
class UserAccount extends Common{

    const ALIPAY = 1;
    const WECHAT_PAY     = 2;


    /**
     * 获取第三方平台绑定的状态，只显示已绑定的
     */
    public function getUserBindAccount($uid){
        $bind = [];
        $data = $this->where('uid' , $uid)->column('type');
        // 根据类型存在数组里面找到绑定状态；
        in_array(self::ALIPAY ,$data ) && $bind['alipay'] = 1;
        in_array(self::WECHAT_PAY ,$data ) && $bind['wechat_pay'] = 1;
        return $bind;
    }
}