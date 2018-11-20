<?php
use think\Loader;
function getPlatform($platform) {
    $type = 0;
    $platform = strtolower($platform);
    switch ($platform) {
        case 'android':
            $type = 1;
            break;
        case 'ios':
            $type = 2;
            break;
        case 'wx':
            $type = 3;
            break;
    }
    return $type;
}
function decrypt_app($encrypted, $k = 'T1a2O3T4o5N6g7S8e9C0R.E0', $i = '01234567') {
    $encrypted = base64_decode($encrypted);
    $key = str_pad($k, 24, '0');
    $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
    if ($i == '') {
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    } else {
        $iv = $i;
    }
    $ks = mcrypt_enc_get_key_size($td);
    @mcrypt_generic_init($td, $key, $iv);
    $decrypted = mdecrypt_generic($td, $encrypted);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $y = pkcs5_unpad($decrypted);
    return $y;
}

/**
 * 发送自定义的模板消息
 * @param $touser
 * @param $template_id
 * @param $url
 * @param $data
 * @param string $topcolor
 * @return bool
 */
function doSend($touser, $template_id, $url, $data,$token,$topcolor = '#7B68EE')
{
    $template = array(
        'touser' => $touser,
        'template_id' => $template_id,
        'url' => $url,
        'topcolor' => $topcolor,
        'data' => $data
    );
    $json_template = json_encode($template);
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
    $dataRes = request_post($url, urldecode($json_template));
    $dataRes = json_decode($dataRes);
    $dataRes = objarray_to_array($dataRes);
    if ($dataRes['errcode'] == 0) {
        return true;
    } else {
        return false;
    }
}


/*发送模板消息
 *@ param $touser 用户openId
 *@ param $template_id 模板ID
 *@ parma $data 数据
 */
function sendTemplateMessage($touser,$template_id,$data){
    //获取token
    $appId = C('WEIXINAPPID');
    $secret = C('WEIXINSECRET');
    $token = request_get("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secret);
    $token = json_decode($token);
    $token = objarray_to_array($token);
    $access_token = $token['access_token'];
    $info = $this->doSend($touser,$template_id,"",$data,$access_token);
    return $info;
}

/*
* 编辑消息
*@ $money 金额
*@ $server 服务
*@ $order 订单号
*@ $time 用户下单时间
*@ $uid 用户名
*@ $type 返回类型 1后台输入价格后发送给客户的模板消息 2用户缴费后发送给客户的信息 4用户0元预约时返回的消息
*/
function editTemplateMessage($money="",$server="",$order="",$time="",$uid="",$type=1){
    if($type==1){
        //返回提交消息
        $data = array(
            'first'=>array('value'=>urlencode("您好,您的订单已创建成功"),'color'=>"#030303"),
            'keyword1'=>array('value'=>urlencode($order),'color'=>"#030303"),
            'keyword2'=>array('value'=>urlencode($server),'color'=>"#030303"),
            'keyword3'=>array('value'=>urlencode($money),'color'=>"#030303"),
            //'remark'=>array("value"=>urlencode("您的订单已经提交成功，请及时付款。如有问题请直接在微信留言，我们将第一时间为您服务。"),'color'=>'#030303'),
        );
    }elseif($type==2){
        //返回支付成功消息
        $data = array(
            'first'=>array('value'=>urlencode("尊敬的客户,我司已收到您的款项"),'color'=>"#030303"),
            'keyword1'=>array('value'=>urlencode('杭州一棵松企业管理有限公司'),'color'=>"#030303"),
            'keyword3'=>array('value'=>urlencode($money),'color'=>"#030303"),
            /*'Remark'=>array("value"=>urlencode("您的订单我们已经收到，已为您安排出车。如有问题请直接在微信留言，我们将第一时间为您服务。"),'color'=>'#030303'),*/
        );
    }elseif($type==3){
        //提交订单返回给公众平台
        $data = array(
            'first'=>array('value'=>urlencode("您收到一条新的订单"),'color'=>"#030303"),
            'keynote1'=>array('value'=>urlencode($time),'color'=>"#030303"),
            'keynote2'=>array('value'=>urlencode('租车订单'),'color'=>"#030303"),
            'keynote3'=>array('value'=>urlencode($uid),'color'=>"#030303"),
            'keynote4'=>array('value'=>urlencode("兴趣车型:".$car),'color'=>"#030303"),
        );
    }elseif($type==4){
        //支付时返回给公众平台
        $data = array(
            'first'=>array('value'=>urlencode("尊敬的用户您好,您的 ".$server." 服务已0元预约成功"),'color'=>"#030303"),
            'keyword1'=>array('value'=>urlencode("杭州一棵松企业管理有限公司"),'color'=>"#030303"),
        );
    }
    return $data;
}
//生成系统时间，整形
function get_time($datetime,$t=1){
    if($datetime=='')
        return '';
    if ($t==1){
        return date('Y-m-d',$datetime);
    }else if($t==0){
        return date('Y-m-d H:i:s',$datetime);
    }else if ($t==2){
        return date('Y-m-d H',$datetime);
    }
}
//根据需求长度生成随机数
function sixRandNumber($number){
    $arr='';
    for($i=0;$i<$number;$i++){
        $range = mt_rand(0,9);
        $arr.=$range;
    }
    return $arr;
}
/*
 * 获得随机字符串
 */

function get_rand_str($length = 8) {
    $strarr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z'];
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $key = mt_rand(0, 34);
        $str .= $strarr[$key];
    }
    return $str;
}
/*
 * 获取IP
 */

