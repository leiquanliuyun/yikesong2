<?php
namespace app\api\controller;

use think\Controller;
use app\common\model\UserData;
use app\common\lib\push\Getui;
use app\common\lib\push\Jpush;
use app\common\lib\weixin\Wechat;
use app\api\model\Message;
//use JPush\Client;
use think\Log;
/**
 *　公共控制器，所有的控制器都继承此控制器（除登录控制器）
 */
class  CommonController extends Controller
{
    /**
     * 自动执行的方法()
     */
    public function _initialize()
    {
        //判断是否登录

    }
    // 删除响应文件
    public function responseDel() {
        if (is_file('./response.txt')) {
            unlink('./response.txt');
        }
        $this->success('删除成功！', 'response');
    }

    // 显示文件
    public function response() {
        echo '<a href="' . url('responseDel') . '">清空</a>';
        echo "\n";
        if (is_file('./response.txt')) {
            $content = file_get_contents('./response.txt');
            echo $content;
        }
        config('app_trace', false);
    }
    /*
     * 验证token是否存在
     */
    private function get_admin($token, $device) {
        if (!$token) {
            $result['info'] = '请求参数错误';
            $result['status'] = '1001';
            $result['data'] = null;
            $this->put_post($result);
        }
        $where['token'] = $token;
        $where['device'] = $device;
//        $where['create_time'] = ['>', time()-1296000];
        $ishave = db('user_data')->field('id,uid,create_time')->where($where)->find();

        if ($ishave) {
            if (strtotime($ishave['create_time']) > time() - 129600000) {
                $result['status'] = 1000;
                $result['info'] = 'ok';
                $result['uid'] = $ishave['uid'];
            } else {
                $result['status'] = 1002;
                $result['info'] = '登录已超时';
                $result['uid'] = $ishave['uid'];
            }
            return $result;
        } else {
            $result['info'] = '该账号在其他设备登录';//或尚未登录
            $result['status'] = 1006;
            $result['data'] = null;
            $this->put_post($result);
        }
    }
    /*
    * 验证token有效性
    * @ token 参数
    */
    public function check_token() {
        $token = $this->get_post('token');
        $device = $this->get_post('device');
        if (!$token || !$device) {
            $result['info'] = '请求参数错误';
            $result['status'] = '1001';
            $result['data'] = null;
            $this->put_post($result);
        }
        $res = $this->get_admin($token, $device);
        if ($res['status'] == '1000') {
            return $res['uid'];
        } else {
            $result = [];
            $result['status'] = 1002;
            $result['info'] = '验证失败';
            $result['data'] = null;
            $this->put_post($result);
        }
    }
    /*public function pushMess(){
        //vendor('Jpush/Jpush');
        $pushObj = new Jpush();
        //组装需要的参数
        $receive = 'all';     //全部
        //$receive = array('tag'=>array('1','2','3'));      //标签
        //$receive = array('alias'=>array('111'));    //别名
        $title = $_POST['title'];
        $content = ***;
	    $m_time = '86400';        //离线保留时间
	    $extras = array("versionname"=>***, "versioncode"=>***);   //自定义数组
	    //调用推送,并处理
	    $result = $pushObj->push($receive,$title,$content,$extras,$m_time);
	    if($result){
            $res_arr = json_decode($result, true);
            if(isset($res_arr['error'])){   //如果返回了error则证明失败
                //错误信息 错误码
                $this->error($res_arr['error']['message'].'：'.$res_arr['error']['code'],U('Jpush/index'));
            }else{
                //处理成功的推送......
                //可执行一系列对应操作~
                $this->success('推送成功~');
            }
        }else{      //接口调用失败或无响应
            $this->error('接口调用失败或无响应~');
        }
	}*/
    /*
     * 获取token方法
     * 参数
     * @ phone  手机号
     * @ password 密码
     */

