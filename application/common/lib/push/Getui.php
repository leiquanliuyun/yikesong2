<?php
namespace app\common\lib\push;
use app\common\model\UserData;
use think\Exception;
use think\Loader;


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/7
 * Time: 9:21
 */
class Getui{

    protected $app_id;
    protected $app_secret;
    protected $app_key;
    protected $master_secret;
    protected $push_url = 'http://sdk.open.api.igexin.com/apiex.htm';


    // 标题
    private $title;
    // 内容体
    private $content;
    // 传送的json
    private $ext = [];

    // appTypePush 推送参数；全部为数组类型
    // 平台类型推送
    private $platform;
    // 按省份推送
    private $province;
    // 按标签推送
    private $tag;
    // 按用户属性
    private $age;
    // 通道
    const CONSUMER_PUSH = 0;  //个人
    const MERCHANT_PUSH = 1;  //商家
    const SERVER_PUSH = 2;    //服务
    // 平台类型
    const PLATFORM_ANDROID = 1;
    const PLATFORM_IOS = 2;
    // 类的实例
    private static $_instance;
    // 透传类型 1:客户端执行操作，2：强制APP回到前台页面
    private $transmissionType = 1;

    public function __construct( $channel ){
        Loader::import('getui.IGt' , '' , '.Push.php');
        if( !in_array( $channel , [0,1,2] ) ){
            abort(500 , '推送通道错误！' );
        }
        $config_key = '';
        $app_env = config('app_env') == 'dev' ? 'dev' : 'product';

        $config = config('getui.'.$app_env);

        switch ( $channel ){
            case 0:
                $config = $config['consumer'];
                break;
            case 1:
                $config = $config['merchant'];
                break;
            case 2:
                $config = $config['server'];
                break;
        }



        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->app_key = $config['app_key'];
        $this->master_secret = $config['master_secret'];
    }

    /**
     * 静态方法构造类的实例
    */
    public static function channel( $channel=0 ){
        self::$_instance = new self($channel);
        return self::$_instance;
    }

    /**
     * 获取用户在线状态
    */
    public function getUserStatus($cid){
        $igt = new \IGeTui($this->push_url,$this->app_key,$this->master_secret);
        $rep = $igt->getClientIdStatus($this->app_id,$cid);
        var_dump($rep);
    }


    /**
     * 用户ID绑定别名
    */
    public function aliasBind( $user_id , $client_id ){
        $igt = new \IGeTui($this->push_url , $this->app_key , $this->master_secret);
        $rep = $igt->bindAlias($this->app_id , $user_id , $client_id);
        if( $rep['result'] == 'ok' ){
            return true;
        }
        return false;
    }

    /**
     * 用户ID与别名解绑，退出登录的时候用到
    */
    public function aliasUnBind($user_id , $client_id){
        $igt = new \IGeTui($this->push_url,$this->app_key,$this->master_secret);
        $rep = $igt->unBindAlias( $this->app_id , $user_id , $client_id);
        if( $rep['result'] == 'ok' ){
            return true;
        }
        return false;
    }


    /**
     * 设置推送的平台
     * @return Getui
    */
    public function set_platform( Array $platform ){
        $this->platform = $platform;
        return self::$_instance;
    }

    /**
     * 设置推送的省份
     * @return Getui
     */
    public function set_province( Array $province ){
        $this->province = $province;
        return self::$_instance;
    }

    /**
     * 设置标签推送
     * @return Getui
     */
    public function set_tag( Array $tag ){
        $this->tag = $tag;
        return self::$_instance;
    }

    /**
     * 设置按用户属性
     * @return Getui
     */
    public function set_age( Array $age ){
        $this->age = $age;
        return self::$_instance;
    }

    /**
     * 设备透传类型
     * @return Getui
    */
    public function set_transmissionType( $transmissionType ){
        $this->transmissionType = $transmissionType;
        return self::$_instance;
    }


    /**
     * 设置推送内容主题
     * @param $title string
     * @param $content string
     * @param $ext string   JSON字符串
     * @return Getui
    */
    public function setMessage( $title , $content  , $type , $data_id = 0 ){
        $this->title = $title;
        $this->content = $content;
        // $this->ext = $ext;
        $ext = [
            'type' => $type,
            'data_id' => $data_id
        ];
        return self::$_instance;
    }



    // 群推
    public function pushMessageToApp(){
        $igt = new \IGeTui($this->push_url,$this->app_key,$this->master_secret);
        //定义透传模板，设置透传内容，和收到消息是否立即启动启用
        $template = $this->IGtNotificationTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        // 定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);


        $appIdList = array($this->app_id);
        $message->set_appIdList($appIdList);
        // 开始设置推送的平台
        if( !is_null($this->platform) || !is_null( $this->province) || !is_null($this->tag) || !is_null( $this->age ) ){

            $cdt = new \AppConditions();
            if( !is_null($this->platform) ){
                $phoneTypeList = $this->platform;
                $cdt->addCondition(\AppConditions::PHONE_TYPE, $phoneTypeList);
            }
            if( !is_null( $this->province ) ){
                $provinceList = $this->province;
                $cdt->addCondition(\AppConditions::REGION, $provinceList);
            }
            if( !is_null($this->tag) ){
                $tagList = $this->tag;
                $cdt->addCondition(\AppConditions::TAG, $tagList);
            }
            if( !is_null( $this->age ) ){
                $age = $this->age;
                $cdt->addCondition("age", $age);
            }
            $message->set_conditions($cdt);
        }

        $rep = $igt->pushMessageToApp($message,"任务组名");

        if( $rep['result'] == 'ok' ){
            return true;
        }
        return false;
    }

