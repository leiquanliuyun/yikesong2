<?php
namespace app\useradmin\model;
use think\Model;

/**
 * 优惠券 模型
 */
class MemberCoupon extends Model
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

    //在获取数据的字段值后自动进行处理
    public function getPictureShowAttr($value,$data)
    {
        //图片地址
        return __PUBLIC__."/uploads/images/".$data['picture'];
    }

    public function getStatusTextAttr($value,$data)
    {
        //幻灯片状态
        switch ($data['status']) {
            case '1' :
                return '未使用';
            case '2' :
                return '已使用';
            default :
                return '';
        }
    }

}