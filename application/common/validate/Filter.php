<?php
namespace app\common\validate;
use think\Validate;

/**
 * 客服表 验证器
 */
class Filter extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        'condition'       => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '所属服务必需',
        'condition'               => '筛选条件必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}