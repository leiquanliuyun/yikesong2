<?php
namespace app\index\model;
use think\Model;

/**
 * 留言表 模型
 */
class LeaveWord extends Model
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
    public function getPictureShowAttr($value,$data)
    {
        if(!empty($data['picture'])) {
            //多图地址
            $more_picture = explode("##",$data['picture']);
            foreach($more_picture as $k=>$v) {
                $more_picture[$k] = __PUBLIC__."/uploads/images/".$v;
            }
            return $more_picture;
        } else {
            return '';
        }
    }

    //定义关联方法  用户表
    public function user()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('user','uid','uid')->field('uid,group_id');
    }

}