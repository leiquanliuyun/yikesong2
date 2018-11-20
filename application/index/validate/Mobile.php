<?php
namespace app\index\validate;
use think\Validate;

/**
 * 手机号码 验证器
 */
class Mobile extends Validate
{
    //验证规则
    protected $rule =   [
        'mobile'         => ['regex'=>'/^1[34578]\d{9}$/','require'],
        'sign'           => ['checkSign']
    ];

    //错误提示
    protected $message  =   [
        'mobile.require'      => '手机号码必需',
        'mobile.regex'        => '手机号码格式错误'
    ];

    // 自定义验证规则
    protected function checkSign($value,$rule,$data)
    {
        //md5签名验证 key = Api123
        $sign = md5($data['mobile']."Api123");
        if($data['sign'] <> $sign){
            return '非法操作';
        } else {
            return true;
        }
    }

}