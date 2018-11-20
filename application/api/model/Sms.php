<?php
namespace app\api\model;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;

/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/4/26
 * Time: 17:02
 * 发送短信系统类，提供给验证码、推广广告使用
 */
class Sms{

    static $acsClient = null;
    // 短信模板
    const AUTH_VERIFY         = '114574';   // 身份验证（换绑手机号，绑定第三方）
    const LOGIN_VERIFY        = '114570';   // 登录验证
    const REGISTER_VERIFY     = '114563';   // 注册验证
    const AUTH_LOGIN_BIND_PHONE   = '114572';   // 第三方登录绑定手机号


    /*
      ***聚合数据（JUHE.CN）短信API服务接口PHP请求示例源码
      ***DATE:2015-05-25
    */
    public static function send( $mobile,  $params , $tpl_code ){
        //header('content-type:text/html;charset=utf-8');
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
        $tpl_vlaue='';
        foreach ($params as $k=>$v){
            $tpl_vlaue.='#'.$k.'#='.$v;
        }
        $smsConf = array(
            'key'   => \config('juhe.sms_appkey'), //您申请的APPKEY
            'mobile'    => $mobile, //接受短信的用户手机号码
            'tpl_id'    => $tpl_code, //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>$tpl_vlaue //您设置的模板变量，根据实际情况修改
        );

        $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信

        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                //echo "短信发送成功,短信ID：".$result['result']['sid'];
                return true;
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                //echo "短信发送失败(".$error_code.")：".$msg;
                return false;
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            //echo "请求发送短信失败";
            return false;
        }
    }

    /**
     * 取得AcsClient，阿里云的，不用
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        // 加载区域结点配置
        Config::load();
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = config('alidayu.app_key'); // AccessKeyId

        $accessKeySecret = config('alidayu.app_secret'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
}