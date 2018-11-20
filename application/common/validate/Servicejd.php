<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class Servicejd extends Validate
{
    //验证规则
    protected $rule =   [
        'service_name'      => ['require'],
        // 'picture'       => ['require'],
        'name'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        'service_name'              => '所属服务必需',
        //'picture'               => '服务轮播图必需',
        'name'            => '所有进度名必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}