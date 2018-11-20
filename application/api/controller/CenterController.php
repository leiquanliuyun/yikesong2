<?php
namespace app\api\controller;
use app\api\model\SmsCode;
use think\captcha\Captcha;      //验证码
use app\api\model\Member;
use app\api\model\Users;
use app\api\model\UserBind;
use app\common\lib\push\Getui;
use app\common\lib\weixin\Wechat;
use think\Cache;
use think\Exception;
use think\Loader;
use think\Log;
/**
 * 个人中心(我的)控制器
 */
class  CenterController extends CommonController
{
    //根据手机号判断是登录还是注册  以此判定验证码接口的type参数
    public function is_login(){
        //$mobile=$this->get_post('mobile');
        //$mobile=input('param.mobile');
        $mobile = data_isset($this->get_post('mobile'), 'trim', "");//halt($mobile);
        if (empty($mobile)) {
            parent::put_post(['status' => 1003, 'info' => '参数不完整1！']);
        }
        if (Member::getUserByPhone($mobile)){
            $is_login=2;
        }else{
            $is_login=1;
        }
        $data=['is_login'=>$is_login,'mobile'=>$mobile];
        parent::put_post(['status'=>1000 , 'info'=>'success' , 'data'=>$data]);
    }
    public function is_login2(){
        //$mobile=$this->get_post('mobile');
        //$mobile=input('param.mobile');
        $mobile = data_isset($this->get_post('mobile'), 'trim', "");//halt($mobile);
        if (empty($mobile)) {
            parent::put_post(['status' => 1003, 'info' => '参数不完整1！']);
        }
        if (Member::getUserByPhone($mobile)){
            $is_login=2;
        }else{
            $is_login=1;
        }
        $data=['is_login'=>$is_login,'mobile'=>$mobile];
        parent::put_post(['status'=>1000 , 'info'=>'success' , 'data'=>$data]);
    }
    public function sendSms() {
        $mobile = data_isset($this->get_post('mobile'), 'trim', "");
        $sms_type = data_isset($this->get_post('sms_type'), 'trim', "");
        //$user_type = data_isset($this->get_post('user_type'), 'trim', "");

        if (empty($mobile) || empty($sms_type)) {
            parent::put_post(['status' => 1003, 'info' => '参数不完整！']);
        }

        if ($sms_type == SmsCode::REGISTER_TYPE) {
            if (Member::getUserByPhone($mobile)) {
                parent::put_post(['status' => 1004, 'info' => '该用户已存在！']);
            }
        }

        if ($sms_type == SmsCode::LOGIN_TYPE || $sms_type == SmsCode::RESET_PASSWORD_TYPE) {
            if (empty(Member::getUserByPhone($mobile))) {
                parent::put_post(['status' => 1004, 'info' => '该手机号还未注册！']);
            }
        }
        //判断要换绑的手机号是否已经注册过

        if ($sms_type == SmsCode::CHANGE_MOBILE && !empty(Member::getUserByPhone($mobile))) {
            parent::put_post(['status' => 1004, 'info' => '该手机号已经被使用！']);
        }

        // 发送短信验证码
        $res = SmsCode::create($mobile, $sms_type);
        if ($res === true) {
            parent::put_post(['status' => 1000, 'info' => '发送成功，请查看手机！']);
        }
        parent::put_post(['status' => 1004, 'info' => $res]);
    }
    /**
     * 给当前账号登录的手机发送短信验证码 适用于修改手机号（对旧手机），绑定支付宝
     */
    public function sendSelf() {
        $sms_type = data_isset($this->get_post('sms_type'), 'intval', "");//4更换手机号，5绑定支付宝
        if (empty($sms_type)) {
            parent::put_post(['status' => 1004, 'info' => '参数错误！']);
        }
        $uid = parent::check_token();
        $user = Member::get($uid);
        if ($user->mobile) {
            $res = SmsCode::create($user->mobile, $sms_type);
            if ($res === true) {
                parent::put_post(['status' => 1000, 'info' => '发送成功，请查看手机！']);
            }
            parent::put_post(['status' => 1004, 'info' => $res]);
        }
        parent::put_post(['status' => 1004, 'info' => '您还没有绑定手机号！']);
    }
    /**
     * 注册  手机号验证码注册,验证器验证，上级推荐码处理，单点登录
     *
     * @param  mobile 用户手机号
     * @param  password 用户密码
     * @return $data ''
     */
    public function register() {
        $mobile = data_isset($this->get_post('mobile'), 'trim', "");
        $sms_code = data_isset($this->get_post('sms_code'), 'trim', "");
        //$password = data_isset($this->get_post('password'), 'trim', "");
        //$repeat_password = data_isset($this->get_post('repeat_password'), 'trim', "");
        $pid = data_isset($this->get_post('pid'), 'intval', 0);//上级用户id加密
        $registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
        if (empty($mobile)|| empty($sms_code)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        //用户表 验证器
       /* $validate = validate('Member');
        //表单提交数据
        $param = input('post.');
        $param['option']='add';
        //验证 表单数据
        if(!$validate->check($param)){
            $this->error($validate->getError());
        }*/
        //上级code
        $pid = base64_decode($pid);
        $member = new Member();
        $member->mobile = $mobile;
        $member->sms_code = $sms_code;
        $member->pid=$pid;
        $res = $member->register();
        if ($res != false) {
            //登录态token
            /*$id=$res->id;
            $randstr = randCode(12);
            $ip = get_ip();
            $attime = time();
            $token = md5($id . $attime . ($attime + 1296000) . $ip . $randstr);
            $member->save(['token'=>$token],['id'=>$id]);//echo $member->getLastSql(); halt($token);
            $data = ['token' =>$token];*/
            $data = ['token' => $this->get_token($res->id,$registration_id,1)];
            parent::put_post(['status' => 1000, 'info' => '注册成功！', 'data' => $data]);
        }
        parent::put_post(['status' => 1004, 'info' => $member->getInfo()]);
    }
    /**
     * 员工登录，手机号+密码登陆 员工不用注册，其数据来源于总管理后台添加
     */
    public function server_login() {
        $user = new Users();
        $user->mobile = data_isset($this->get_post('mobile'), 'trim', "");
        $user->password = data_isset($this->get_post('password'), 'trim', "");

        if (empty($user->mobile)|| empty($user->password)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $res = $user->login();
        if ($res instanceof Users) {
            $registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
            $data = ['token' => $this->get_token($res->id, $registration_id,2)];
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => $data]);
        }
        parent::put_post(['status' => 1004, 'info' => $user->getInfo()]);
    }
    /**
     * 用户登录，手机号+短信验证码登陆
     */
    public function login() {
        $user = new Member();
        $user->mobile = data_isset($this->get_post('mobile'), 'trim', "");
        //$user->password = data_isset($this->get_post('password'), 'trim', "");
        $user->sms_code = data_isset($this->get_post('sms_code'), 'trim', "");

        if (empty($user->mobile)|| empty($user->sms_code)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $res = $user->login();
        if ($res instanceof Member) {
            $registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
            $data = ['token' => $this->get_token($res->id, $registration_id,1)];
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => $data]);
        }
        parent::put_post(['status' => 1004, 'info' => $user->getInfo()]);
    }
    // 第三方授权登录，微信
    public function authLogin() {
        $code = data_isset($this->get_post('code'), 'trim', "");
        //$user_type = data_isset($this->get_post('user_type'), 'trim', "0");
        $login_type = data_isset($this->get_post('login_type'), 'trim', "1");
        $registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
        $device = data_isset($this->get_post('device'), 'trim', "");
        $auth = '';
        switch ($login_type) {
            case 1:
                $auth = new Wechat();
                break;
        }
        if (empty($auth)) {
            parent::put_post(['status' => 1004, 'info' => '登录类型错误！']);
        }
        // 第三方授权登录，数组里面必须有一个统一字段，叫auth_key
        $data = $auth->authLogin($code,$device);
        if ($data === false || empty($data)) {
            parent::put_post(['status' => 1004, 'info' => '登录失败，无效的授权！']);
        }
        $userBind = new UserBind();
        $userBindData = $userBind->getRsByAuthKeyAndLoginType($data['auth_key'], $login_type);
        if (empty($userBindData)) {
            // 给客户端返回授权key，进行绑定的时候使用；
            $access_token = md5($code);
            //$data['user_type'] = $user_type;
            $data['login_type'] = $login_type;
            $data['registration_id'] = $registration_id;
            cache($access_token, $data, 7200);
            parent::put_post(['status' => 1000, 'info' => 'notBind', 'data' => ['token' => $access_token,'is_bind'=>0]]);
        }
        $token = $this->get_token($userBindData->uid, $registration_id,1);
        if (!empty($token)) {
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => ['token' => $token,'is_bind'=>1]]);
        }
        parent::put_post(['status' => 1004, 'info' => '登录失败，服务异常']);
    }
    // 授权登录（第二步）绑定手机
    public function authBindMobile() {
        $access_token = data_isset($this->get_post('access_token'), 'trim', "0");//第一步返回的token
        $mobile = data_isset($this->get_post('mobile'), 'trim', "0");
        $sms_code = data_isset($this->get_post('sms_code'), 'trim', "0");
        $platform = data_isset($this->get_post('platform'), 'trim', "");
        //$password = data_isset($this->get_post('password'), 'trim', "");

        if (empty($access_token) || empty($mobile) || empty($sms_code)) {
            parent::put_post(['status' => 1004, 'info' => '参数错误！']);
        }

        if (!SmsCode::checkSmsCode($mobile, SmsCode::AUTH_LOGIN_BIND, $sms_code)) {
            parent::put_post(['status' => 1004, 'info' => '验证码错误！']);
        }
        // 取出授权的资料
        $auth_info = cache($access_token);
        if (empty($auth_info)) {
            parent::put_post(['status' => 1004, 'info' => '授权失效！']);
        }

        $user = new Member();
        $user->startTrans();
        try{
            $res = '';
            $res = $user->getUserByPhone($mobile);
            if ($res instanceof Member) {
                // 保存用户资料,直接存member表,如果是默认的昵称与头像，此处修改为微信头像与昵称
                if (strpos($res->nickname,'yks')!==false){
                    $res->nickname = $auth_info['nickname'];
                }
                if (strpos($res->picture,'mine')!==false){
                    $res->picture = $auth_info['headimgurl'];
                }
                //empty($res->nickname) && $res->nickname = $auth_info['nickname'];
                //empty($res->picture) && $res->picture = $auth_info['headimgurl'];
                empty($res->sex) && $res->sex = $auth_info['sex'];
                $res->isUpdate(true)->save();
            } else {
                // 新用户,此处可以传上级id吗 新增一个用户,相当于注册
                $res = $user;//将初始对象赋值给$res
                $res->mobile = $mobile;
                //$res->salt = $res->makeSalt();
                //$res->password = $res->makePassword($password);
                $res->code = uniqid();
                $res->nickname=$auth_info['nickname'];
                $res->picture=$auth_info['headimgurl'];
                $res->sex=$auth_info['sex'];
                $res->save();
            }

            // 添加授权表的记录
            $userBind = new UserBind();
            $userBind->openid = $auth_info['openid'];
            $userBind->uid = $res->id;
            $userBind->auth_key = $auth_info['auth_key'];
            $userBind->type = $auth_info['login_type'];
            //$userBind->user_type = $auth_info['user_type'];
            $userBind->save();

            $user->commit();
            // 删除缓存数据，
            SmsCode::deleteSmsCode($mobile, SmsCode::AUTH_LOGIN_BIND);
            cache($access_token, null);
            $token = $this->get_token($res->id, $auth_info['registration_id'],1);
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => ['token' => $token]]);
        } catch (Exception $e) {
            $user->rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
    }

    /**
     * 获取用户信息
     *
     * @param  $token 'token'
     * @return $data ''
     */
    public function user_info()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //用户表 模型
        $user = model('User');
        //查询用户表 数据
        $response = $user->get(['uid'=>$user_info['uid']],'client');

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }




}