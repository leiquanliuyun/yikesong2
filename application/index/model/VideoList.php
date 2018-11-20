<?php
namespace app\index\model;
use think\Model;

/**
 * 视频分类列表 模型
 */
class VideoList extends Model
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

    //定义关联方法  视频分类表
    public function classify()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('VideoClassify','classify_id','classify_id');
    }

}