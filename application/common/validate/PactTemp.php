<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class PactTemp extends Validate
{
    //验证规则
    protected $rule =   [
        'options'      => ['require'],
        'picture'       => ['require'],
        'description'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'options'              => '合同项必需',
        'picture'               => '模板图必需',
        'description'            => '模板描述必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}