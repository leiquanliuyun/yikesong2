<?php
namespace app\common\model;
use think\Model;

/**
 * 需求表 模型
 */
class PactOption extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $append=['icon_show'];
//在获取数据的字段值后自动进行处理
    public function getIconShowAttr($value,$data)
    {
        //图片地址
        return __PUBLIC__."/uploads/images/".$data['icon'];
    }
}