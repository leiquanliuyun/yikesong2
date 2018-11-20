<?php
namespace app\index\controller;
use think\Controller;
/**
 * 登录注册 控制器
 */
class LoginController extends Controller {

    /**
     * 首页
     */
    public function login() {

        //获取浏览器信息
        $user_agent = $_SERVER['HTTP_USER_AGENT'];//var_dump($user_agent);exit;
        //判断是否是微信浏览器
        if (strpos($user_agent, 'MicroMessenger') != FALSE) {
            //判断是否登录
            if(empty(session('user_info'))) {
                //获取数据
                $param = input('param.');
                //做跳转，拿到openid,第一步跳转链接
                if (empty($param['openid'])) {
                    //系统配置参数表 模型
                    $system_deploy = model('SystemDeploy');
                    //查询 系统配置参数 数据
                    $system = $system_deploy->field('app_id')->find();
                    //获取上级用户id
                    $uid = input('param.uid');
                    $uid = empty($uid) ? 0: $uid;
                    //获取网站url
                    $url = request()->root(true);
                    $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$system['app_id']."&redirect_uri=".$url."/index/login/wei_xin/uid/".$uid."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
                    //echo "<script language='javascript'>window.location='".$url."'</script>";
                    header("Location:".$url);
                }else{//什么情况下走这？wei_xin()
                    //获取参数
                    $param = input('param.');
                    //存入session
                    session('belong_id', $param['uid']);
                    //用户表 模型
                    $user = model('User');
                    //查询用户表数据
                    $result = $user->where('union_id',$param['unionid'])->find();
                    //判断是否为空
                    if(!empty($result)) {
                        //判断账号是否正常
                        switch($result['status']) {
                            case '停用':
                                echo "该账号已被停用";exit;
                                break;
                            case '已删除':
                                echo "该账号已被删除";exit;
                                break;
                        }
                        //判断头像是否更新
                        if($result['client']['avatar'] != $param['avatar']) {
                            //更换头像数据
                            $result['client']['avatar'] = $param['avatar'];
                            //前端管理员/机构/代理人/普通用户信息表 模型
                            $info_client = model('InfoClient');
                            $info_client->save(['avatar'=>$param['avatar']],['uid'=>$result['uid']]);
                        }
                        //更新用户最后登录时间和ip
                        $user->save(["last_login_time"=>date("Y-m-d H:i:s"),"ip"=>request()->ip()],["uid"=>$result['uid']]);

                        //存入session
                        session('user_info', $result);
                        //重定向
                        $this->redirect('index/index1');

                        //注册用户
                    } else {
                        //封装用户表参数
                        $param_user['identification'] = randCode(2,2).randCode(6,1);         //客户id标识
                        $param_user['salt'] = randCode(8);                                   //加密串
                        $param_user['username'] = trim($param['unionid']);                //账号
                        $param_user['password'] = md5('123456'.$param_user['salt']);        //密码
                        $param_user['group_id'] = 4;                                         //角色：普通用户
                        $param_user['token'] = md5($param['openid'].time());                //token
                        $param_user['openid'] = $param['openid'];                           //微信openid
                        $param_user['union_id'] = $param['unionid'];                 //微信开放平台union_id
                        $param_user['last_login_time'] = date("Y-m-d H:i:s");               //上次登录时间
                        //添加,用户表数据
                        $result_user = $user->save($param_user);
                        //添加关联表数据,过滤非数据表字段的数据
                        if($result_user) {
                            //代理商/顾客信息表 模型
                            $info_client = model('InfoClient');
                            //封装顾客信息表参数
                            $param_client['uid'] = $user->uid;              //关联表外键uid
                            $param_client['avatar'] = $param['avatar'];     //头像
                            $param_client['nickname'] = $param['nickname'];     //昵称
                            //添加,顾客信息表数据
                            $info_client->save($param_client);

                        } else {
                            abort(611,'注册失败');
                        }

                        //存入session
                        session('user_info', $user::get($user->uid));
                        //重定向
                        $this->redirect('index/index1');
                    }

                }
            }
        }

    }



    /**
     * 微信回调
     */
    public function wei_xin() {
        //获取参数
        $param = input('param.');
        //判断是否获取到了coed
        if (isset($param['code'])) {
            //系统配置参数表 模型
            $system_deploy = model('SystemDeploy');
            //查询 系统配置参数 数据
            $system = $system_deploy->field('app_id,secret,deploy_id,access_token,expires_in')->find();

            $appid = $system['app_id'];
            $secret = $system['secret'];
            $code = $param['code'];
            $uid = $param['uid'];//上级用户id

            //第一步:取得openid、网页授权access_token
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $oauth2 = self::getJson($url);

            //1.1 获取普通access_token
            //判断时间戳是否过期
            if(!empty($system['expires_in']) && !empty($system['access_token']) && $system['expires_in'] > time()) {
                //从数据库获取access_token
                $access_token = $system['access_token'];
            } else {
                //获取普通access_token
                $url_token="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
                $return_token = self::getJson($url_token);
                $access_token = $return_token["access_token"];

                //更新access_token
                $system->save(['access_token'=>$access_token,'expires_in'=>time()+$return_token['expires_in']],['deploy_id'=>$system['deploy_id']]);;
            }

            //2.2获取用户基本信息(UnionID机制)
            $openid = $oauth2['openid'];
            $url_info = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $user_info = self::getJson($url_info);

            //获取网站url
            $url = request()->root(true);
            $goUrl=$url.'/index/login/login';
            //跳转url地址
            if (!empty($user_info['unionid'])) {
                header("Location:".$goUrl."?openid=".$user_info['openid']."&uid=".$uid."&nickname=".$user_info['nickname']."&address=".$user_info['country'].$user_info['province'].$user_info['city']."&avatar=".$user_info['headimgurl']."&unionid=".$user_info['unionid']);
            } else {
                $this->error('您好!为获得更好的服务体验,请务必先关注"一棵松"服务号.','https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU1NDcxNzY3Ng==&scene=124#wechat_redirect','',2);
            }
        }
    }




    /**
     * 发送验证码
     */
    public function sms() {
        //获取提交数据
        $param = input('post.');
        //手机号码 验证器
        $validate = validate('mobile');
        //验证 提交数据
        if(!$validate->check($param)){
            return json(array('status'=>'501','message'=>'请输出正确的手机号码!'));
        }

        //验证码
        $code = rand(100000,999999);
        //手机验证码表 模型
        $sms_code = model('SmsCode');

        //查询数据
        $list_mobile = $sms_code->get(['mobile'=>$param['mobile']]);
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
                return json(array('status'=>'502','message'=>'3分钟内验证码不变!'));
            } else {
                //更新数据
                $return_sms = $sms_code->save(['code'=>$code],['sms_id'=>$list_mobile['sms_id']]);
            }
        }

        //判断更新或插入数据是否成功
        if($return_sms) {
            //发送短信
            $info = sendMess($param['mobile'],$code);
            //判断是否发送成功
            if($info['status'] == 'fail'){
                return json(array('status'=>'503','message'=>'发送验证码失败!'));
            }else{
                //返回数据
                $response['second'] = 60;
                $response['code'] = $code;
                return json(array('status'=>'200','message'=>'发送验证码成功','data'=>$response));
            }
        } else {
            return json(array('status'=>'503','message'=>'验证码存储失败!'));
        }

    }



    //php curl
    public function getJson($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }



}