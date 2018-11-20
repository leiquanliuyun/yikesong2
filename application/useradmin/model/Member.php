<?php
namespace app\useradmin\model;
use think\Model;

/**
 * 会员表 模型
 */
class Member extends Common
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    //protected $auto = [];
    protected $insert = ['create_time'];
    protected $update = ['update_time'];

    //在数据赋值的时候自动进行转换处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }
    //在获取数据的字段值后自动进行处理
    public function getPictureShowAttr($value,$data)
    {
        //图片地址
        return __PUBLIC__."/uploads/images/".$data['picture'];
    }
    //在获取数据的字段值后自动进行处理
    public function getIdPhotoShowAttr($value,$data)
    {
        //图片地址
        if(!empty($data['id_photo'])) {
            //多图地址
            $rs= explode("##",$data['id_photo']);
            foreach ($rs as $key=>$val) {
                $rs[$key]= __PUBLIC__."/uploads/images/".$val;
            }
            return $rs;
            //return __PUBLIC__."/uploads/images/".$data['picture'];
        } else {
            return '';
        }
    }

    //在获取数据的字段值后自动进行处理
    public function getSexAttr($value)
    {
        //用户角色
        switch ($value) {
            case 1 :
                return '男';
            case 2 :
                return '女';
            default :
                return '男';
        }
    }

    //定义一对一关联方法  用户需求表
    public function demand()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Demand','id','uid');
    }
    //定义一对一关联方法  订单合同表
    public function pact()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Pact','id','uid');
    }
    //定义一对一关联方法  订单表
    public function order()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Order','id','uid');
    }
    //定义一对一关联方法  积分提现订单表
    public function withdraworder()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('Withdraworder','id','uid');
    }
    /**
     * 获取用户,存在返回用对象实例
     * @param $mobile string 手机号
     * @return User 对象实例
     */
    public static function getUserByPhone( $mobile ){
        return self::where('mobile' , $mobile)
            ->find();
    }
    /**
     * 密码加密方式
     */
    public function makePassword( $password , $salt = ''){
        $salt = $salt ? $salt : $this->makeSalt();
        return md5($password.$salt);
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
     * 用户注册
     * 部署到正式环境请将参数改为true
     */
    public function register($check_sms = true){
        // 判断短信验证码
        if ($check_sms) {
            if (!SmsCode::checkSmsCode($this->mobile, SmsCode::REGISTER_TYPE, $this->sms_code)) {
                //halt($check_sms);
                $this->setError('验证码错误！');
                return false;
            }
        }
        //$this->salt = $this->makeSalt();
        // 加密密码
        //$this->password = $this->makePassword( $this->password );
        $data = [
            'mobile' => $this->mobile,
            //'password' => $this->password,
            //'salt' => $this->salt,
            'nickname'=>'yks_'.$this->mobile,
            'picture'=>config('web_url').'/static/images/logo.jpg',
            'code'=>uniqid()
        ];
        //上级code
        if ($this->pid){
            $parent=$this->where('id',$this->pid)->find();
            if($parent){
                $data['pcode'] = $parent['code'];
            }
        }
        // 删掉验证码
        //SmsCode::deleteSmsCode( $this->mobile , SmsCode::REGISTER_TYPE );
        return $this->allowField(true)->save( $data ) ? $this : false;
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
            //SmsCode::deleteSmsCode( $this->mobile , SmsCode::LOGIN_TYPE );
        }
        // 密码登陆验证
        if( !empty($this->password) ){
            // 加密传过来的密码
            $password  = $this->makePassword( $this->password , $data->salt );
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
    /**
     * 获取实名
     */
    public function getRealAuth(){
        if( empty($this->id) ){
            return false;
        }
        return self::where('id' , $this->id)->field('card_num,realname,sex,id_photo')
            ->find();
        //$auth = db('Member')->where('id' , $this->id)->field('card_num,realname,sex,id_photo')->find();
    }
}