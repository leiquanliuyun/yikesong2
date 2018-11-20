<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 生成随机字符串
 * @param $length 要生成的随机字符串长度
 * @param $type 0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
 * @return string
 */
function randCode($length = 5, $type = 0) {
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
 * 获取数据
 * @param $var string 参数名
 * @param $type string 声明未定义返回空字符
 * @param $default string 未定义返回的设置值
 * @return string
 */
function data_isset($var, $type = 'intval', $default = '') {
    if (isset($var)) {
        $return = $var;
//        if(empty($return)){
//            $return = $default;
//        }
        if (empty($return) && !is_numeric($var)) {
            $return = $default;
        }
    } else {

        if ($type == 'intval') {
            $return = 0;
        } elseif ($type == 'trim') {
            $return = '';
        }
        if ($default != '') {
            $return = $default;
        }
    }
    return $return;
}

/*
 * 获取IP地址
 */

function get_ip() {
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }
    return $ip;
}

/**
 * 微信小程序获取用户敏感数据 encryptedData 解密
 * @param  $encryptedData 'CiyLU1Aw2KjvrjMdj8YKliAjtP4gsMZM'  包括敏感数据在内的完整用户信息的加密数据
 * @param  $iv 'r7BXXKkLb8qrSNn05n0qiA=='  加密算法的初始向量
 * @param  $sessionKey 'SESSIONKEY'  会话密钥
 * @return array
 */
function encrypted_data($encryptedData, $iv, $sessionKey) {
    //引入微信小程序 解密算法
    require_once "../vendor/wechat_php/wxBizDataCrypt.php";
    //小程序appid
    $appid = 'wx71a4af77c1e33c49';
    //解密类
    $pc = new WXBizDataCrypt($appid, $sessionKey);
    //调用解密方法
    $errCode = $pc->decryptData($encryptedData, $iv, $data );
    //判断是否成功
    if ($errCode == 0) {
        return $data;
    } else {
        abort(501,"错误代码：".$errCode);
    }
}



/**
 *  数据导入
 * @param string $file excel文件,详细地址
 * @param string $sheet
 * @return string   返回解析数据
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function import_excel($file='', $sheet=0){
    $file = iconv("utf-8", "gb2312", $file);   //转码
    if(empty($file) OR !file_exists($file)) {
        die('file not exists!');
    }
    // 1.引入excel外部插件
    require_once('../vendor/phpexcel/phpexcel.php');
    //include('PHPExcel.php');  //引入PHP EXCEL类
    $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象
    if(!$objRead->canRead($file)){
        $objRead = new PHPExcel_Reader_Excel5();
        if(!$objRead->canRead($file)){
            die('No Excel!');
        }
    }

    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
    $obj = $objRead->load($file);  //建立excel对象
    $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表
    $columnH = $currSheet->getHighestColumn();   //取得最大的列号
    $columnCnt = array_search($columnH, $cellName);
    $rowCnt = $currSheet->getHighestRow();   //获取总行数

    $data = array();
    for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容
        for($_column=0; $_column<=$columnCnt; $_column++){
            $cellId = $cellName[$_column].$_row;
            $cellValue = $currSheet->getCell($cellId)->getValue();
            //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值
            if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串
                $cellValue = $cellValue->__toString();
            }

            $data[$_row][$cellName[$_column]] = $cellValue;
        }
    }
    //返回excel数据
    return $data;
}



/**
 * 发送SMTP邮件
 * @param $title '' 发送邮件标题
 * @param $content '' 发送邮件内容
 * @param $email '' 收件人邮箱地址
 * @return ''
 */
