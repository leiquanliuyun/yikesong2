<?php
namespace app\common\validate;
use think\Validate;

/**
 * 服务选项表 验证器
 */
class Csituation extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        'name'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '所属服务必需',
        'name'            => '选项名称必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}