<?php
namespace app\api\model;
use think\Model;

/**
 * 幻灯片 模型
 */
class Collect extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $updateTime = false;
    //protected $append=['picture_show'];

    //在获取数据的字段值后自动进行处理
    /*public function getPictureShowAttr($value,$data)
    {
        //图片地址
        $web_url=config('web_url');
        return $web_url.__PUBLIC__."/uploads/images/".$data['picture'];
    }*/

    public function getStatusTextAttr($value,$data)
    {
        //幻灯片状态
        switch ($data['status']) {
            case '1' :
                return '收藏';
            case '0' :
                return '取消收藏';
            default :
                return '';
        }
    }

}