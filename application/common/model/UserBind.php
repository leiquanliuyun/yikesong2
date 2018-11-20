<?php
namespace app\common\model;
use think\Exception;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/9
 * Time: 11:28
 */
class UserBind extends Common{

    protected $updateTime = false;

    protected $type = [
        'detail' => 'json'
    ];

    public function getRsByAuthKeyAndLoginType($auth_key,$login_type)
    {
        return $this->where('auth_key' , $auth_key)
            ->where('type' , $login_type)->find();
    }

    public function getUserInfoByUid($mod, $uid) {
        return $this->where(['type' => $mod, 'uid' => $uid])->order('id desc')->find();

    }
}