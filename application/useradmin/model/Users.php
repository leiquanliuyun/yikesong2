<?php
namespace app\useradmin\model;
use think\Model;

/**
 * 用户表 模型
 */
class Users extends Common
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];

    //在数据赋值的时候自动进行转换处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }

    //在获取数据的字段值后自动进行处理
    public function getUserTypeTextAttr($value,$data)
    {
        //用户角色
        switch ($data['user_type']) {
            case 1 :
                return '超级管理员';
            case 2 :
                return '员工';
            case 3 :
                return '会员';
            default :
                return '';
        }
    }

    public function getStatusTextAttr($value,$data)
    {
        //用户状态
        switch ($data['status']) {
            case '0' :
                return '禁用';
            case '1' :
                return '启用';
            case '2' :
                return '未验证';
            default :
                return '';
        }
    }
    /**
     * 获取用户,存在返回用对象实例
     * @param $mobile string 手机号
     * @return User 对象实例
     */
    public static function getUserByPhone( $mobile ){
        return self::where(['mobile'=>$mobile,'user_type'=>2])
            ->find();
    }
    /**
     * 密码加密方式
     */
    public function makePassword( $password , $salt = ''){
        $salt = $salt ? $salt : $this->makeSalt();
        return md5(trim($password.$salt));
    }

    /**
     * 生成盐值，6位随机字符
     */
    public function makeSalt(){
        static $salt;
        if( empty($salt) ){
            // 不存在将生成
            $salt = randCode(6);
        }
        return $salt;
    }
    /**
     * 用户登陆(普通登录)
     */
    public function login(){

        $data = $this->getUserByPhone($this->mobile);
        if( empty($data) ){
            $this->setError('该手机号码还未注册！');
            return false;
        }
        if( $data->status != 1 ){
            $this->setError('账号被禁止，登录失败！');
            return false;
        }
        // 短信验证码登陆
        if( !empty($this->sms_code) ){
            if( !SmsCode::checkSmsCode( $this->mobile , SmsCode::LOGIN_TYPE , $this->sms_code ) ){
                $this->setError('短信验证码错误！');
                return false;
            }
            // 删除验证码
            SmsCode::deleteSmsCode( $this->mobile , SmsCode::LOGIN_TYPE );
        }
        // 密码登陆验证
        if( !empty($this->password) ){  //halt($data->salt);
            // 加密传过来的密码
            $password  = $this->makePassword( $this->password, $data->salt );
            // 和盐值加密后的密码 校验 数据库里面的加密结果
            if( $password != $data->password ){
                $this->setError('密码输入错误！');
                return false;
            }
        }
        // 更新登陆信息
        $data->last_login_ip = get_ip();
        $data->last_login_time =date('Y-m-d H:i:s',time());
        $data->save();
        return $data;
    }

}