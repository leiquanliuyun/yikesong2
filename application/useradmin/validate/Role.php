<?php
namespace app\useradmin\validate;
use think\Validate;

/**
 * 规则用户组表 验证器
 */
class Role extends Validate
{
    //验证规则
    protected $rule =   [
        'name'      => ['require'],
        'status'     => ['number'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'name.require'      => '角色名称必需',
        'status'             => '角色状态不为数字',
        '__token__'			    => '表单令牌规则错误'
    ];

}