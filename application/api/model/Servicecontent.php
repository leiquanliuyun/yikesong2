<?php
namespace app\api\model;
use think\Model;

/**
 * 角色表 模型
 */
class Servicecontent extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    protected $append=['picture_show'];
    //在数据赋值的时候自动进行转换处理
    /*protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }*/
    public function getPictureShowAttr($value,$data)
    {
        if(!empty($data['picture'])) {
            //多图地址
            $web_url=config('web_url');
            $rs= explode("##",$data['picture']);
            foreach ($rs as $key=>$val) {
                $rs[$key]=$web_url. __PUBLIC__."/uploads/images/".$val;
            }
            return $rs;
            //return __PUBLIC__."/uploads/images/".$data['picture'];
        } else {
            return '';
        }
    }
}