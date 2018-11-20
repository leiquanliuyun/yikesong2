<?php
namespace app\useradmin\validate;
use think\Validate;

/**
 * 数据接口表 验证器
 */
class PortData extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        'uid'           => ['number'],
        'useradmin'     => ['require'],
        'password'      => ['require'],
        'company'       => ['require'],
        'hits'          => ['number'],
        'float'         => ['number'],
        'mobile'        => ['regex'=>'/^1[34578]\d{9}$/'],
        'status'        => ['number'],
        'end_time'      => ['number'],
        '__token__'     => ['require','token']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        'uid'           => '用户表id不为数字',
        'useradmin'     => '接口账号必需',
        'password'      => '接口密码必需',
        'company'       => '公司名称必需',
        'hits'          => '调用次数不为数字',
        'float'         => '浮动点数不为数字',
        'mobile'        => '手机号码格式错误',
        'status'        => '状态不为数字',
        'end_time'      => '结束时间不为数字',
        '__token__'	    => '表单令牌规则错误'
    ];


}