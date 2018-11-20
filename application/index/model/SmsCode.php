<?php
namespace app\index\model;
use think\Model;

/**
 * 手机验证码表 模型
 */
class SmsCode extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = ['create_time'];
    protected $update = ['create_time'];

    // 关闭自动写入时间戳
    protected $autoWriteTimestamp = false;

    //在数据赋值的时候自动进行转换处理
    protected function setCreateTimeAttr()
    {
        //创建时间
        return time();
    }

    //在获取数据的字段值后自动进行处理
    public function getCreateTimeAttr($value)
    {
        //装换为时间戳
        return $value;
    }

}