<?php
namespace app\common\validate;
use think\Validate;

/**
 * 用户表 验证器
 */
class Users extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        'user_login'       => ['checkUsername','min:5'],
        'password'       => ['min:6','checkPassword'],
        'user_email'      =>['email'],
        'user_type'       => ['number'],
        'status'         => ['number'],
        'mobile'         => ['regex'=>'/^1[34578]\d{9}$/','checkMobile'],
        'captcha'        => ['captcha'],
        '__token__' 	  => ['require','token']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        'user_login.min'          => '账号不能短于5个字符',
        'password.min'          => '密码不能短于6个字符',
        'user_type'              => '账号角色类型不为数字',
        'status'                => '用户状态不为数字',
        'mobile.regex'          => '手机号码格式错误',
        'user_email.email'          => '邮箱格式错误',
        'captcha.captcha'       => '验证码错误',
        '__token__'			    => '表单令牌规则错误'
    ];

    // 自定义验证规则
    protected function checkPassword($value,$rule,$data)
    {
        //判断是否是新添加用户数据
        if(empty($data['id']) && $data['option'] == 'add') {

            return  !empty($data['password']) ? true : '密码为空';
        }
        return true;
    }

    protected function checkUsername($value,$rule,$data)
    {
		//判断是否新添加账号
        if(empty($data['id']) && $data['option'] == 'add') {
            if(empty($data['user_login'])) {
                return '账号为空';
            }
            //用户表 模型
            $user = model('Users');
            //查询管理员用户数据
            $list = $user->get(['user_login' => $data['user_login']]);
            return empty($list) && !empty($data['user_login']) ? true : '账号为空或者账号已存在，请重新设置账号';
        }
        return true;
    }

    protected function checkMobile($value,$rule,$data)
    {
        //判断是否新添加账号
        if(empty($data['id']) && $data['option'] == 'add') {
            if(empty($data['mobile'])) {
                return '手机号为空';
            }
            //用户表 模型
            $user = model('Users');
            //查询管理员用户数据
            $list = $user->get(['mobile' => $data['mobile']]);
            return empty($list) && !empty($data['mobile']) ? true : '手机号已注册或者为空，请重新设置手机号';
        }
        return true;
    }

}
