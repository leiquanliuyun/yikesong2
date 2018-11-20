<?php
namespace app\common\validate;
use think\Validate;

/**
 * 服务积分表 验证器
 */
class Integral extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        // 'picture'       => ['require'],
        'integral'    => ['number','require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '所属服务必需',
        'integral.number'            => '积分比例不为数字',
        'integral.require'            => '积分比例必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}