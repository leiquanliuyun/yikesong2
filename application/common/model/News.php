<?php
namespace app\common\model;
use think\Model;

/**
 * 幻灯片 模型
 */
class News extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];
    protected $append=['picture_show'];
    //在数据赋值的时候自动进行转换处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }

    //在获取数据的字段值后自动进行处理
    public function getPictureShowAttr($value,$data)
    {
        //图片地址
        return __PUBLIC__."/uploads/images/".$data['picture'];
    }

    public function getStatusAttr($value)
    {
        //幻灯片状态
        switch ($value) {
            case '1' :
                return '发布';
            case '2' :
                return '下架';
            default :
                return '';
        }
    }

}