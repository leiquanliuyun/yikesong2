<?php
namespace app\index\validate;
use think\Validate;

/**
 * 我的地址表 验证器
 */
class Address extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        'uid'           => ['number','require'],
        'name'          => ['require'],
        'mobile'        => ['regex'=>'/^1[34578]\d{9}$/','require'],
        'province'      => ['require'],
        'city'          => ['require'],
        'county'        => ['require'],
        'address'       => ['require'],
        'status'        => ['number']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        'uid.require'             => '用户uid必需',
        'uid.number'              =>  '用户uid必需为数字',
        'name.require'            =>  '姓名必需',
        'mobile.require'          => '手机号码必需',
        'mobile.regex'            => '手机号码格式错误',
        'province.require'        =>  '省份必需',
        'city.require'            =>  '城市必需',
        'county.require'          =>  '区(县)必需',
        'address.require'         =>  '详细地址必需',
        'status.number'           =>  '状态必须为数字'
    ];

}