function getIp() {
    $ip = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip_arr = explode(',', $ip);
    return $ip_arr[0];
}
/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree

    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = & $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    return $tree;
}
function curl_request($url, $post = '', $cookie = '', $returnCookie = 0, $json = false) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    if (substr($url, 0, strlen("https://")) == "https://") {
        // https请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    // curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if ($post) {
        if ($json) { //发送JSON数据
            $post = json_encode($post);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length:' . strlen($post)
                )
            );
        } else {
            $post = http_build_query($post);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        if (isset($matches[1][0])) {
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
        } else {
            $info['cookie'] = $matches[1];
            $info['content'] = $body;
        }
        return $info;
    } else {
        return $data;
    }
}
/*
 * 微信支付
 * @uid 用户ID
 * @ money 金额
 * @ orderid 订单ID
 * @type  1 APP支付 2 小程序支付
 */

function weixin_pay($uid, $money, $orderid, $type, $openid = '') {
    $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    $weixin = config('weixinpay');
    $weburl = config('web_url');
    Loader::import('pay.weixinpay.WxPayApi');
    Loader::import('pay.weixinpay.WxPay.Data');
    $input = new WxPayUnifiedOrder();
    if ($type == 1) {
        $input->SetAppid($weixin['app_id']);
        $input->SetMch_id($weixin['mch_id']);
    } else {
        $input->SetAppid($weixin['ot_app_id']);
        $input->SetMch_id($weixin['ot_mch_id']);
    }

    //判断测试模块
    $env = config('app_env');
    if ($env == 'dev') {
        $money = 0.01;
    }

    $noncestr = get_rand_str(20);
    $input->SetNonce_str($noncestr);
    $input->SetBody('一棵松平台支付');
    $outtradeno = $uid . time() . rand(100000, 999999);
    $input->SetOut_trade_no($outtradeno);
    $input->SetTotal_fee($money * 100);
    $input->SetSpbill_create_ip(getIp());
    //$input->SetNotify_url($weburl . url('Pay/weixinCallback'));
    $input->SetNotify_url("http://app.yikesong66.com/api/pay/weixinCallback");
    if ($type == 1) {
        $input->SetTrade_type('APP');
    } else {
        $input->SetTrade_type('JSAPI');
        $input->SetOpenid($openid);
    } //echo '<pre>';  var_dump($input);exit;
    $response = WxPayApi::unifiedOrder($input);//用的是extend目录
    //print_r( $response );
    if ($response['return_code']=='SUCCESS'){
        if ($response['result_code'] == 'SUCCESS') {
            //清空之前的参数
            $response = WxPayApi::twoSign($response);
            //添加支付记录
            $insert = array();
            $insert['uid'] = $uid;
            $insert['order_id'] = $orderid;
            $insert['out_trade_no'] = $outtradeno;
            $insert['money'] = $money;
            $insert['create_time'] = time();
            $insert['type'] = 2;
            $insert['status'] = 1;
            $insert['style'] = 1;
            $insert['sign'] = md5($response['sign']);//此处还需要md5吗
            $insert['user_ip'] = getIp();
            $res = db('pay_log')->insert($insert);

            $result = [];
            $result['appid'] = $weixin['app_id'];
            $result['partnerid'] = $weixin['mch_id'];
            $result['prepay_id'] = $response['prepay_id'];
            $result['noncestr'] = $response['nonce_str'];
            $result['timestamp'] = $response['timestamp'];
            $result['sign'] = $response['sign'];
            $result['package_str'] = 'Sign=WXpay';
//
            $response['partnerid'] = $weixin['mch_id'];
            //print_r( $response );exit;
            //echo '<pre>';  var_dump($response); exit;
            return $response;
        }
    }
    return false;
}
/*极光消息推送*/
function jpush($registrarion_id,$message){
    $app_key=config('jpush.app_key');
    $master_secret=config('jpush.master_secret');
    $client=new \JPush\Client($app_key,$master_secret,null);
    $pusher = $client->push()->setPlatform('all')->addRegistrationId($registrarion_id)
        ->setNotificationAlert($message)->send();
    /*$pusher->setPlatform('all');
    $pusher->addRegistrationId($registrarion_id);
    //$pusher->addAllAudience();
    $pusher->setNotificationAlert($message);
    $pusher->send();*/
   /* try {
        $pusher->send();
    } catch (\JPush\Exceptions\JPushException $e) {
        // try something else here
        print $e;
        Log::write('回调处理失败：'.$e->getMessage() , Log::ERROR);
        Log::write('回调处理文件：'.$e->getFile() , Log::ERROR);
        Log::write('回调处理行号：'.$e->getLine() , Log::ERROR);
    }*/
}

/**
 * 上传图片，base64，上传成功由服务器推到七牛服务器
 */
function save_base64_image($base64_img,$type=1) {
    set_time_limit(0);
    //$wwwroot = config('web_url');
    //$wwwroot = 'http://'.$_SERVER['HTTP_HOST'];
    $wwwroot = $_SERVER['DOCUMENT_ROOT'];
    $save_dir = $wwwroot . '/uploads/images';

    if (!preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
        return false;
    }
    $size = file_get_contents($base64_img);
    if (strlen($size) / 1024 > (2 * 1024)) {
        return false;
    }
    $save_dir .= '/' . date('Ymd');
    if (!file_exists($save_dir)) {
        mkdir($save_dir, 0777, true);
    }
    $type = $result[2];
    if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
        $file_name = md5(microtime(true)) . '.' . $type;
        $new_file = $save_dir . '/' . $file_name;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
            if ($type==1){
                return $new_file;
            }else{
                return date('Ymd').'/'.$file_name;
            }
        } else {
            return false;
        }
    } else {
        //文件类型错误
        return false;
    }
}
