<?php
namespace app\index\model;
use think\Model;

/**
 * 公告表 模型
 */
class Notice extends Model
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
    public function getShowAttr($value)
    {
        //公告状态
        switch ($value) {
            case '1' :
                return '发布';
            case '2' :
                return '下架';
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

}