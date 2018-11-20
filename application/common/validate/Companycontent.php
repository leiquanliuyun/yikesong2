<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class Companycontent extends Validate
{
    //验证规则
    protected $rule =   [
        'regmoney'      => ['require'],
        //'picture'       => ['require'],
        'solid_assets'    => ['require'],
        'price'       => ['number','require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'regmoney'              => '注册资金必需',
        'picture'               => '图片必需',
        'solid_assets'            => '名下固产必需',
        'price.number'               => '价格不为数字',
        'price.require'               => '价格必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}