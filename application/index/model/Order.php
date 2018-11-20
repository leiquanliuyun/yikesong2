<?php
namespace app\index\model;
use think\Model;

/**
 * 期权持仓订单表 模型
 */
class Order extends Model
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
    public function getAuditAttr($value)
    {
        //订单审核状态
        switch ($value) {
            case '1' :
                return '成功';
            case '2' :
                return '失败';
            case '3' :
                return '待审核';
            default :
                return '';
        }
    }

    public function getStatusAttr($value)
    {
        //订单状态
        switch ($value) {
            case '1' :
                return '未结算';
            case '2' :
                return '已结算';
            default :
                return '';
        }
    }

    public function getGoNumbersMoreAttr($value,$data)
    {
        //将去件单号转换为数组
        return explode('##',$data['go_numbers']);
    }

    public function getGoNoMoreAttr($value,$data)
    {
        //将去件快递公司编号转换为数组
        return explode('##',$data['go_no']);
    }

    //定义关联方法  用户表  持有人
    public function possessor()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('user','possessor_uid','uid')->field('uid,group_id,mobile');
    }
    //定义关联方法  用户表  对接人
    public function superior()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('user','superior_uid','uid')->field('uid,group_id,mobile');
    }
    //定义关联方法  股票数据表
    public function stock()
    {
        //belongsTo('关联模型名','外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('stock','code','symbol')->field('symbol,trade');
    }


}