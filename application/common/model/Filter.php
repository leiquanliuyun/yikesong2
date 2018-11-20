<?php
namespace app\common\model;
use think\Model;

/**
 * 筛选条件 模型
 */
class Filter extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $updateTime = false;
    //在数据赋值的时候自动进行转换处理
    /*protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }*/
    //定义一对一关联方法  园区服务内容表
    public function housecontent()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Housecontent','id','filter_id');
    }
    //定义一对一关联方法  资质转让内容表
    public function companycontent()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Companycontent','id','filter_id');
    }

}