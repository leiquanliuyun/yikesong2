<?php
namespace app\common\model;
use think\Model;

/**
 * 角色表 模型
 */
class Role extends Model
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
    public function getStatusTextAttr($value,$data)
    {
        //用户角色
        switch ($data['status']) {
            case 1 :
                return '正常';
            case 2 :
                return '禁用';
            default :
                return '';
        }
    }




}