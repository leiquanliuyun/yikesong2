<?php
namespace app\common\validate;
use think\Validate;

/**
 * 客服表 验证器
 */
class Phone extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        'phone'       => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '客服所属服务必需',
        'phone'               => '客服电话必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}