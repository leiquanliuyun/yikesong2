<?php
namespace app\index\validate;
use think\Validate;

/**
 * 留言表 验证器
 */
class LeaveWord extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        'uid'           => ['number','require'],
        'type'          => ['require'],
        'mobile'        => ['regex'=>'/^1[34578]\d{9}$/','require'],
        'content'       => ['require'],
        'picture'       => ['require']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        'uid.require'             => '用户uid必需',
        'uid.number'              =>  '用户uid必需为数字',
        'type.require'            =>  '意见类型必需',
        'mobile.require'          => '手机号码必需',
        'mobile.regex'            => '手机号码格式错误',
        'content.require'        =>  '反馈内容必需',
        'picture.require'        =>  '照片说明必需',
    ];

}