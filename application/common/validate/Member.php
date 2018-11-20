<?php
namespace app\common\validate;
use think\Validate;

/**
 * 用户表 验证器
 */
class Member extends Validate
{

    /**
     * 验证规则
     */
    protected $rule =   [
        //'username'       => ['checkUsername','min:2','max:20'],
        'password'       => ['min:2','max:20','checkPassword'],
        'email'      =>['email'],
        'mobile'         => ['regex'=>'/^1[34578]\d{9}$/','checkMobile']
        //'captcha'        => ['captcha'],
        //'__token__' 	  => ['require','token']
    ];

    /**
     * 错误提示
     */
    protected $message  =   [
        //'username.min'          => '账号不能短于2个字符',
        'password.min'          => '密码不能短于2个字符',
        //'username.max'          => '账号不能长于20个字符',
        'password.max'          => '密码不能长于20个字符',
        'mobile.regex'          => '手机号码格式错误',
        'email.email'          => '邮箱格式错误'
       // 'captcha.captcha'       => '验证码错误',
       // '__token__'			    => '表单令牌规则错误'
    ];

    // 自定义验证规则
    protected function checkPassword($value,$rule,$data)
    {
        //判断是否是新添加用户数据
        if($data['option'] == 'add') {
            if (empty($data['password']) || empty($data['repeat_password'])){
                return '密码为空';
            }
            if ($data['password']!=$data['repeat_password']){
                return '两次密码输入不一致';
            }
            return  true;
        }
        return true;
    }

    protected function checkUsername($value,$rule,$data)
    {
        //判断是否新添加账号
        if(empty($data['id']) && $data['option'] == 'add') {
            if(empty($data['username'])) {
                return '账号为空';
            }
            //用户表 模型
            $user = model('Member');
            //查询管理员用户数据
            $list = $user->get(['username' => $data['username']]);
            return empty($list) && !empty($data['username']) ? true : '账号为空或者账号已存在，请重新设置账号';
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
            $user = model('Member');
            //查询管理员用户数据
            $list = $user->get(['mobile' => $data['mobile']]);
            return empty($list) && !empty($data['mobile']) ? true : '手机号已注册或者为空，请重新设置手机号';
        }
        return true;
    }

}
