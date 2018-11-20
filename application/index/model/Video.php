<?php
namespace app\index\model;
use think\Model;

/**
 * 视频表 模型
 */
class Video extends Model
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
    public function getPictureAttr($value,$data)
    {
        //图片地址
        return __WWW__."/uploads/images/".$data['picture'];
    }

    public function getShowAttr($value)
    {
        //资讯表状态
        switch ($value) {
            case '1' :
                return '发布';
            case '2' :
                return '草稿';
            default :
                return '';
        }
    }

    public function getRecommendAttr($value)
    {
        //是否推荐
        switch ($value) {
            case '1' :
                return '推荐';
            case '2' :
                return '未推荐';
            default :
                return '';
        }
    }

    //定义关联方法  视频分类列表
    public function classify()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('VideoList','list_id','list_id');
    }

}