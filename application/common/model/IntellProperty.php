<?php
namespace app\common\model;
use think\Model;

/**
 * 知识产权表 模型
 */
class IntellProperty extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $updateTime = false;

    public function getStatusTextAttr($value,$data)
    {
        //幻灯片状态
        switch ($data['status']) {
            case '1' :
                return '已处理';
            case '0' :
                return '待处理';
            default :
                return '';
        }
    }
    //定义一对一关联方法
    public function member()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Member','uid','id')->field('mobile,realname');
    }
}