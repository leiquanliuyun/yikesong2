<?php
namespace app\common\model;
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
    const AUTH_VERIFY         = 'SMS_134970046';   // 身份验证
    const LOGIN_VERIFY        = 'SMS_134970045';   // 登录验证
    const REGISTER_VERIFY     = 'SMS_134970043';   // 注册验证
    const CHANGE_PWD_VERIFY   = 'SMS_134970041';   // 重置、修改密码验证
    const SHOP_VERIFY_SUCCESS = 'SMS_138062646';   // 开店成功通知
    const SHOP_VERIFY_FAIL    = 'SMS_138062649';   // 开店失败

    // 发送短信实现方法
    public static function send( $mobile,  $params , $tpl_code ){
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置短信接收号码
        $request->setPhoneNumbers( $mobile );
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName( config('alidayu.signature') );
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode( $tpl_code );
        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项// $params 短信模板中字段的值
        $request->setTemplateParam(json_encode( $params , JSON_UNESCAPED_UNICODE));
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        if( $acsResponse->Code == 'OK' ){
            return true;
        }
        return false;
    }

    /**
     * 取得AcsClient
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