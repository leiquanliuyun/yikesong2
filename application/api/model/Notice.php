<?php
namespace app\api\model;
use think\Model;

/**
 * 公告表 模型
 */
class Notice extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];

    //定义一对一关联方法  用户表
    public function member()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Member','uid','id');
    }
}