<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class Housecontent extends Validate
{
    //验证规则
    protected $rule =   [
        //'housetype'      => ['require'],
        //'picture'       => ['require'],
        'rental_method'    => ['require'],
        'price'       => ['number','require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        //'housetype'              => '房屋类型必需',
        //'picture'               => '房屋图片必需',
        'rental_method'            => '租售方式必需',
        'price.number'               => '房屋价格不为数字',
        'price.require'               => '房屋价格必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}