<?php
namespace app\common\model;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/29
 * Time: 14:15
 */
class UserData extends Common{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    public static function getResultByUid( $uid ){

    }

    public static function getGeTuiByUid( $uid ){
        $data = self::where('uid',$uid)->where("platform" , 'in' , [0,1,2])->field('registration_id ,platform')->select();
        return $data;
    }

}