function send_email($title='',$content='',$email='') {
    //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
    require_once "../vendor/phpmail/class.phpmailer.php";
    require_once "../vendor/phpmail/class.smtp.php";
    //示例化PHPMailer核心类
    $mail = new PHPMailer();

    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;

    //使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
    //可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
    $mail->isSMTP();
    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth=true;
    //链接qq域名邮箱的服务器地址
    $mail->Host = "smtp.163.com";//SMTP服务器
    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';
    //设置ssl连接smtp服务器的远程服务器端口号 可选465或994
    $mail->Port = 465;
    //设置smtp的helo消息头 这个可有可无 内容任意
    //$mail->Helo = 'Hello smtp.qq.com Server';
    //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
    //$mail->Hostname = 'jjonline.cn';
    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'UTF-8';
    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '一棵松';
    //smtp登录的账号
    $mail->username ='yqaitec@163.com';
    //smtp授权码
    $mail->Password = 'yqaitec123456';      //授权码
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = 'yqaitec@163.com';
    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($email,'');
    //添加该邮件的主题
    $mail->Subject = $title;
    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;

    //发送命令 返回布尔值
    //PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
    $status = $mail->send();
    //返回数据
    return $status;
}



//聚合数据接口----发送短信
function sendMess($phone,$code,$tpl_id){
    header('content-type:text/html;charset=utf-8');

    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL

    $smsConf = array(
        'key'   => '71ffe860e8aa7d85720b588c422d1a70', //您申请的APPKEY
        'mobile'    => $phone, //接受短信的用户手机号码
        'tpl_id'    => $tpl_id, //您申请的短信模板ID，根据实际情况修改
        'tpl_value' =>'#code#='.$code //您设置的模板变量，根据实际情况修改
    );

    $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信

    if($content){
        $result = json_decode($content,true);
        $error_code = $result['error_code'];
        if($error_code == 0){
            //状态为0，说明短信发送成功
            return true;
            //echo "短信发送成功,短信ID：".$result['result']['sid'];
        }else{
            //状态非0，说明失败
            return false;
            /*$msg = $result['reason'];
            echo "短信发送失败(".$error_code.")：".$msg;*/
        }
    }else{
        //返回内容异常，以下可根据业务逻辑自行修改
        echo "请求发送短信失败";
    }
}


/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
/*function juhecurl($url,$params=false,$ispost=0){
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
}*/
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
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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

/**
 *  查询快递物流详情
 * @param numbers int '123456' 快递单号
 * @param no string 'sf'  快递公司编号
 *
 * @return array data   返回快递物流详情
 */
function juhe_exp($numbers=0, $no=''){
    // 1.引入聚合快递接口
    require_once('../vendor/juhe/exp.php');
    //设置编码
    header('Content-type:text/html;charset=utf-8');
    //封装配置参数
    $params = array(
        'key' => '8d4b2ec2709e0914cf1d1749e1fc4e5e', //您申请的快递appkey
        'com' => $no, //快递公司编码，可以通过$exp->getComs()获取支持的公司列表
        'no'  => $numbers //快递编号
    );
    //初始化类
    $exp = new exp($params['key']);
    //执行查询
    $result = $exp->query($params['com'],$params['no']);

    //判断是否查询成功
    if($result['error_code'] == 0){
        $list = $result['result']['list'];
        return $list;
    }else{
        abort(501,"获取失败，原因：".$result['reason']);
    }
}




/**
 *  后台查询快递物流详情
 * @param numbers int '123456' 快递单号
 * @param no string 'sf'  快递公司编号
 *
 * @return array data   返回快递物流详情
 */
function admin_juhe_exp($numbers=0, $no=''){
    // 1.引入聚合快递接口
    require_once('../vendor/juhe/exp.php');
    //设置编码
    header('Content-type:text/html;charset=utf-8');
    //封装配置参数
    $params = array(
        'key' => '8d4b2ec2709e0914cf1d1749e1fc4e5e', //您申请的快递appkey
        'com' => $no, //快递公司编码，可以通过$exp->getComs()获取支持的公司列表
        'no'  => $numbers //快递编号
    );
    //初始化类
    $exp = new exp($params['key']);
    //执行查询
    $result = $exp->query($params['com'],$params['no']);
    //返回数据
    return $result;
}



/**
 *  对图片进行base64解码输出
 * @param image_file '123.jpg' 图片
 *
 * @return array data   返图片base64
 */
function base64EncodeImage ($image_file) {
    $base64_image = '';
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    return $base64_image;
}



/**
 * get方式发送curl
 * @param  string $url [请求的URL地址]
 * @return array json
 */
function curl_get_https($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象
}