<?php
namespace app\useradmin\validate;
use think\Validate;

/**
 * 幻灯片表 验证器
 */
class Slide extends Validate
{
    //验证规则
    protected $rule =   [
        'title'      => ['require'],
        'sort'       => ['number'],
        'picture'    => ['require'],
        'status'       => ['number'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'title'              => '幻灯片标题必需',
        'sort'               => '排序数值不为数字',
        'picture'            => '幻灯片图片必需',
        'status'               => '是否显示不为数字',
        '__token__'			 => '表单令牌规则错误'
    ];

}