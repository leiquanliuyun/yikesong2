<?php
namespace app\common\lib\call_phone;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/8
 * Time: 9:48
 */
class TianZhou{

    private static $request_url = 'http://ifs.95013.com:8081/safenumberservicessm';

    private static function public_param(){
        $data = [];
        $data['ver'] = '2.0';
        $data['msgid'] = '6cac2d02-9b39-4b98-9d28-c12cf0dbb4cb';
        $data['ts'] = date('Y-m-d H:i:s');
        $data['service'] = 'SafeNumber';
        $data['appkey'] = config('tianzhou.appkey');
        $data['unitID'] = config('tianzhou.unitID');

        return $data;
    }

    /**
     * 号码绑定
     * @param $create_phone string 发起方号码
     * @param $target_phone string 拨打的电话那方
     * @param $order_sn     string 订单编号
     * @return void|string 返回false绑定失败
    */
    public static function bind( $create_phone , $target_phone , $order_sn = "" ){
        $url = self::$request_url.'/api/manage/dataManage?';
        $param = [];
        $param['msgtype'] = 'binding_Relation';
        $param['prtms']  = $create_phone;
        // 号码类别，0:安全号，1：岗位号
        $param['uidType'] = 0;
        $param['otherms'] = $target_phone;
        // 号码的有效期
        $param['validitytime'] = 1;
        $param['uuidinpartner'] = $order_sn;
        $param = array_merge(self::public_param() , $param);
        $param['sid'] = self::makeSign( $param );

        // $remote = file_get_contents($url.http_build_query(array_merge( self::public_param() , $param )));
        // echo $url.http_build_query($param);
        $remote = curl_request($url.http_build_query($param));

        $data = json_decode( $remote , true );
        if( isset( $data['binding_Relation_response'] ) && !empty($data['binding_Relation_response']['smbms']) ){
            return $data['binding_Relation_response']['smbms'];
        }
        return false;
    }

    /**
     * 号码解绑，使用订单编号或者安全号，两者选一个进行解绑;
     * @param $order_sn string 订单编号
     * @param $smbms string 安全号码
     * @return bool
    */
    public static function removeBind($order_sn = '' , $smbms = ''){
        $url = self::$request_url.'/api/manage/dataManage?';
        if( empty($order_sn) || empty($smbms) ){
            return false;
        }
        $param = [];
        // 使用订单号解绑
        !empty($order_sn) && $param['uuidinpartner'] = $order_sn;
        // 使用安全号码解绑
        !empty( $smbms ) && $param['smbms'] = $smbms;

        $param = array_merge(self::public_param() , $param);
        $param['msgtype'] = 'remove_Relation';
        $param['sid'] = self::makeSign( $param );

        $remote = curl_request($url.http_build_query($param));
        $data = json_decode($remote , true);
        if( isset( $data['binding_Relation_response'] ) ){
            return true;
        }
        return false;
    }

    private static function makeSign( $param ){

        //签名步骤一：按字典序排序参数
        ksort($param);
        //签名步骤二：拼接字符串
        $string = self::ToUrlParams($param);
        //签名步骤三：拼装的字符串前后加上app的secret
        $string = config('tianzhou.secret').$string.config('tianzhou.secret');
        //签名步骤四：MD5加密
        $result = md5($string);

        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    private static function ToUrlParams(Array $param)
    {
        $buff = "";
        foreach ($param as $k => $v)
        {
            $buff .= $k  . $v ;
        }
        return $buff;
    }

}