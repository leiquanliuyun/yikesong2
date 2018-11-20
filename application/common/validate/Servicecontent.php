<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class Servicecontent extends Validate
{
    //验证规则
    protected $rule =   [
        'service'      => ['require'],
       // 'picture'       => ['require'],
        'phone'    => ['require'],
        'price'       => ['number','require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service'              => '所属服务必需',
        //'picture'               => '服务轮播图必需',
        'phone'            => '客服电话必需',
        'price.number'               => '服务价格不为数字',
        'price.require'               => '服务价格必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}