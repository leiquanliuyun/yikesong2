<?php
namespace app\index\model;
use think\Model;

/**
 * 客服信息表 模型
 */
class InfoService extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];

    //在数据赋值的时候自动进行转换处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }

    //在获取数据的字段值后自动进行处理
    public function getSexAttr($value)
    {
        //性别
        switch ($value) {
            case '1' :
                return '男';
            case '2' :
                return '女';
            default :
                return null;
        }
    }


    //定义一对一关联方法  用户表
    public function user()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('User','uid','uid')->field('uid');
    }

}