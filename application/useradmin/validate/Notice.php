<?php
namespace app\useradmin\validate;
use think\Validate;

/**
 * 公告表 验证器
 */
class Notice extends Validate
{
    //验证规则
    protected $rule =   [
        'content'      => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'content'              => '公告内容必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}