    public function get_token($uid, $registration_id = '',$type=1) {//$type用于判断 1会员登录 2员工登录
        $device = data_isset($this->get_post('device'), 'trim', "");
        $platform = data_isset($this->get_post('platform'), '', "");//1：安卓，2：IOS，3：小程序，0：未知设备
        //$code = data_isset($this->get_post('code'), '', "");
        if (!$uid || !$device) {
           self::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        // 设备类型转num；
        $platform = getPlatform($platform);
        // 踢掉前面的用户；存在多个登录的可能.同一个用户在同一台手机登录会员端与员工端不掉线
        $userdata=new UserData();
        $login = $userdata->where(['uid'=>$uid,'type'=>$type])->select();//echo '<pre>'; print_r($login);
        if (!empty($login)) {
            $message=new Message();
            $app_key=config('jpush.app_key');
            $master_secret=config('jpush.master_secret');
            $client=new \JPush\Client($app_key,$master_secret,null);//registration_id anzhuo 160a3797c8210c195ad
            foreach ($login as $old_login) {
                // 同设备挤掉，小程序不能多个；如果不是小程序，不同的设备也掉线线
                if ($platform == $old_login['platform'] || ( $platform != 3 && $old_login['platform'] != 3 )
                ) {
                    if ($old_login['platform'] != 3 && !empty($old_login['registration_id']) && $old_login['registration_id'] != $registration_id
                    ) {
                        // 旧的：不是小程序登录，同时有个推的标识，进行推送；
                        // 并且不是同一个设备上的，避免客户端没有退出的时候，重新安装导致推送挤掉线通知
                        //jpush($old_login['registration_id'],'您的账号在其他设备重新登录，如不是本人操作，请及时修改密码！');
                        $pusher = $client->push();
                        $pusher->setPlatform('all');
                        $pusher->setOptions(null,null,null,false);
                        //print_r($old_login['registration_id']);
                        $pusher->addRegistrationId($old_login['registration_id']);
                        //$pusher->addAllAudience();
                        $pusher->setNotificationAlert("您的账号在其他设备重新登录，如不是本人操作，请及时修改密码");
                        try {
                            $r=$pusher->send();
                            //print_r($r);
                        } catch (\JPush\Exceptions\JPushException $e) {
                            print $e;
                            Log::write('推送处理失败：'.$e->getMessage() , Log::ERROR);
                            Log::write('推送处理文件：'.$e->getFile() , Log::ERROR);
                            Log::write('推送处理行号：'.$e->getLine() , Log::ERROR);
                        }
                        $message->registration_id=$old_login['registration_id'];
                        $message->title='登录提醒';
                        $message->content='您的账号在其他设备重新登录，如不是本人操作，请及时修改密码';
                        $message->isUpdate(false)->save();
                    }
                    $old_login->delete();
                }
            }
        }

        $randstr = randCode(12);
        $ip = get_ip();
        $attime = time();
        $token = md5($uid . $attime . ($attime + 1296000) . $ip . $randstr);
        $tokendata = [];
        $tokendata['uid'] = $uid;
        $tokendata['time'] = $attime;
        $tokendata['ip'] = $ip;
        $tokendata['randstr'] = $randstr;
        $tokendata['effect_time'] = $attime + 1296000;
        $tokendata['device'] = $device;

        $data = [];
        /*if (!empty($code) && $device == 'wx') {
            $wechat = new Wechat();
            $data['open_id'] = $wechat->authLogin($code, $device);
        }*/
        $data['create_time']=date('Y-m-d H:i:s',time());
        $data['uid'] = $uid;
        $data['type'] = $type;
        $data['token'] = $token;
        $data['token_detail'] = json_encode($tokendata);
        $data['device'] = $device;
        $data['platform'] = $platform;
        !empty($registration_id) && $data['registration_id'] = $registration_id;
        db('user_data')->insert($data);
        return $token;
    }
    /*
     * 由token获取uid
     * @ token 参数
     */
    public function get_userinfo() {
        $token = $this->get_post('token');
        $device = $this->get_post('device');
        $uid = 0;
        if ($token && $device) {
            $ishave = db('user_data')->field('id,uid,create_time')->where(['token' => $token, 'device' => $device])->find();
            if ($ishave) {
                $time=strtotime($ishave['create_time']);
                if ($time> time() - 129600000) {
                    $uid = $ishave['uid'];
                }
            }
        }
        return $uid;
    }
    // 数据统一入口
    protected function get_post($data) {
        //判断是否加密，有加密就需要解密
        $this->is_entrypt = input('is_entrypt', 0, 'intval');
        if ($this->is_entrypt == 0) {
            $post = input('param.', '', '');
            if (isset($post['entrypt_data'])) {
                $json = decrypt_app($post['entrypt_data']);
                $post = json_decode($json, true);
            }
        } elseif ($this->is_entrypt == 1) {
            $encrypted = input('entrypt_data', '', 'trim');
            $json = decrypt_app($encrypted);
            $post = json_decode($json, true);
        }

        if (!isset($post[$data]) || (empty($post[$data]) && !is_numeric($post[$data]))) {
            $post[$data] = "";
        }
        return $post[$data];
    }

    // 数据统一出口
    protected function put_post($result) {

        // 打印数据
        if (config('app_env') == 'dev' || true) {
            $break_action = [
                'chinacities', 'upload_image', 'getallareas'
            ];

            $data = input('param.');
            // print_r($this->request->header());
            $str = "<br><br>======================================================================<br><br>";
            $str .= '请求时间:' . date('Y-m-d H:i:s') . '  request_method:' . $this->request->method() . "<br><br>";
            $str .= '请求地址: ' . $this->request->url() . "<br><br>";
            if (in_array($this->request->action(), $break_action)) {
                $str .= '请求参数: 请求参数过于膨大<br><br>';
            } else {
                $str .= '请求参数: ' . json_encode($data, JSON_UNESCAPED_UNICODE) . "<br><br>";
            }
            $str .= '返回数据: ' . json_encode($result, JSON_UNESCAPED_UNICODE) . "<br><br>";
            $str .= '请求头: ' . json_encode($this->request->header(), JSON_UNESCAPED_UNICODE);

            $file = fopen('response.txt', 'a');
            fwrite($file, $str);
            fclose($file);
        }

        //判断是否加密，有加密就需要解密
        if ($this->is_entrypt == 0) {
            if ($result['status'] == 1000) {
                if (!empty($result['data'])) {
                    $this->_success($result['info'], $result['data'], '');
                } elseif (isset($result['data']) && !is_null($result['data'])) {
                    $this->_success($result['info'], $result['data']);
                } else {
                    $this->_success($result['info']);
                }
            } else {
                $this->_error($result['info'], $result['status']);
            }
        } elseif ($this->is_entrypt == 1) {
            echo json_encode(array('entrypt_data' => encrypt_app(json_encode($result))));
            exit;
        }
    }

    /**
     * 获取系统配置参数表数据
     *
     * @return $data
     */
    public function system_deploy(){
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数表数据
        $result = $system_deploy->find();
        //返回数据
        return $result;
    }

    /*
     * 图片上传接口
     *
     * @param  $files ''
     * @return $data
     * */
    public function upload_image($files){
        foreach($files as $file){
            // 验证图片并以date规则命名，移动到框架应用根目录/public/uploads/images 目录下
            $info = $file->rule('date')->validate(['ext'=>'gif,jpg,jpeg,bmp,png'])->move(ROOT_PATH . 'public' . DS . 'uploads/images');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                return $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
    }
    /**
     * 上传图片，base64，上传成功由服务器推到七牛服务器
     */
    public function save_base64_image($base64_img) {
        //set_time_limit(0);
        // data_isset($this->get_post('img'),'trim',"")
        //$base64_img = trim(data_isset($this->get_post('img'), 'trim', ""));
        $wwwroot = $_SERVER['DOCUMENT_ROOT'];
        $save_dir = $wwwroot . '/uploads/images';

        if (empty($base64_img)) {
            self::put_post(['status' => 1004, 'info' => '请选择上传图片']);
        }
        if (!preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
            self::put_post(['status' => 1004, 'info' => '选择的不是图片类型']);
        }
        $size = file_get_contents($base64_img);
        // parent::put_post( ['status'=> 1004 , 'info'=>strlen($size)/1024 ] );

        if (strlen($size) / 1024 > (2 * 1024)) {
            self::put_post(['status' => 1004, 'info' => '图片大小不能超过2M']);
        }
        $save_dir .= '/' . date('Ymd');
        if (!file_exists($save_dir)) {
            mkdir($save_dir, 0777, true);
        }
        $type = $result[2];
        if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
            $file_name = md5(microtime(true)) . '.' . $type;
            $new_file = $save_dir . '/' . $file_name;
            $filename=date('Ymd'). '/' .$file_name;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
                return $filename;
            } else {
                self::put_post(['status' => 1004, 'info' => '上传失败！']);
            }
        } else {
            //文件类型错误
            self::put_post(['status' => 1004, 'info' => '文件类型错误！']);
        }
    }


