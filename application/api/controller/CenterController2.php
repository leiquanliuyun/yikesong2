<?php
namespace app\api\controller;
use app\common\model\SmsCode;
use think\captcha\Captcha;      //验证码
use app\common\model\Member;
use app\common\model\UserBind;
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
    //根据手机号判断是登录还是注册
    public function is_login(){
        $mobile = data_isset($this->get_post('mobile'), 'trim', "");
        if (empty($mobile)) {
            parent::put_post(['status' => 1003, 'info' => '参数不完整！']);
        }
        if (Member::getUserByPhone($mobile)){
            $is_login=2;
        }else{
            $is_login=1;
        }
        parent::put_post(['status'=>1000 , 'info'=>'success' , 'data'=>$is_login]);
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
        if (empty($mobile)|| empty($sms_code) || empty($password) || empty($repeat_password)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        //用户表 验证器
        $validate = validate('Member');
        //表单提交数据
        $param = input('post.');
        $param['option']='add';
        //验证 表单数据
        if(!$validate->check($param)){
            $this->error($validate->getError());
        }
        //上级code
        $member = new Member();
        $member->mobile = $mobile;
        $member->sms_code = $sms_code;
        $member->password = $password;
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
            $data = ['token' => $this->get_token($res->id,$registration_id)];
            //return json(['status' => 1000, 'info' => '注册成功！', 'data' => $data]);
            parent::put_post(['status' => 1000, 'info' => '注册成功！', 'data' => $data]);
        }
        parent::put_post(['status' => 1004, 'info' => $member->getInfo()]);
    }
    /**
     * 用户登录，使用短信或者密码登陆
     */
    public function login() {
        $user = new Member();
        $user->mobile = data_isset($this->get_post('mobile'), 'trim', "");
        $user->password = data_isset($this->get_post('password'), 'trim', "");
        $user->sms_code = data_isset($this->get_post('sms_code'), 'trim', "");

        // !empty($user->password) && !empty($user->sms_code) ||
        if (empty($user->password) && empty($user->sms_code)) {
            // 短信或密码，只能用一个来登录
            parent::put_post(['status' => 1003, 'info' => '密码为必须']);
        }

        $res = $user->login();
        if ($res instanceof Member) {
            $registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
            $data = ['token' => $this->get_token($res->id, $registration_id)];
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
        $data = $auth->authLogin($code);
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
            parent::put_post(['status' => 1000, 'info' => 'notBind', 'data' => ['token' => $access_token]]);
        }
        $token = $this->get_token($userBindData->uid, $registration_id);
        if (!empty($token)) {
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => ['token' => $token]]);
        }
        parent::put_post(['status' => 1004, 'info' => '登录失败，服务异常']);
    }
    // 授权登录（第二步）绑定手机
    public function authBindMobile() {
        $access_token = data_isset($this->get_post('access_token'), 'trim', "0");
        $mobile = data_isset($this->get_post('mobile'), 'trim', "0");
        $sms_code = data_isset($this->get_post('sms_code'), 'trim', "0");
        $platform = data_isset($this->get_post('platform'), 'trim', "");
        $password = data_isset($this->get_post('password'), 'trim', "");

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
        try {
            $res = '';
            $res = $user->getUserByPhone($mobile);
            if ($res instanceof Member) {
                // 用户存在，修改新密码
                $res->password = $res->makePassword($password, $res->salt);
            } else {
                // 新用户,此处可以传上级id吗
                $res = $user;//将初始对象赋值给$res
                $res->mobile = $mobile;
                $res->salt = $res->makeSalt();
                $res->password = $res->makePassword($password);
                $res->code = uniqid();
                $res->platform = $platform;
            }
            // 新增一个用户或修改用户密码
            $res->save();

            // 添加授权表的记录
            $userBind = new UserBind();
            $userBind->openid = $auth_info['openid'];
            $userBind->uid = $res->id;
            $userBind->auth_key = $auth_info['auth_key'];
            $userBind->type = $auth_info['login_type'];
            $userBind->user_type = $auth_info['user_type'];
            $userBind->save();

            // 保存用户资料,直接存member表
            /*$userInfo = new UserInfo();
            $info = $userInfo->where('user_id', $res->id)->find();*/
            empty($res->nickname) && $res->nickname = $auth_info['nickname'];
            empty($res->picture) && $res->picture = $auth_info['headimgurl'];
            empty($res->sex) && $res->sex = $auth_info['sex'];
            $res->isUpdate(true)->save();

            $user->commit();
            // 删除缓存数据，
            SmsCode::deleteSmsCode($mobile, SmsCode::AUTH_LOGIN_BIND);
            cache($access_token, null);
            $token = $this->get_token($res->id, $auth_info['registration_id']);
            parent::put_post(['status' => 1000, 'info' => '登录成功！', 'data' => ['token' => $token]]);
        } catch (Exception $e) {
            $user->rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
    }
    /*修改手机号*/
    /*用户密码登录*/
    public function login2()
    {
        //获取提交数据
        $param = input('post.');
        //echo '<pre>'; var_dump($param);exit;
        //验证数据
        if(empty($param['code'])) {
            abort(501,'用户登录凭证必需');
        }
        if(!empty($param['superior_uid']) && !is_numeric($param['superior_uid'])) {
            abort(502,'上级用户uid格式错误');
        } else {
            $param_user['belong_id'] = empty($param['superior_uid']) ? 0 : $param['superior_uid'];
        }

        //配置请求接口参数
        $config = array(
            'appid'   => 'wx71a4af77c1e33c49', //小程序唯一标识
            'secret'    => 'd84b5f9eaaa722b7786f8967bbcec43d', //小程序的 app secret
            'js_code'    => $param['code'], //用户登录凭证
            'grant_type' =>'authorization_code' //填写为 authorization_code
        );
        //code 换取 session_key接口地址
        $wx_data = juhecurl('https://api.weixin.qq.com/sns/jscode2session',$config,1);
        //echo '<pre>';echo 11; var_dump($wx_data);exit;
        //json装换为数组
        $wx_data = json_decode($wx_data, true);
        //判断是否请求成功
        if(empty($wx_data['openid'])) {
            abort($wx_data['errcode'],$wx_data['errmsg']);
        }
        //判断是否有unionid数据
        if(empty($wx_data['unionid'])) {
            if(empty($param['encryptedData'])) {
                abort(501,'用户登录凭证必需');
            }
            if(empty($param['iv'])) {
                abort(501,'加密算法的初始向量必需');
            }
            //微信小程序获取用户敏感数据 encryptedData 解密
            $encrypted_data = encrypted_data($param['encryptedData'],$param['iv'],$wx_data['session_key']);
            //var_dump($encrypted_data);
            $encrypted_data = json_decode($encrypted_data,true); //echo '<br/>'; var_dump($encrypted_data);exit;
            //获取union_id
            $wx_data['unionid'] = $encrypted_data['unionId'];
        }
        //用户表 模型
        $user = model('User');
        //where条件
        $where['union_id'] = trim($wx_data['unionid']);
        //查询用户表数据
        $data_user = $user->where($where)->find();
        //判断是否有账号
        if(empty($data_user)) {
            //封装用户表参数
            $param_user['identification'] = randCode(2,2).randCode(6,1);         //客户id标识
            $param_user['salt'] = randCode(8);                                   //加密串
            $param_user['username'] = trim($wx_data['unionid']);                //账号
            $param_user['password'] = md5('123456'.$param_user['salt']);        //密码
            $param_user['group_id'] = 4;                                         //角色：普通用户
            $param_user['token'] = md5($wx_data['openid'].time());                //token
            $param_user['openid'] = $wx_data['openid'];                           //微信openid
            $param_user['session_key'] = $wx_data['session_key'];                 //微信会话密钥
            $param_user['union_id'] = $wx_data['unionid'];                 //微信开放平台union_id
            $param_user['last_login_time'] = date("Y-m-d H:i:s");               //上次登录时间
            //添加,用户表数据
            $result_user = $user->save($param_user);
            //添加关联表数据,过滤非数据表字段的数据
            if($result_user) {
                //代理商/顾客信息表 模型
                $info_client = model('InfoClient');
                //封装顾客信息表参数
                $param_client['uid'] = $user->uid;              //关联表外键uid
                //添加,顾客信息表数据
                $info_client->save($param_client);

                //返回数据
                $response['token'] = $param_user['token'];
                $response['identification'] = $param_user['identification'];    //客户ID

            } else {
                abort(611,'注册失败');
            }

        } else {
            //判断用户状态
            if($data_user['status'] != '启用') {
                abort(501,'该用户状态未启用,请联系管理员!');
            }
            //封装数据
            $data['last_login_time'] = date("Y-m-d H:i:s");
            $data['token'] = md5($data_user['uid'].time());
            //更新账号数据
            $user->save($data,['uid'=>$data_user['uid']]);
            //返回数据
            $response['token'] = $data['token'];
            $response['identification'] = $data_user['identification'];     //客户ID

        }
        return json(array('status'=>'200','message'=>'success','data'=>$response));

    }



    /**
     * 保存用户头像
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  avatar 'https:wx.save_avatar'  头像地址
     * @param  nickname 'test'  微信昵称
     * @return $data
     */
    public function save_avatar()
    {

        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['avatar'])) {
            abort(501,'头像地址必需');
        }
        if(empty($param['nickname'])) {
            abort(501,'微信昵称必需');
        }
        //代理商/顾客信息表 模型
        $info_client = model('InfoClient');
        //封装数据
        $param_client['avatar'] = $param['avatar'];
        $param_client['nickname'] = $param['nickname'];
        //更新顾客信息表数据
        $response = $info_client->save($param_client,['uid'=>$user_info['uid']]);

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
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



    /**
     * 个人信息修改
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  name '测试'  姓名
     * @param  email '123@163.com'  邮箱
     * @param  mobile 15000000000  手机号码
     * @param  code 123456  手机验证码
     * @param  identification_code  '123sa'  识别码非必需
     * @return $data
     */
    public function datum_update()
    {

        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['name'])) {
            abort(600,'姓名不能为空');
        }
        if(empty($param['mobile'])) {
            abort(600,'手机号码不能为空');
        }
        if(empty($param['code'])) {
            abort(600,'手机验证码不能为空');
        }
        //手机验证码表 模型
        $sms_code = model('SmsCode');
        //查询数据
        $list_mobile = $sms_code->get(['mobile'=>$param['mobile']]);
        //验证数据
        if(empty($list_mobile)) {
            abort(612,'该手机号未发送验证码');
        }
        if($list_mobile['code'] != $param['code'] || time() > ($list_mobile['create_time'] + 600)) {
            abort(613,'短信验证码错误');
        }
        //判断识别码是否为空
        if(!empty($param['identification_code'])) {
            //期权持仓订单表
            $order = model('Order');
            //查询订单数据
            $data_order = $order->field('order_id,possessor_uid')->where('identification_code',$param['identification_code'])->select();
            //更新订单持有人uid
            foreach($data_order as $key=>$value) {
                //判断该订单是否存在持有人
                if(empty($value['possessor_uid'])) {
                    $order->save(['possessor_uid'=>$user_info['uid']],['order_id' =>$value['order_id']]);
                }
            }
        }

        //代理商/顾客信息表 模型
        $info_client = model('InfoClient');
        //用户表 模型
        $user = model('User');

        //更新顾客信息表数据
        $response_user = $user->allowField(true)->save($param,['uid' =>$user_info['uid']]);
        $response_client = $info_client->allowField(true)->save($param,['uid' =>$user_info['uid']]);
        if(!$response_client) {
            abort(503,'修改个人信息失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response_client));
    }



    /**
     * 经纪注册
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  type '个人'  类型
     * @param  mobile 15000000000  手机号码
     * @param  code 123456  手机验证码
     * @return $data
     */
    public function broker_register()
    {

        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['type'])) {
            abort(600,'类型不能为空');
        }
        if(empty($param['mobile'])) {
            abort(600,'手机号码不能为空');
        }
        if(empty($param['code'])) {
            abort(600,'手机验证码不能为空');
        }
        //手机验证码表 模型
        $sms_code = model('SmsCode');
        //查询数据
        $list_mobile = $sms_code->get(['mobile'=>$param['mobile']]);
        //验证数据
        if(empty($list_mobile)) {
            abort(612,'该手机号未发送验证码');
        }
        if($list_mobile['code'] != $param['code'] || time() > ($list_mobile['create_time'] + 600)) {
            abort(613,'短信验证码错误');
        }

        //经纪注册表 模型
        $broker_register = model('BrokerRegister');
        //封装数据
        $param_register['uid'] = $user_info['uid'];
        $param_register['type'] = $param['type'];
        $param_register['mobile'] = $param['mobile'];
        //添加经纪注册表数据
        $response_user = $broker_register->save($param_register);
        //判断是否成功
        if(!$response_user) {
            abort(503,'添加经纪注册数据失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response_user));
    }




    /**
     * 获取帮助中心数据
     *
     * @return $data ''
     */
    public function help_center()
    {

        //帮助中心表 模型
        $center = model('Center');
        //获取帮助中心表数据
        $response = $center->order('sort desc')->select();

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 获取消息中心数据
     *
     * @return $data ''
     */
    public function notice_center()
    {

        //公告表 模型
        $notice = model('Notice');
        //获取公告表数据
        $response = $notice->where(['show'=>1])->order('recommend asc')->order('create_time desc')->select();

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 新增收货地址
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  name 'text'     姓名
     * @param  mobile '15000000000' 手机号
     * @param  province '浙江'    省份
     * @param  city '杭州'    城市
     * @param  county '西湖区'    区(县)
     * @param  address '地址'
     * @param  encoding '123456'  邮政编码
     * @return $data
     */
    public function address_add()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //封装用户uid
        $param['uid'] = $user_info['uid'];
        //我的地址表 验证器
        $validate = validate('Address');
        //验证 提交数据
        if(!$validate->check($param)){
            abort(501,$validate->getError());
        }
        //我的地址表 模型
        $address = model('Address');
        //添加,过滤非数据表字段的数据
        $response = $address->allowField(true)->save($param);
        if(!$response) {
            abort(605,'新增收货地址失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 收货地址管理
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @return $data
     */
    public function address_list()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);

        //我的地址表 模型
        $address = model('Address');
        //查询收货地址数据
        $response = $address->where("uid",$user_info['uid'])->order("create_time asc")->order("status asc")->select();
        //循环遍历
        foreach($response as $key=>$value) {
            //封装完整地址
            $response[$key]['address'] = $value['province'].$value['city'].$value['county'].$value['address'];
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 获取地址详情
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  address_id '1'  地址表id
     * @return $data
     */
    public function address_detail()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['address_id'])) {
            abort(501,'地址表id必需');
        }

        //我的地址表 模型
        $address = model('Address');
        //查询收货地址数据
        $response = $address->where("address_id",$param['address_id'])->find();

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 修改收货地址
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  address_id '1'  地址表id
     * @param  name 'text'     姓名
     * @param  mobile '15000000000' 手机号
     * @param  province '浙江'    省份
     * @param  city '杭州'    城市
     * @param  county '西湖区'    区(县)
     * @param  address '地址'
     * @param  encoding '123456'  邮政编码
     * @return $data
     */
    public function address_update()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['address_id'])) {
            abort(501,'地址表id必需');
        }
        //封装用户uid
        $param['uid'] = $user_info['uid'];
        //我的地址表 验证器
        $validate = validate('Address');
        //验证 提交数据
        if(!$validate->check($param)){
            abort(501,$validate->getError());
        }
        //我的地址表 模型
        $address = model('Address');
        //更新,过滤非数据表字段的数据
        $response = $address->allowField(true)->save($param,['address_id'=>$param['address_id']]);
        if(!$response) {
            abort(605,'修改收货地址失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 删除收货地址
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  address_id '1'     地址表id
     * @return $data
     */
    public function address_delete()
    {
        //获取提交数据
        $param = input('post.');
        //验证 登录
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['address_id'])) {
            abort(501,'地址表id必需');
        }
        //我的地址表 模型
        $address = model('Address');
        //删除数据
        $response = $address::destroy($param['address_id']);
        //判断是否删除成功
        if(!$response) {
            abort(606,'删除收货地址失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }


    /**
     * 选择默认收货地址
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  address_id '1'     地址表id
     * @return $data
     */
    public function address_default()
    {
        //获取提交数据
        $param = input('post.');
        //验证 登录
        $result = parent::is_login($param['token']);
        //验证数据
        if(empty($param['address_id'])) {
            abort(600,'地址表id必需');
        }
        //我的地址表 模型
        $address = model('Address');
        //更新默认地址
        $response = $address->save(['status'=>1],["address_id"=>$param['address_id'],"uid"=>$result['uid']]);
        if($response) {
            //查询收货地址数据
            $data = $address->where("uid",$result['uid'])->select();
            //循环遍历
            foreach($data as $key=>$value) {
                //判断地址状态
                if($value['address_id'] != $param['address_id'] && $value['status'] == 1) {
                    $address->save(['status'=>2],["address_id"=>$value['address_id']]);
                }
            }
        } else {
            abort(524,'更新默认地址失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 新增留言数据
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  type 'text'     意见类型
     * @param  mobile '15000000000' 手机号
     * @param  content '留言'    反馈内容
     * @param  picture '12##12'    照片说明(多张照片用##分割)
     * @return $data
     */
    public function leave_word_add()
    {
        //获取提交数据
        $param = input('post.');
        //验证 登录
        $user_info = parent::is_login($param['token']);
        //封装用户uid
        $param['uid'] = $user_info['uid'];
        //留言表 验证器
        $validate = validate('LeaveWord');
        //验证 提交数据
        if(!$validate->check($param)){
            abort(501,$validate->getError());
        }
        //留言表 模型
        $leave_word = model('LeaveWord');
        //添加,过滤非数据表字段的数据
        $response = $leave_word->allowField(true)->save($param);
        if(!$response) {
            abort(607,'新增留言失败');
        }
        //=================新增留言时发送邮件
        //邮件表 模型
        $email = model('Email');
        //查询邮件表无询价记录数据
        $data_email = $email->get(6);
        //判断是否查询到数据
        if(!empty($data_email)) {
            //判断客服是否需要发送邮件
            if($data_email['service_status'] == 1) {
                //获取邮件模板内容
                $email_content = $data_email['content'];
                //替换用户手机号
                $email_content = str_replace('{$mobile}',$param['mobile'],$email_content);
                //替换类型
                $email_content = str_replace('{$type}',$param['type'],$email_content);
                //替换反馈内容
                $email_content = str_replace('{$content}',$param['content'],$email_content);
                //判断是否有照片
                if(!empty($param['picture'])) {
                    //替换照片
                    $email_content = str_replace('{$picture}','有照片',$email_content);
                } else {
                    //替换照片
                    $email_content = str_replace('{$picture}','无照片',$email_content);
                }
                //替换联系方式
                $email_content = str_replace('{$mobile}',$param['mobile'],$email_content);

                //系统配置参数表
                $system_deploy = model('SystemDeploy');
                //查询系统配置参数数据
                $data_system = $system_deploy->field('email')->find();
                //给客服发送smtp163邮件
                if(!empty($data_system['email'])) {
                    //获取客服email数组
                    $service_email = explode(';',$data_system['email']);
                    //给每个客服发送邮件
                    foreach($service_email as $key=>$value) {
                        send_email($data_email['title'],$email_content,$value);
                    }
                }
            }
        }
        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 新增留言数据
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  type 'text'     意见类型
     * @param  mobile '15000000000' 手机号
     * @param  content '留言'    反馈内容
     * @param  picture '12##12'    照片说明(多张照片用##分割)
     * @return $data
     */
    public function h5_leave_word_add()
    {
        //获取提交数据
        $param = input('post.');
        //验证 登录
        $user_info = parent::is_login($param['token']);
        //保存base64图片
        $picture_array = array();
        if(!empty($param['picture'])) {
            foreach($param['picture'] as $key=>$value) {
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $value, $result)){
                    $type = $result[2];     //jpeg  $result[1] data:image/jpeg;base64,
                    /*以时间来命名上传的文件*/
                    $time = date ( 'Ymdhis' ).randCode(2,1);
                    $new_file = ROOT_PATH . 'public/uploads/images/leave_word/'.$time.'.'.$type;
                    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $value)))){
                        $picture_array[] = 'leave_word/'.$time.'.'.$type;
                    }
                }
            }
        }
        //封装图片参数
        if(empty($picture_array)) {
            $param['picture'] = '';
        } else {
            $param['picture'] = implode('##',$picture_array);
        }
        //封装用户uid
        $param['uid'] = $user_info['uid'];
        //留言表 验证器
        $validate = validate('LeaveWord');
        //验证 提交数据
        if(!$validate->check($param)){
            abort(501,$validate->getError());
        }
        //留言表 模型
        $leave_word = model('LeaveWord');
        //添加,过滤非数据表字段的数据
        $response = $leave_word->allowField(true)->save($param);
        if(!$response) {
            abort(607,'新增留言失败');
        }
        //=================新增留言时发送邮件
        //邮件表 模型
        $email = model('Email');
        //查询邮件表无询价记录数据
        $data_email = $email->get(6);
        //判断是否查询到数据
        if(!empty($data_email)) {
            //判断客服是否需要发送邮件
            if($data_email['service_status'] == 1) {
                //获取邮件模板内容
                $email_content = $data_email['content'];
                //替换用户手机号
                $email_content = str_replace('{$mobile}',$param['mobile'],$email_content);
                //替换类型
                $email_content = str_replace('{$type}',$param['type'],$email_content);
                //替换反馈内容
                $email_content = str_replace('{$content}',$param['content'],$email_content);
                //判断是否有照片
                if(!empty($param['picture'])) {
                    //替换照片
                    $email_content = str_replace('{$picture}','有照片',$email_content);
                } else {
                    //替换照片
                    $email_content = str_replace('{$picture}','无照片',$email_content);
                }
                //替换联系方式
                $email_content = str_replace('{$mobile}',$param['mobile'],$email_content);

                //系统配置参数表
                $system_deploy = model('SystemDeploy');
                //查询系统配置参数数据
                $data_system = $system_deploy->field('email')->find();
                //给客服发送smtp163邮件
                if(!empty($data_system['email'])) {
                    //获取客服email数组
                    $service_email = explode(';',$data_system['email']);
                    //给每个客服发送邮件
                    foreach($service_email as $key=>$value) {
                        send_email($data_email['title'],$email_content,$value);
                    }
                }
            }
        }
        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 上传照片
     * @param  base64 ''
     * @return $data
     */
    public function save_picture()
    {
        //验证 提交数据
        if(empty($_FILES ['picture'])) {
            abort(501,'照片为空，请重新拍照上传');
        }
        //获取图片临时文件
        $tmp_file = $_FILES ['picture'] ['tmp_name'];
        //获取图片后缀格式
        $file_types = explode ( ".", $_FILES ['picture'] ['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];
        /*设置上传路径*/
        $savePath = ROOT_PATH . 'public' . DS . 'uploads/images/';
        /*以时间来命名上传的文件*/
        $str = date ( 'Ymdhis' );
        $file_name = 'leave_word/'.$str . "." . $file_type;
        /*是否上传成功*/
        if (!copy($tmp_file, $savePath . $file_name)) {
            abort(501,'上传失败');
        }
        $response['address'] = $file_name;
        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 获取验证码图片
     *
     * @return $data
     */
    public function verification_code()
    {
        //验证码的配置参数
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 设置验证码字符为纯数字
            //'codeSet '    =>    '0123456789',
            // 验证码图片高度
            //'imageH'      =>    '500',
            // 验证码图片宽度
            //'imageW'      =>    '500',
        ];

        //实例化验证码类
        $captcha = new Captcha($config);
        //返回图片base64
        return json(array('status'=>'200','message'=>'success','data'=>$captcha->base64_entry()));
    }

    /**
     * 验证验证码,并发送手机验证码
     *
     * @param mobile '手机号码'
     * @param  verification_code '2154'   图片验证码
    //* @params sign md5(mobile+key)   key = Api123
     * @return $second '用于倒计时的秒数'
     * @return $code '验证码'
     * @return $message '验证码发送状态'
     */
    public function sms()
    {
        //获取提交数据
        $param = input('post.');
        //手机号码 验证器
        $validate = validate('mobile');
        //验证 提交数据
        if(!$validate->check($param)){
            abort(501,$validate->getError());
        }
        //实例化验证码类
        /*$captcha = new Captcha();
        //判断图片验证码是否正确
        if(!$captcha->check($param['verification_code'], '')) {
            abort(609,'图片验证码验证失败');
        }*/
        //验证码
        $code = rand(100000,999999);
        //手机验证码表 模型
        $sms_code = model('SmsCode');
        //查询数据
        $list_mobile = $sms_code->get(['mobile'=>$param['mobile']]);
        /*手机号白名单
        if($param['mobile'] == 15000000000) {
            //更新数据
            $sms_code->save(['code'=>$code],['sms_id'=>$list_mobile['sms_id']]);
            //返回数据
            $response['second'] = 60;
            $response['code'] = $code;
            return json(array('status'=>'200','message'=>'success','data'=>$response));
        }*/
        //判断数据
        if(empty($list_mobile)) {
            //更新数据
            $data_sms['mobile'] = $param['mobile'];
            $data_sms['code'] = $code;
            $return_sms = $sms_code->save($data_sms);
        } else {
            //三分钟之内再次请求，验证码不变，不发短信
            $time = time();
            if($time < ($list_mobile['create_time'] + 180)){
                abort(610,'3分钟内验证码不变');
            } else {
                //更新数据
                $return_sms = $sms_code->save(['code'=>$code],['sms_id'=>$list_mobile['sms_id']]);
            }
        }
        //判断更新或插入数据是否成功
        if($return_sms) {
            //发送短信  ,使用61621 聚合短信模板id
            $info = sendMess($param['mobile'],$code,'61621');
            //判断是否发送成功
            if(!$info){
                abort(502,'短信发送失败');
            }else{
                //返回数据
                $response['second'] = 60;
                $response['code'] = $code;
                return json(array('status'=>'200','message'=>'success','data'=>$response));
            }
        } else {
            abort(503,'更新失败');
        }
    }



    /**
     * 代理商->我的业绩
     *
     * @param  token 'f4bc776debaea1831830d06664bb1240'
     * @param  page '当前页'  默认1
     * @param  page_size '页大小'  默认6
     * @return $data ''
     */
    public function my_performance()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //分页处理
        $page = empty($param['page'])? 1 : $param['page'];
        $page_size = empty($param['page_size'])? 6 : $param['page_size'];
        $start = ($page-1)*$page_size;

        //期权持仓订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['superior_uid'] = ['=',$user_info['uid']];  //对接人uid
        //$where['status'] = ['=',2];                         //1:未结算 2:已结算
        $where['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核

        //查询总期权持仓订单数量
        $count = $order->where($where)->count();

        //获取期权交易订单数据
        $data_order = $order->where($where)->order('create_time desc')->limit($start,$page_size)->select();
        //定义空数组
        $response = array();
        //处理期权持仓订单数据
        foreach($data_order as $key=>$value) {
            $response[$key]['name'] = $value['possessor']['client']['name'];   //持有人姓名
            $response[$key]['principal'] = ($value['principal']/10000).'万';       //名义本金(万)
            $response[$key]['time'] = date("Y-m-d",$value['begin_time']).'至'.date("Y-m-d",$value['end_time']);//开始时间至结束时间
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','count'=>$count,'data'=>$response));
    }



    /**
     * 业绩查看(前端管理员、机构、代理人角色)
     *
     * @param  token 'f4bc776debaea1831830d06664bb1240'
     * @param  page '当前页'  默认1
     * @param  page_size '页大小'  默认6
     * @param  time '2018-01'  年月份
     * @return $data ''
     */
    public function performance()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['time'])) {
            abort(501,'年月份必需');
        }
        //分页处理
        $page = empty($param['page'])? 1 : $param['page'];
        $page_size = empty($param['page_size'])? 6 : $param['page_size'];
        $start = ($page-1)*$page_size;

        //总订单数
        $order_count = 0;
        //模型多次循环出错 Indirect modification of overloaded element of app\\api\\model\\User has no effect，不能修改其值
        //用户表
        $user = db('User');
        //期权持仓订单表
        $order = db('Order');
        //前端管理员/机构/代理人/普通用户信息表
        $info_client = db('InfoClient');
        //封装搜索条件
        $where['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核
        //获取当前月及下月时间戳
        $first_timestamp = strtotime($param['time']);
        $first_day = date("Y-m-01",$first_timestamp);
        $last_timestamp = strtotime("$first_day +1 month -1 day");
        $where['begin_time'] = ['between',[$first_timestamp,$last_timestamp]];       //开始时间戳范围

        //判断用户角色
        switch($user_info['group_id']) {
            case '前端管理员':
                //查询状态启用的机构用户
                $response = $user->field('uid')->where(['group_id'=>5,'status'=>1])->limit($start,$page_size)->select();

                foreach($response as $key=>$value) {
                    //封装机构用户数据
                    $response[$key]['order_count'] = 0;                 //总单数
                    $response[$key]['principal_count'] = 0;             //总名义本金(万)
                    $client_name = $info_client->field('name')->where('uid',$value['uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    //查询状态启用的下级代理人
                    $response[$key]['agent'] = $user->field('uid')->where(['group_id'=>3,'belong_id'=>$value['uid'],'status'=>1])->select();
                    foreach($response[$key]['agent'] as $ke=>$va) {
                        //封装搜索条件
                        $where['superior_uid'] = $va['uid'];        //订单对接人uid
                        //封装代理人用户数据
                        $response[$key]['agent'][$ke]['order_count'] = $order->where($where)->count();         //总单数
                        $response[$key]['agent'][$ke]['principal_count'] = $order->where($where)->sum('principal')/10000;     //总名义本金(万)
                        $client_name = $info_client->field('name')->where('uid',$va['uid'])->find();
                        $response[$key]['agent'][$ke]['name'] = $client_name['name']; //姓名
                        $response[$key]['agent'][$ke]['client'] = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->order('create_time desc')->select();     //查询下线订单

                        foreach($response[$key]['agent'][$ke]['client'] as $k=>$v) {
                            //封装普通用户数据
                            $client_name = $info_client->field('name')->where('uid',$v['possessor_uid'])->find();
                            $response[$key]['agent'][$ke]['client'][$k]['name'] = $client_name['name']; //姓名
                            $response[$key]['agent'][$ke]['client'][$k]['principal'] = $v['principal']/10000; //名义本金(万)
                            $response[$key]['agent'][$ke]['client'][$k]['time'] = date('Y-m-d',$v['begin_time']).'至'.date('Y-m-d',$v['end_time']); //期限
                        }
                        //增加机构总单数
                        $response[$key]['order_count'] += $response[$key]['agent'][$ke]['order_count'];
                        //增加机构总名义本金
                        $response[$key]['principal_count'] += $response[$key]['agent'][$ke]['principal_count'];
                    }
                    //增加总单数
                    $order_count += $response[$key]['order_count'];
                }
                break;
            case '机构':
                //查询状态启用的下线代理人用户
                $response = $user->field('uid')->where(['group_id'=>3,'status'=>1,'belong_id'=>$user_info['uid']])->limit($start,$page_size)->select();

                foreach($response as $key=>$value) {
                    //封装搜索条件
                    $where['superior_uid'] = $value['uid'];        //订单对接人uid
                    //封装代理人用户数据
                    $response[$key]['order_count'] = $order->where($where)->count();         //总单数
                    $response[$key]['principal_count'] = $order->where($where)->sum('principal')/10000;     //总名义本金(万)
                    $client_name = $info_client->field('name')->where('uid',$value['uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    $response[$key]['client'] = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->order('create_time desc')->select();       //查询下线订单

                    foreach($response[$key]['client'] as $k=>$v) {
                        //封装普通用户数据
                        $client_name = $info_client->field('name')->where('uid',$v['possessor_uid'])->find();
                        $response[$key]['client'][$k]['name'] = $client_name['name']; //姓名
                        $response[$key]['client'][$k]['principal'] = $v['principal']/10000; //名义本金(万)
                        $response[$key]['client'][$k]['time'] = date('Y-m-d',$v['begin_time']).'至'.date('Y-m-d',$v['end_time']); //期限
                    }
                    //增加总单数
                    $order_count += $response[$key]['order_count'];
                }
                break;
            case '代理人':
                //封装搜索条件
                $where['superior_uid'] = $user_info['uid'];        //订单对接人uid
                //总单数
                $order_count = $order->where($where)->count();         //总单数
                //查询下线订单
                $response = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->limit($start,$page_size)->order('create_time desc')->select();
                foreach($response as $key=>$value) {
                    //封装普通用户数据
                    $client_name = $info_client->field('name')->where('uid',$value['possessor_uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    $response[$key]['principal'] = $value['principal']/10000; //名义本金(万)
                    $response[$key]['time'] = date('Y-m-d',$value['begin_time']).'至'.date('Y-m-d',$value['end_time']); //期限
                }
                break;
            default:
                $response = array();
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','order_count'=>$order_count,'data'=>$response));
    }



    /**
     * 数据接口注册
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  company '公司名称'  单位名称
     * @param  mobile 15000000000  手机号码
     * @param  code 123456  手机验证码
     * @return $data
     */
    public function port_register()
    {

        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['company'])) {
            abort(600,'单位名称不能为空');
        }
        if(empty($param['mobile'])) {
            abort(600,'手机号码不能为空');
        }
        if(empty($param['code'])) {
            abort(600,'手机验证码不能为空');
        }
        //手机验证码表 模型
        $sms_code = model('SmsCode');
        //查询数据
        $list_mobile = $sms_code->get(['mobile'=>$param['mobile']]);
        //验证数据
        if(empty($list_mobile)) {
            abort(612,'该手机号未发送验证码');
        }
        if($list_mobile['code'] != $param['code'] || time() > ($list_mobile['create_time'] + 600)) {
            abort(613,'短信验证码错误');
        }

        //接口申请表 模型
        $port_apply = model('PortApply');
        //封装数据
        $param_register['uid'] = $user_info['uid'];
        $param_register['company'] = $param['company'];
        $param_register['mobile'] = $param['mobile'];
        //添加接口申请表数据
        $response_user = $port_apply->save($param_register);
        //判断是否成功
        if(!$response_user) {
            abort(503,'添加接口申请表数据失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response_user));
    }



    /**
     * 接口服务基本信息
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @return $data
     */
    public function port_message()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);

        //数据接口表 模型
        $port_data = model('PortData');
        //查询数据接口数据
        $response = $port_data->where('uid',$user_info['uid'])->find();
        //处理到期日期
        if(!empty($response)) {
            $response['end_time'] = date('Y-m-d',$response['end_time']);
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 接口服务调用说明列表
     * @return $data
     */
    public function port_explain()
    {
        //接口调用表 模型
        $port_explain = model('PortExplain');
        //获取 调用说明列表数据
        $response = $port_explain->field('port_id,title,create_time,update_time')->order('sort desc')->select();

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }




    /**
     * 接口服务调用说明详情
     * @param  port_id '7'    调用说明表id
     * @return $data
     */
    public function explain_detail()
    {
        //获取提交数据
        $param = input('post.');
        //验证数据
        if(empty($param['port_id'])) {
            abort(600,'调用说明表id不能为空');
        }
        //分类id
        $where['port_id'] = ['=',$param['port_id']];
        //接口调用表 模型
        $port_explain = model('PortExplain');
        //查询接口调用表数据
        $response = $port_explain->where($where)->find();
        if(empty($response)) {
            abort(600,'调用说明详情不存在,表id错误');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response));
    }



    /**
     * 数据接口修改浮动点数和接口密码
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  float 0.01  浮动点数
     * @param  password '123456'  接口密码
     * @return $data
     */
    public function edit_float()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['password'])) {
            abort(600,'接口密码不能为空');
        }
        if(empty($param['float'])) {
            $param['float'] = 0;
        } else {
            $param['float'] = (float)$param['float'];
        }

        //数据接口表 模型
        $port_data = model('PortData');
        //修改数据接口浮动点数数据
        $response_port = $port_data->allowField(true)->save($param,['uid'=>$user_info['uid']]);
        //判断是否成功
        if(!$response_port && $response_port != 0) {
            abort(503,'修改失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response_port));
    }



    /**
     * 数据接口续费申请
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  date '一个月'  续费时长
     * @return $data
     */
    public function port_renew()
    {

        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);
        //验证数据
        if(empty($param['date'])) {
            abort(600,'续费时长不能为空');
        }
        //数据接口表 模型
        $port_data = model('PortData');
        //查询数据接口数据
        $data_port = $port_data->where('uid',$user_info['uid'])->field('data_id')->find();

        //接口续费表 模型
        $port_renew = model('PortRenew');
        //封装数据
        $param_renew['uid'] = $user_info['uid'];
        $param_renew['data_id'] = $data_port['data_id'] ? $data_port['data_id'] : 0;
        $param_renew['date'] = $param['date'];
        //添加接口续费数据
        $response_renew = $port_renew->save($param_renew);
        //判断是否成功
        if(!$response_renew) {
            abort(503,'添加数据接口续费失败');
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','data'=>$response_renew));
    }



    /**
     * 获取期权数据接口
     * @param  useradmin 'test'   账号
     * @param  password '123456'  密码
     * @param  code '000001'  股票代码
     * @param  principal '1000000'  名义本金(元)
     * @param  time '1'  行权周期1~12
     * @return $data
     */
    public function port_data()
    {

        //获取提交数据
        $param = input('param.');
        //验证数据
        if(empty($param['useradmin'])) {
            abort(600,'账号参数不能为空');
        }
        if(empty($param['password'])) {
            abort(600,'密码参数不能为空');
        }
        if(empty($param['code'])) {
            abort(600,'股票代码参数不能为空');
        }
        if(empty($param['principal'])) {
            abort(600,'名义本金参数不能为空');
        }
        //转为整数
        $param['principal'] = intval($param['principal']);
        if($param['principal'] < 200000 || $param['principal']%100000 !=0) {
            abort(600,'名义本金不是100000的倍数或小于200000元');
        }
        if(preg_match('/^[1-9]|1[0-2]$/',$param['time']) == 0) {
            abort(600,'行权周期格式错误,1~12整数');
        }

        //数据接口表 模型
        $port_data = model('PortData');
        //查询数据接口数据
        $data_port = $port_data->where(['useradmin'=>$param['useradmin'],'password'=>$param['password']])->find();
        //判断是否查询到数据
        if(empty($data_port)) {
            abort(600,'账号或者密码错误');
        }
        //判断是否到期
        if($data_port['end_time'] < time()) {
            abort(600,'账号已到期');
        }
        //判断账号状态是否启用
        if($data_port['status'] != 1) {
            abort(600,'账号状态未启用');
        }
        //股票汇率表 模型
        $rate = model('Rate');
        //查询股票汇率数据
        $data_rate = $rate->where('code',$param['code'])->find();
        //判断是否查询到数据
        if(empty($data_rate)) {
            abort(600,'股票代码不存在');
        }
        //股票数据表 模型
        $stock = model('Stock');
        //查询股票数据
        $data_stock = $stock->where('symbol',$param['code'])->find();
        //判断是否查询到数据
        if(empty($data_stock)) {
            abort(600,'股票代码不存在');
        }

        //封装返回数据
        $response['stock'] = $data_stock['name'].'('.$data_stock['symbol'].')';     //股票信息
        $response['principal'] = $param['principal'];     //名义本金(元)
        $response['time'] = parent::transform_month($param['time']);    //行权周期
        $response['premium'] = $data_rate['month'.$param['time']] + $data_port['float'];    //期权费率
        $response['royalty'] = round($response['premium']*$param['principal'],2);    //权利金
        $response['begin_time'] = date('Y-m-d');            //开始时间
        $response['end_time'] = date('Y-m-d',strtotime("+{$param['time']} month"));    //结束时间

        //增加调用次数
        $port_data->where('data_id', $data_port['data_id'])->setInc('hits');

        //封装添加接口调用表参数
        $param_call['uid'] = $data_port['uid'];    //用户表id
        $param_call['data_id'] = $data_port['data_id'];    //数据接口表id
        $param_call['code'] = $param['code'];    //股票代码
        $param_call['title'] = $data_stock['name'];    //股票名称
        $param_call['principal'] = $param['principal'];    //名义本金(元)
        $param_call['time'] = $response['time'];    //期限
        $param_call['premium'] = $response['premium'];    //期权费率
        $param_call['royalty'] = $response['royalty'];    //权利金

        //接口调用表 模型
        $port_call = model('PortCall');
        //添加,接口调用表
        $result_call = $port_call->save($param_call);

        //返回数据
        return json(array('status'=>200,'message'=>'success','data'=>$response));
    }

    /**
     * 按年份结算记录
     *
     * @param  token '77f683b4188917ac90ebe8ef416774af'
     * @param  page '当前页'  默认1
     * @param  page_size '页大小'  默认6
     * @param  time '2018'  年份
     * @return $data ''
     */
    public function get_year_order()
    {
        //获取提交数据
        $param = input('post.');
        //验证登录,返回用户信息
        $user_info = parent::is_login($param['token']);

        //验证数据
        if(empty($param['time'])) {
            abort(501,'年份必需');
        }
        //分页处理
        $page = empty($param['page'])? 1 : $param['page'];
        $page_size = empty($param['page_size'])? 6 : $param['page_size'];
        $start = ($page-1)*$page_size;

        //期权持仓订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['possessor_uid'] = ['=',$user_info['uid']];  //持有人uid
        $where['status'] = ['=',2];                         //1:未结算 2:已结算
        $where['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核
        $yeartime =mktime(0,0,0,1,1,$param['time']);
        $nextyeartime=$yeartime+86400*365;
        $where['begin_time'] = [['>',$yeartime],['<',$nextyeartime],'and'];//开始时间
        // $where['create_time'] = ['like',"%{$param['time']}%"]; //创建时间

        //计算年份总盈利
        $profit = $order->where($where)->sum('actual_income');

        //获取期权交易结算数据
        $data_order = $order->where($where)->order('create_time desc')->limit($start,$page_size)->select();
        //处理期权持仓订单数据
        $response = array();
        foreach($data_order as $key=>$value) {
            $response[$key]['order_id'] = $value['order_id'];                       //订单表id
            $response[$key]['title'] = $value['title'].' ('.$value['code'].')';     //股票名称和代码
            $response[$key]['principal'] = ($value['principal']/10000).'万元';     //名义本金万元
            $response[$key]['time'] = date("Y-m-d",$value['begin_time']).'--'.date("Y-m-d",$value['end_time']);       //开始时间--结束时间
            $response[$key]['actual_income'] = number_format($value['actual_income'],2);     //创收
        }

        //返回数据
        return json(array('status'=>'200','message'=>'success','profit'=>$profit,'data'=>$response));
    }

}