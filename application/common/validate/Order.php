<?php
namespace app\common\validate;
use think\Validate;

/**
 * 期权持仓订单表 验证器
 */
class Order extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        'price'     => ['number','require'],
        'mtc'     => ['number','require'],
        '__token__'         => ['require','token']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        'price.require'         => '订单价格必需',
        'mtc.price'          => '订单支付方式必需',
        '__token__'			    => '表单令牌规则错误'
    ];


}