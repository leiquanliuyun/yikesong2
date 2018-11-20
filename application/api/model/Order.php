<?php
namespace app\api\model;
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
    public function getStatusTextAttr($value,$data)
    {
        //用户状态
        switch ($data['status']) {
            case '1' :
                return '等待输入价格';
            case '2' :
                return '等待支付';
            case '3' :
                return '已支付，处理中';
            case '4' :
                return '已完成';
            case '5' :
                return '已删除';
            case '6' :
                return '非正常';
            default :
                return '';
        }
    }
    public function getMtcTextAttr($value,$data)
    {
        //用户状态
        switch ($data['mtc']) {
            case '1' :
                return '季付';
            case '2' :
                return '半年付';
            case '3' :
                return '年付';
            default :
                return '';
        }
    }
    //在获取数据的字段值后自动进行处理
   /* public function getAuditAttr($value)
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
*/
  /*  public function getGoNumbersMoreAttr($value,$data)
    {
        //将去件单号转换为数组
        return explode('##',$data['go_numbers']);
    }

    public function getGoNoMoreAttr($value,$data)
    {
        //将去件快递公司编号转换为数组
        return explode('##',$data['go_no']);
    }*/
    //定义一对一关联方法  订单合同表
    public function pact()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Pact','id','order_id');
    }
    //定义一对一关联方法  订单表
    public function member()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('Member','uid','id');
    }



}