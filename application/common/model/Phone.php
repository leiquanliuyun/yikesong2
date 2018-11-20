<?php
namespace app\common\model;
use think\Model;

/**
 * 客服 模型
 */
class Phone extends Model
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


}