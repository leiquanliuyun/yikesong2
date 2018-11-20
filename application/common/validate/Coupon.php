<?php
namespace app\common\validate;
use think\Validate;

/**
 * 优惠券表 验证器
 */
class Coupon extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        'name'       => ['require','check_name'],
        'price'    => ['require','number'],
        'fullprice'    => ['require','number'],
        'num'    => ['require','number'],
        'useday'    => ['require','number'],
        'status'       => ['number'],
        //'picture'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '所属服务必需',
        'name'               => '优惠券名称必需',
        'price.require'            => '优惠券可抵用价格必需',
        'fullprice.require'            => '优惠券满减金额必需',
        'num.require'            => '优惠券数量必需',
        'useday.require'            => '优惠券使用期限必需',
        'price.number'            => '优惠券可抵用价格不为数字',
        'fullprice.number'            => '优惠券满减金额不为数字',
        'num.number'            => '优惠券数量不为数字',
        'useday.number'            => '优惠券使用期限不为数字',
        'status'               => '是否显示不为数字',
        //'picture'            => '优惠券图片必需',
        '__token__'			 => '表单令牌规则错误'
    ];
    protected function check_name($value,$rule,$data)
    {


        if(empty($data['id']) && $data['option'] == 'add') {
            //判断是否重名
            $coupon=model('Coupon');
            $list=$coupon->where(['name'=>$data['name'],'service_name'=>$data['service_name']])->find();

            return empty($list) ? true : '该服务下优惠券名称已存在，请勿重复添加';
        }
        return true;
    }
}