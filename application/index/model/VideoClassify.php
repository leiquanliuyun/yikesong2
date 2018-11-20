<?php
namespace app\index\model;
use think\Model;

/**
 * 视频分类表 模型
 */
class VideoClassify extends Model
{

    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];

    //在获取数据的字段值后自动进行处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }
    //在获取数据的字段值后自动进行处理
    public function getPictureAttr($value,$data)
    {
        //图片地址
        return __WWW__."/uploads/images/".$data['picture'];
    }


}