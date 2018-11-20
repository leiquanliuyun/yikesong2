<?php
namespace app\useradmin\model;
use think\Model;

/**
 * 优惠券 模型
 */
class Withdraworder extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $updateTime = false;
    protected $append = ['status_text'];//会自动在查询结果中显示该项，不用显示调用
    //在数据赋值的时候自动进行转换处理
    /*protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }*/



    public function getStatusTextAttr($value,$data)
    {
        //幻灯片状态
        switch ($data['status']) {
            case '1' :
                return '审核中';
            case '2' :
                return '已通过';
            case '3' :
                return '已退回';
            default :
                return '';
        }
    }
    //定义一对一关联方法
    public function member()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Member','uid','id')->field('mobile');
    }
}