    // 单推接口，使用client_id 来推送
    public function pushMessageToSingleByClientId( $client_id , $platform ){
        return $this->pushMessageToSingle( '' , $client_id , $platform );
    }


    //单推接口
    public function pushMessageToSingle( $user_id , $client_id = null , $platform = null )
    {
        Loader::import('getui.exception.RequestException');

        $igt = new \IGeTui($this->push_url, $this->app_key, $this->master_secret);

        //定义"SingleMessage"
        $message = new \IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        // $message->set_offlineExpireTime(3600*12*1000);//离线时间

        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->app_id);

        // 暂时不使用别名推送
        $client_arr = [];

        if( $client_id != null && $platform != null ){
            array_push( $client_arr , [
                'registration_id' => $client_id,
                'platform' => $platform,
            ]);
        }else if( !empty($user_id) ) {
            $client_arr = UserData::getGeTuiByUid( $user_id );
        }
        if( empty( $client_arr ) ){
            // 没有可推送的设备
            return false;
        }
        foreach ( $client_arr as $value ){
            //消息模版：
            if ( $value['platform'] == self::PLATFORM_IOS ) {
                $template = $this->IGtTransmissionTemplateDemo();
            } else if( $value['platform'] == self::PLATFORM_ANDROID ){
                $template = $this->IGtNotificationTemplateDemo();
            }else{
                $template = $this->IGtNotificationTemplateDemo();
            }
            if( empty($template) ){ continue; }
            $target->set_clientId($value['registration_id']);
            $message->set_data($template); //设置推送消息类型
            try {
                $rep = $igt->pushMessageToSingle($message, $target);
                // file_put_contents( './wechat_login.log' , 'cid:'.$value['registration_id'].'，'. json_encode( $rep ) );
                if( $rep['result'] == 'ok' ){

                }
            }catch(\RequestException $e){
                $requstId = e.getRequestId();
                //失败时重发
                $rep = $igt->pushMessageToSingle($message, $target,$requstId);
                if( $rep['result'] == 'ok' ){}
            }
        }
        return true;
    }


    private function IGtNotificationTemplateDemo(){
        Loader::import('getui.template.IGt' , '' , '.IGtNotificationTemplate.php');
        $template =  new \IGtNotificationTemplate();
        $template->set_appId($this->app_id);                      //应用appid
        $template->set_appkey( $this->app_key);                    //应用appkey
        $template->set_transmissionType($this->transmissionType);               //透传消息类型
        $template->set_transmissionContent( json_encode($this->ext) );   //透传内容
        $template->set_title($this->title);                     //通知栏标题
        $template->set_text($this->content);        //通知栏内容
        $template->set_logo("push.png");                  //通知栏logo
        $template->set_logoURL("https://api.51jja.cn/static/images/push.png"); //通知栏logo链接
        $template->set_isRing(true);                      //是否响铃
        $template->set_isVibrate(true);                   //是否震动
        $template->set_isClearable(true);                 //通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        // APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg=new \DictionaryAlertMsg();
        $alertmsg->body=$this->content;
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
        // iOS8.2 支持
        $alertmsg->title=$this->title;
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        $apn->add_customMsg("payload",$this->ext);
        // $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";

        $template->set_apnInfo($apn);

        return $template;
    }



    // 透传消息模板
    private function IGtTransmissionTemplateDemo(){
        Loader::import('getui.template.IGt' , '' , '.TransmissionTemplate.php');
        Loader::import('getui.igetui' , '' , '.IGt.APNPayload');
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId($this->app_id);                   //应用appid
        $template->set_appkey($this->app_key);                 //应用appkey
        $template->set_transmissionType( $this->transmissionType );            //透传消息类型
        // 使用标准的透传
        // ['title'=>'sdfdsff','content'=>'sdffs','payload'=>['type'=>2,'data_id'=>1]]
        $template->set_transmissionContent($this->getMessage());//透传内容


        // APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg=new \DictionaryAlertMsg();
        $alertmsg->body=$this->content;
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
        // iOS8.2 支持
        $alertmsg->title=$this->title;
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        $apn->add_customMsg("payload",$this->ext);
        // $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";

        $template->set_apnInfo($apn);

        return $template;
    }

    // 使用透传消息的模板才用到；
    private function getMessage(){
        return json_encode([
            'title' => $this->title,
            'content' => $this->content,
            'payload' => $this->ext
        ]);
    }

}