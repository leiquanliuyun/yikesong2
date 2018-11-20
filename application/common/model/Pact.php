<?php
namespace app\common\model;
use think\Model;

/**
 * 合同表 模型
 */
class Pact extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];
    //在数据赋值的时候自动进行转换处理
   /* protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }*/

    public function getStatusTextAttr($value,$data)
    {
        //用户状态
        switch ($data['status']) {
            case '1' :
                return '等待雇主签订';
            case '2' :
                return '已签订';
            case '3' :
                return '已删除';
            default :
                return '';
        }
    }
    //定义一对一关联方法  用户表
    public function member()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Member','uid','id');
    }
    //定义一对一关联方法  订单表
    public function order()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Order','order_id','id');
    }
}