<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class News extends Validate
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
        'title'              => '资讯标题必需',
        'sort'               => '排序数值不为数字',
        'picture'            => '资讯图片必需',
        'status'               => '是否显示不为数字',
        '__token__'			 => '表单令牌规则错误'
    ];

}