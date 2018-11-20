<?php
namespace app\common\lib\call_phone;
/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/5/14
 * Time: 17:10
 */
class Youting{

    public $regphoneid = '';
    public $userid = '';
    public $user_pwd = '';
    public $token = '';

    public function __construct($config = [])
    {
        $this->regphoneid = $config['regphoneid'];
        $this->userid = $config['userid'];
        $this->user_pwd = $config['user_pwd'];
        $this->getToken();
    }


    /**
     * 获取优听token
     * @return void;
    */
    public function getToken(){
        $url = 'http://sp.umeeting.cn/httpservice/gettoken?';
        // regphoneid=REGPHONEID&userid=USERID&timestamp=TIMESTAMP&hashcode=HASHCODE
        $time = date('YmdHis');
        $hashcode = md5($this->regphoneid.$this->user_pwd.$time.$this->userid);
        $url .= 'regphoneid='.$this->regphoneid.'&userid='.$this->userid.'&timestamp='.$time.'&hashcode='.$hashcode;
        // exit;
        $remote = json_decode(file_get_contents( $url ) , true);
        if( isset( $remote['token'] ) && !empty($remote['token']) ){
            $this->token = $remote['token'];
        }
    }

    /**
     * 呼叫业务
     * @param $create_mobile string 发起方
     * @param $target_mobile string 被呼叫方
     * @return bool
    */
    public function call( $create_mobile , $target_mobile ){
        if( empty($this->token) ){
            return false;
        }
        $url = 'http://sp.umeeting.cn/httpservice/createconference?token='.$this->token;
        $data= [
            "meettitle"=>"家家爱",
            "meetname"=>"业务沟通",
            "meetcall"=> $create_mobile,
            "meetphone"=> $target_mobile,
            "phoneaddress"=>"发起人,接听方",
            "meetsmsflag"=>0,
            "meetrecflag"=>0,
            "autocallflag"=>0,
            "shutupflag"=>0,
            "meetduration"=>30
        ];
        $remote = curl_request($url , $data , '' ,'' , true );
        if( empty($remote) ){
            return false;
        }
        $remote = json_decode($remote , true);
        if( isset( $remote['conferenceid'] ) && !empty($remote['conferenceid']) ){
            return true;
        }
        return false;
    }
}