    /*
     * 保存base64位图片
     *
     * @param string $base64pic base64图片信息
     * @param string $path 保存路径例如 IA_ROOT . "/public/uploads/images/"
     * @return string 正确返回 Ymd/filename.jpg 按年月日存放的地址,错误返回 error
     */
    public function save_base64_picture($base64picture)
    {
        $level = date('Ymd/', time());//按年月日存放文件
        //$wwwroot = $_SERVER['DOCUMENT_ROOT'];
        $wwwroot = config('web_url');
        $save_dir = $wwwroot . '/uploads/images';
        $path = $save_dir . $level;
        if (!is_dir($path)) {
            mkdir($path, 0766, true);
        }
        $filename = md5(time());
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64picture, $result)) {
            $type = ($result[2] == 'jpeg') ? 'jpg' : $result[2];
            $path1 = $path . $filename . "." . $type;
            if (file_put_contents($path1, base64_decode(str_replace($result[1], '', $base64picture)))) {

                //返回按年月日存放的地址
                return $path1;
            }
        }
        return false;
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
     * 生成随机字符串
     * @param $length '要生成的随机字符串长度'
     * @param $type 0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
     * @return string
     */
    protected function randCode($length = 5, $type = 0) {
        $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
        if ($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        } elseif ($type == "-1") {
            $string = implode("", $arr);
        } else {
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $string[rand(0, $count)];
        }
        return $code;
    }



    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }



}