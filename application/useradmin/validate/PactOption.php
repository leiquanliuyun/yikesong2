<?php
namespace app\useradmin\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class PactOption extends Validate
{
    //验证规则
    protected $rule =   [
        'name'      => ['require'],
        // 'picture'       => ['require'],
        'fieldname'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'fieldname'              => '合同项字段名必需',
        //'picture'               => '服务轮播图必需',
        'name'            => '合同项名称必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}