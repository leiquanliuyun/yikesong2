<?php
namespace app\api\controller;

use app\api\model\Housecontent;
use app\api\model\Message;
use app\api\model\Servicecontent;
use app\api\model\Companycontent;
use app\api\model\Pact;
use app\api\model\PactOption;
use app\api\model\PactTemp;
use app\api\model\Order;
use app\api\model\Servicejd;
use app\common\lib\juhe\IdCard;
//use app\common\lib\lbs\Geohash;
use app\common\model\SmsCode;
use app\api\model\Member;
use app\api\model\Demand;
use app\api\model\UserAccount;
use app\api\model\Collect;
use app\api\model\Coupon;
use app\api\model\UserData;
use app\api\model\MemberCoupon;
use app\api\model\Withdraworder;
use app\api\model\IntellProperty;
use app\common\lib\push\Getui;
/**
 * Created by PhpStorm.
 * User: leiquan
 * Date: 2018/8/13
 * Time: 16:58
 *
 */
class MemberController extends CommonController
{

    /**
     * @var integer 当前登录的用户ID
     */
    protected $user_id;
    /**
     * @var \app\common\model\User User 实例
     */
    protected $user;

    /**
     * TP构造函数，当前控制器都用到，必须使用登录才能操作
     */
    public function _initialize()
    {
        $this->user_id = $this->check_token();
        $this->user = Member::get($this->user_id);
    }
    // 退出登录
    public function logout() {
        $token = data_isset($this->get_post('token'), 'intval', "");
        if (!empty($token)) {
            $user_data = UserData::where('token', $token)->find();
            if (!empty($user_data)) {
                /*if (!empty($user_data['registration_id'])) {
                    $user_type = 0;
                    Getui::channel($user_type)->aliasUnBind($user_data->uid, $user_data->registration_id);
                }*/
                $user_data->delete();
            }
        }
        $result = array('status' => 1000, 'info' => 'OK');
        parent::put_post($result);
    }
    /*消息列表（推送消息）*/
    public function message(){
        //$registration_id = data_isset($this->get_post('registration_id'), 'trim', "");
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $uid = $this->user_id;
        $registration_id=db('user_data')->where(['uid'=>$uid,'type'=>1])->value('registration_id');
        $message=new Message();//print_r($registration_id);
        $message_list=$message->where(['registration_id'=>$registration_id])->order('create_time desc')
            ->field('msg_id,title,content,create_time')->select();
        $data=['message_list'=>$message_list];
        parent::put_post(['status'=>1000 , 'info'=>'OK' , 'data'=>$data]);
    }
    /*获取用户信息（必须在登录之后）*/
    public function getInfo(){
        $uid = $this->user_id;
        $user=new Member();
        $info=$user->where('id',$uid)
            ->field('id as uid,nickname,realname,mobile,picture,integral,sex')->find();
        /*if( empty($info['nickname']) ){
            $info['nickname'] = 'yks_'.$info['mobile'];
        }
        if( empty($userInfo['picture']) ){
            $userInfo['picture'] = config('web_url').'/static/images/logo.jpg';
        }*/
        $status=$info['realname']?1:0;//1实名0未实名
        $membercoupon=new MemberCoupon();
        $nowtime=get_time(time(),0);
        $rs=db('user_account')->where('uid',$uid)->find();//是否绑定支付宝
        $is_bind=$rs?1:0;
        //是否绑定第三方登录（微信）
        $result=db('user_bind')->where('uid',$uid)->find();
        $is_bind_wechat=$result?1:0;
        $count=$membercoupon->where(['uid'=>$uid,'status'=>1])->where('end_time','>=',$nowtime)->count();
        $data=['info'=>$info,'count'=>$count,'status'=>$status,'is_bind'=>$is_bind,'is_bind_wechat'=>$is_bind_wechat];
        parent::put_post(['status'=>1000 , 'info'=>'success' , 'data'=>$data]);
    }
    /**
     * 修改用户资料，此处需要保存完整用户头像
     */
    public function saveInfo(){
        $nickname = data_isset($this->get_post('nickname'),'trim',"");
        $avatar = data_isset($this->get_post('avatar'),'trim',"");

        $member = new Member();
        $member_info = $member->where('id' , $this->user_id)->find();
        /*if( empty($member_info) ){
            // 还没填写过资料的用户
            $member_info = $model;
            $member_info->id = $this->user_id;
        }*/
        //此处用模型或函数都可以,只有在修改头像时才去调上传base64图像接口
        if ($avatar){
            $avatar=parent::save_base64_image($avatar);
            //$avatar=save_base64_image($avatar);
            $web_url=config('web_url');
            $avatar=$web_url."/uploads/images/".$avatar;
        } //168.yikesong66.com/index/content/consult_list/id/1/type/0.html
        !empty($nickname) && $member_info->nickname = $nickname;
        !empty($avatar)   && $member_info->picture   = $avatar;

        if( $member_info->save() !== false ){
            parent::put_post(['status'=>1000 , 'info'=>'修改成功！']);
        }
        parent::put_post(['status'=>1004 , 'info'=>'保存失败！' ]);
    }
    /**
     * 更换手机号码绑定 此接口仅修改当前登录用户的手机号，不影响登录
     */
    public function changeMobile(){
        $uid = $this->user_id;
        $old_sms_code = data_isset($this->get_post('old_sms_code'),'trim',"");
        $mobile = data_isset($this->get_post('mobile'),'trim',"");
        $old_mobile = data_isset($this->get_post('old_mobile'),'trim',"");
        $sms_code = data_isset($this->get_post('sms_code'),'trim',"");
        $user = $this->user;
        //此处两个验证码类型一样，因为验证码的唯一标识是手机号加验证码类型，此处两手机号肯定不一样，旧手机号验证码调给当前登录手机号发送验证码的接口
        if( !SmsCode::checkSmsCode($user->mobile , SmsCode::CHANGE_MOBILE , $old_sms_code) ){
            parent::put_post(['status'=>1004 , 'info'=>'旧手机验证码有误！']);
        }
        if( !SmsCode::checkSmsCode($mobile , SmsCode::CHANGE_MOBILE , $sms_code) ){
            parent::put_post(['status'=>1004 , 'info'=>'新手机验证码有误！']);
        }
        $rs = Member::getUserByPhone($mobile);
        if ($rs instanceof Member) {
            parent::put_post(['status'=>1004 , 'info'=>'该手机号已被绑定！']);
        }
        $user->startTrans();
        $user->mobile = $mobile;
        $res = $user->save();
        if( $res !== false ){
            $user->commit();
            // 删除短信验证码
            SmsCode::deleteSmsCode($mobile , SmsCode::CHANGE_MOBILE);
            SmsCode::deleteSmsCode($old_mobile , SmsCode::CHANGE_MOBILE);
            parent::put_post(['status'=>1000 , 'info'=>'修改成功！']);
        }
        $user->rollback();
        parent::put_post(['status'=>1004 , 'info'=>'绑定失败！']);
    }
    /**
     * 获取实名状态
     */
    public function getRealAuth(){
        //halt($this->user);
        $member = new Member();
        $member->id=$this->user_id ;
        $auth=$member->getRealAuth();
        if( !empty($auth) ){
            if($auth['realname']){
                $auth['real_name_status'] = 1;
                $auth['id_photo']= explode("##",$auth['id_photo']);
                /*$web_url=config('web_url');
                foreach ($rs as $key=>$val) {
                    $rs[$key]= $web_url.__PUBLIC__."/uploads/images/".$val;
                }
                $auth['id_photo']=$rs;*/
            }else{
                $auth['real_name_status'] =0;
            }
        }else{
            $auth = [
                'realname' => '',
                'card_num' => '',
                'sex'=>$auth['sex'],
                'id_photo'=>'',
                'real_name_status' => 0
            ];
        }
        parent::put_post(['status'=>1000 , 'info'=>'OK' , 'data'=> $auth]);
    }
    /**
     * 设置实名认证
     */
    public function setRealNameAuth(){
        $realname = data_isset($this->get_post('realname'),'trim',"");
        $card_num = data_isset($this->get_post('card_num'),'trim',"");
        $sex = data_isset($this->get_post('sex'),'intval',"");
        $id_photo = data_isset($this->get_post('id_photo'),'trim',"");
        if( config('app_env') != 'dev' ){
            if( !IdCard::idCardVerify($realname , $card_num) ){
                parent::put_post(['status'=>1004 , 'info'=>'姓名和身份证不匹配！']);
            }
        }
        $rs= explode("##",$id_photo);
        $web_url=config('web_url');
        foreach ($rs as $key=>$val) {
            $r=parent::save_base64_image($val);
            $rs[$key]=$web_url.__PUBLIC__."/uploads/images/".$r;
        }
        $id_photo=implode('##',$rs);
        $user = new Member();
        $info = $user->where('id' , $this->user_id)->field('realname,card_num,id_photo,sex')->find();
//        if( $info->real_name_status == 1 ){
//            parent::put_post(['status'=>1004 , 'info'=>'您已经实名认证，请勿重复提交！']);
//        }
        $info->realname = $realname;
        $info->card_num = $card_num;
        $info->sex = $sex;
        $info->id_photo = $id_photo;
        //$info->real_name_status = 1;
        if( $info->save() !== false ){
            parent::put_post(['status'=>1000 , 'info'=>'您的实名认证已成功！']);
        }
        parent::put_post(['status'=>1004 , 'info'=>'提交失败']);
    }
    // 绑定第三方账号账号(非登录)，用于转账提现,保证绑定对象是正在登陆的手机号
    public function setBindAccount(){
        $uid = $this->user_id;
        $account = data_isset($this->get_post('account'),'trim',"");
        $realname = data_isset($this->get_post('realname'),'trim',"");
        $type = data_isset($this->get_post('type'),'intval',"");
        $sms_code = data_isset($this->get_post('sms_code'),'trim',"");

        if( !SmsCode::checkSmsCode($this->user->mobile , SmsCode::BIND_USER_ACCOUNT , $sms_code) ){
            parent::put_post(['status'=>1004 , 'info'=>'短信验证码错误！']);
        }
        $user=new Member();
        $userinfo=$user->where('id',$uid)->field('realname')->find();
        if (empty($userinfo)){
            parent::put_post(['status'=>1004 , 'info'=>'请先实名认证！']);
        }
        if ($userinfo['realname']!=$realname){
            parent::put_post(['status'=>1004 , 'info'=>'姓名须与实名认证的姓名一致！']);
        }
        $user_account = new UserAccount();
        $rst = $user_account->where('uid',$uid)->where('type' , $type)->find();
        //每种账号只能绑定一个
        if( empty( $rst ) ){
            // 新增；
            $rst = $user_account;
            $rst->type = $type;
            $rst->uid = $uid;
        }
        $rst->account = $account;
        $rst->realname = $realname;
        if($rst->save()){
            SmsCode::deleteSmsCode( $this->user->mobile , SmsCode::BIND_USER_ACCOUNT );
            parent::put_post(['status'=>1000 , 'info'=>'绑定成功！']);
        }
        parent::put_post(['status'=>1004 , 'info'=>'绑定失败！']);
    }
    //分享页面显示
    /*路径问题
    *$_SERVER['SCRIPT_NAME']是当前脚本相对于项目根目录的相对路径，本地其值为/yikesong/public/index.php由于项目在本地与服务器的部署不同
     * ，项目的根目录就不同
    */
    public function share(){
        $uid = $this->user_id;
        $pid=strtolower(base64_encode($uid));
        $link_path = 'http://app.yikesong66.com/api/center/register/id/'.$pid;//上线后要改的
        $data=[];
        $data['link_path']=$link_path;
        $data['pid']=$pid;
        $user=new Member();
        $userInfo=$user->where('id',$uid)->field('qrcode')->find();
        if(!$userInfo['qrcode']){
            //生成二维码
            vendor("phpqrcode.phpqrcode");
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
            $date=date('Y-m-d');
            $qrcode_path = __PUBLIC__.'/uploads/qrcode/'.$date.'/';//相对于入口文件的相对路径
            //echo __PUBLIC__;echo '<pr>';
            //echo  $_SERVER['SCRIPT_NAME'];
            if (!file_exists( $_SERVER['DOCUMENT_ROOT'].$qrcode_path)){
                mkdir($_SERVER['DOCUMENT_ROOT'].$qrcode_path,0777,true);
            }
            // 生成的文件名
            $fileName = $qrcode_path.time().'_'.$pid.'.png';
            \Qrcode::png($link_path, $_SERVER['DOCUMENT_ROOT'].$fileName, $level, $size);
            $userInfo->qrcode=config('web_url').$fileName;
            //$userInfo->save();
            $data['qrcode_path']=$userInfo->qrcode;
        }else{
            $data['qrcode_path']=$userInfo['qrcode'];
        }
        parent::put_post(['status'=>1000 , 'info'=>'OK' , 'data'=> $data]);
    }
    //服务咨询页面显示
    public function consult(){
        $uid = $this->user_id;
        $consult_qrcode=cache('consult_qrcode');
        //cache('consult_qrcode',null);
        $link_path='https://www.baidu.com';

        if (empty($consult_qrcode)){
            $qrcode=vendor("phpqrcode.phpqrcode");
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
            $rand=uniqid().'consult_qrcode';
            $qrcode_path = '/uploads/images/share/'.$rand.'.png';//相对于入口文件的相对路径
            \Qrcode::png($link_path, '.'.$qrcode_path, $level, $size);
            $consult_qrcode=config('web_url').$qrcode_path;
            cache('consult_qrcode',$consult_qrcode);
        }
        //不要生成，直接给二维码图片地址
        $consult_qrcode=config('web_url').'/uploads/images/share/consult_qrcode.png';
        $data=['consult_qrcode'=>$consult_qrcode,'phone'=>'18806526183'];
        parent::put_post(['status'=>1000 , 'info'=>'OK' , 'data'=> $data]);
    }
    /*公司注册前查询名字是否可以用,如果查询记录显示不可用，则直接返回不可用，否则调用聚合接口查询*/
    public function inquire(){
        $uid=$this->user_id;
        $keyword = data_isset($this->get_post('keyword'),'trim',"");//公司名
        $mobile = data_isset($this->get_post('mobile'),'trim',"");
        if (empty($keyword) || empty($mobile) || strlen($keyword)<5){
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $inquire=model('inquire');
        $inquire_info=db('inquire')->where(['keyword'=>$keyword])->order('create_time desc')->field('is_able,create_time')->find();
        if ($inquire_info && $inquire_info['is_able']==0){
            $inquire->is_able=0;
        }else{
            $url = "http://japi.juhe.cn/enterprise/simpleList";
            $params = array(
                "key" => config('juhe.company_appkey'),//您申请的appKey
                "keyword" => $keyword//待搜索的关键词
            );
            $paramstring = http_build_query($params);
            $content = juhecurl($url,$paramstring);
            $result = json_decode($content,true);
            if($result){
                if($result['error_code']=='0'){
                    $data=$result['result']['data'];
                    if ($data['total']>0){
                        $inquire->is_able=0;
                    }else{
                        $inquire->is_able=1;
                    }
                }else{
                    $inquire->is_able=1;
                    //$data= $result['error_code'].":".$result['reason'];
                    //parent::put_post(['status' => 1004, 'info' => $data]);
                }
            }else{
                parent::put_post(['status' => 1003, 'info' => '请求失败']);
            }
        }
        $inquire->uid=$uid;
        $inquire->keyword=$keyword;
        $inquire->mobile=$mobile;
        $inquireInfo=$inquire->where(['keyword'=>$keyword,'mobile'=>$mobile,'uid'=>$uid])->find();
        if ($inquireInfo){
            $inquireInfo->is_able=$inquire->is_able;
            $inquireInfo->save();
        }else{
            $inquire->save();
        }
        $data['is_able']=$inquire->is_able;
        //$data['result']=$result;
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //添加收藏，取消收藏直接删除记录（软删除时再收藏操作会比较麻烦）
    public function collect(){
        $uid = $this->user_id;
        $name = data_isset($this->get_post('name'),'trim',"");//服务分类表
        $service_id = data_isset($this->get_post('service_id'),'intval',"");
        $type = data_isset($this->get_post('type'),'intval',"");//1收藏，0取消收藏
        if (empty($name)|| !is_numeric($service_id) || !is_numeric($type)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        switch($name){
            case "资质转让":
                $model='Companycontent';
                break;
            case "园区服务":
                $model='Housecontent';
                break;
            default:
                $model='Servicecontent';
                break;
        }
        $collect=new Collect();
        $info=$collect->where(['mod'=>$model,'uid'=>$uid,'service_id'=>$service_id])->find();//halt($info);
        if ($type==1){
            if ($info){
                parent::put_post(['status'=>1005 , 'info'=>'已收藏过了哦!']);
            }
            $info=$collect;
            $info->mod=$model;
            $info->service_id=$service_id;
            $info->uid=$uid;
            $info->status=1;
            if($info->isUpdate(false)->save()){
                parent::put_post(['status'=>1000 , 'info'=>'收藏成功!']);
            }
        }else{
            if (!$info){
                parent::put_post(['status'=>1005 , 'info'=>'该产品还未收藏!']);
            }
            //$info->status=0;
            if ($info->delete()){
                parent::put_post(['status'=>1000 , 'info'=>'取消收藏成功!']);
            }
        }
        parent::put_post(['status'=>1004 , 'info'=>'操作失败!']);
    }
    /*收藏列表*/
    public function collect_list(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $collect=new Collect();
        $count=$collect->where('uid',$uid)->count();
        $info=$collect->where(['uid'=>$uid])->order('create_time','desc')->page($page,$pagesize)->select();
        $collec_info = array();//获取收藏项的详细内容
        if ($info){
            foreach($info as $k=>$v){
                $model=model($v['mod']);
                $mess=$model->where('content_id',$v['service_id'])
                    ->find();//echo $model->getLastSql();
                //用于点击跳转到详情页
                $collec_info[$k]['content_id']=$mess['content_id'];
                if (isset($mess['service'])){
                    $collec_info[$k]['service_name']= $mess['service'];
                }else if ($v['mod']=="Housecontent"){
                    $collec_info[$k]['service_name']= '园区服务';
                    $collec_info[$k]['house_price']=$mess['price'];
                }else if ($v['mod']=="Companycontent"){
                    $collec_info[$k]['service_name']= '资质转让';
                    $collec_info[$k]['found_time']=$mess['found_time'];
                    $collec_info[$k]['company_price']=$mess['price'];
                }
               // $collec_info[$k]['service_name']=isset($mess['service'])?$mess['service']:$v['mod']=="Housecontent"?'园区服务':'资质转让';
                $collec_info[$k]['collect_id'] = $v['id'];//用于取消收藏
                $collec_info[$k]['picture']=$mess['picture_show'][0];
                $collec_info[$k]['name'] = isset($mess['service'])?$mess['service']:$mess['name'];
                if (isset($mess['filter_id'])){
                    $filter_id=json_decode($mess['filter_id'],true);
                    $collec_info[$k]['filter'] =db('filter')->where('id','in',$filter_id)->limit(3)->column('condition');
                }else{
                    $collec_info[$k]['filter']=[];
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' => ['count' => $count,'collec_info'=>$collec_info]]);
    }
    /*取消收藏 可一次取消多个*/
    public function cancel_collect(){
        $uid = $this->user_id;
        $collect_id = data_isset($this->get_post('collect_id'),'intval',"");
        if (empty($collect_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }

        $collect_id=json_decode($collect_id,true);
        $collect=new Collect();
        foreach ($collect_id as $k=>$v){
            $info=$collect->where('id',$v)->field('id')->find();
            if (!$info){
                parent::put_post(['status'=>1004 , 'info'=>'参数有误!']);
            }
            if (!$info->delete()){
                parent::put_post(['status'=>1004 , 'info'=>'操作失败!']);
            }
        }
        parent::put_post(['status'=>1000 , 'info'=>'取消收藏成功!']);
    }
    /*优惠券列表  包括待领取，已领取,不分页查询(两种不同查询结果待领取，已领取综合在一起，如何分页？)*/
    public function coupon_list(){
        $uid = $this->user_id;
        //$page = data_isset($this->get_post('page'),'intval',1);
        //$pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        //$status = data_isset($this->get_post('status'),'intval',1);//1优惠券2历史记录
        /*if (!is_numeric($status)){
            parent::put_post(array('status'=>1003,'info'=>'缺少必要参数'));
        }*/
        $nowtime=get_time(time(),2);
        //获取用户未使用优惠券数
        $membercoupon=new MemberCoupon();
        //$count=$membercoupon->where(['uid'=>$uid,'status'=>1])->where('end_time','>=',$nowtime)->where('start_time','<=',$nowtime)->count();
        //获取用户已使用,已过期优惠券数
        $use_count=$membercoupon->where('uid',$uid)->where('status',2)
            ->whereor('end_time','<=',$nowtime)->count();
        //获取用户已领取优惠券
        $count_list=$membercoupon->where(['uid'=>$uid])->column('coupon_id');
        $where=[];
        if ($count_list){
            $where['coupon_id']=['not in',$count_list];
        }
        //获取可领取优惠券
        $coupon=new Coupon();
        $coupon_info=$coupon->where(['status'=>1,'is_assign'=>0])->where('num','>',0)
            ->where('end_time','>=',$nowtime)->where('start_time','<=',$nowtime)->where($where)
            ->field('0 as member_coupon_id,coupon_id,name,service_name,price,fullprice,start_time,end_time')->select()->toArray();
        //echo $coupon->getLastSql();exit;
        //获取可使用（已领，未用，未过期）
        $membercoupon_info=$membercoupon->alias('a')->join('cmf_coupon b','a.coupon_id=b.coupon_id')
            ->where(['a.uid'=>$uid,'a.status'=>1])->where('b.end_time','>=',$nowtime)
            ->field('a.member_coupon_id,a.coupon_id,b.service_name,b.name,b.price,b.fullprice,a.start_time,a.end_time')
            ->order('a.create_time')->select()->toArray();
        $coupon_info = $coupon_info ? $coupon_info : [];
        $membercoupon_info = $membercoupon_info ? $membercoupon_info : [];
        $info=array_merge($coupon_info,$membercoupon_info);
        $count=count($info);
        if ($info){
            foreach($info as $k=>$v){
                if ($v['member_coupon_id']==0){
                    $info[$k]['url']='';
                    continue;
                }
                switch($v['service_name']){
                    case "公司注册":
                        $info[$k]['url'] = 'http://app.yikesong66.com/api/index/services_company';
                        //$info[$k]['url'] =url('api/index/services_company');
                        break;
                    case "资质转让":
                        $info[$k]['url'] = 'http://app.yikesong66.com/api/index/company_detail';
                        break;
                    case "天猫转让":
                        $info[$k]['url'] = 'http://app.yikesong66.com/api/index/services_company';
                        break;
                    case "租房找房":
                        $info[$k]['url'] = 'http://app.yikesong66.com/api/index/house_detail';
                        break;
                    default:
                        $info[$k]['url'] = 'http://app.yikesong66.com/api/index/services_detail';
                        break;
                }
            }
        }//halt($membercoupon_info);
        /*if ($coupon_info){//当券可领取多次时这样处理
            if ($membercoupon_info){
                foreach ($coupon_info as $k=>$v){
                    $n=0;
                    foreach ($membercoupon_info as $key=>$val){
                        if ($v['coupon_id']==$val['coupon_id']){
                            $n++;
                        }
                    }
                    if ($n>=$v['frequency']){
                        unset($coupon_info[$k]);
                    }
                }
                $coupon_info=array_values($coupon_info);//可领取优惠券
            }
        }
        */

        $data=['count'=>$count,'use_count'=>$use_count,'info'=>$info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    /*历史记录（已使用，已过期优惠券列表)*/
    public function used_coupon_list(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $nowtime=get_time(time(),2);
        //获取用户未使用优惠券数
        $membercoupon=new MemberCoupon();
        $info=$membercoupon->alias('a')->join('cmf_coupon b','a.coupon_id=b.coupon_id')
            ->where(['a.uid'=>$uid,'a.status'=>2])
            ->whereOr(function($query)use($uid,$nowtime){$query->where(['a.uid'=>$uid])->where('a.end_time','<=',$nowtime);})
            ->field('a.status,a.use_time,b.name,b.price,b.fullprice,a.start_time,a.end_time')
            ->order('a.create_time desc')->page($page,$pagesize)->select();//echo $membercoupon->getLastSql();exit;
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$info]);
    }
    /*可领取优惠券列表,已作废*/
    public function coupon_can_received(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        //获取用户已领取优惠券，要限制今天吗？
        $membercoupon=new MemberCoupon();
        $todaytime=get_time(time(),1);
        $tomorrowtime=get_time(time()+86400,1);
        $count_list=$membercoupon->where(['uid'=>$uid])->where('create_time','between',[$todaytime,$tomorrowtime])->column('coupon_id');
        //获取可领取优惠券
        $coupon=new Coupon();
        $info=$coupon->where('status',1)->where('num','>',0)
            ->where('coupon_id','not in',$count_list)
            ->order('create_time')->page($page,$pagesize)->select();//echo $coupon->getLastSql();exit;
        $data=$info;
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    /*领取优惠券*/
    public function coupon_receive(){
        $uid = $this->user_id;
        $coupon_id = data_isset($this->get_post('coupon_id'),'intval','');
        if (!is_numeric($coupon_id)){
            parent::put_post(array('status'=>1003,'info'=>'缺少必要参数'));
        }
        //判断该券是否可领，该券是否存在，是否已领
        $membercoupon=new MemberCoupon();
        //$todaytime=get_time(time(),1);
        //$tomorrowtime=get_time(time()+86400,1);
        $couponInfo=$membercoupon->where(['uid'=>$uid,'coupon_id'=>$coupon_id])->find();
        if ($couponInfo){
            parent::put_post(array('status'=>1004,'info'=>'该券已领取，无法再领取'));
        }
        $coupon=new Coupon();
        $info=$coupon->where(['coupon_id'=>$coupon_id,'status'=>1])->find();
        if (!$info || $info['num']<0){
            parent::put_post(array('status'=>1004,'info'=>'该券不存在'));
        }
        //领取优惠券
        $membercoupon->uid=$uid;
        $membercoupon->coupon_id=$coupon_id;
        //$endTime = strtotime("+".$info['useday']."days");
        $membercoupon->end_time=$info['end_time'];
        $membercoupon->start_time=$info['start_time'];
        $membercoupon->startTrans();//halt($info);
        try{//仅仅这个模型会回滚，其他若将更新数量放在前面，如果添加出错，数量仍会正常更新
            $membercoupon->save();//echo $membercoupon->getLastSql();exit;
            $data=[];
            $data['coupon_id']=$membercoupon->member_coupon_id;
            switch($info['service_name']){
                case "公司注册":
                    $data['url'] = 'http://app.yikesong66.com/api/index/services_company';
                    break;
                case "资质转让":
                    $data['url'] = 'http://app.yikesong66.com/api/index/company_detail';
                    break;
                case "天猫转让":
                    $data['url'] = 'http://app.yikesong66.com/api/index/services_company';
                    break;
                case "租房找房":
                    $data['url'] = 'http://app.yikesong66.com/api/index/house_detail';
                    break;
                default:
                    $data['url'] = 'http://app.yikesong66.com/api/index/services_detail';
                    break;
            }
            $coupon->where('coupon_id',$coupon_id)->setDec('num');
            $membercoupon->commit();
        } catch (Exception $e) {
            $membercoupon->rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //我的积分收入明细 如果所属服务未设置返分比例，该订单此处就不用考虑
    public function integral_detail(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $user=new Member();
        $userInfo=$user->where('id',$uid)->field('code')->find();
        $order=new Order();
        $integral_info=$order->alias('a')->join('cmf_member b','a.uid=b.id')
            ->join('cmf_integral c','a.service_name=c.service_name')
            ->where('b.pcode|b.liable',$userInfo['code'])->where('a.status','>=',3)
            ->field('a.id as order_id,a.ordernum,a.create_time,a.status,a.price,c.integral')
            ->order('a.create_time desc')->page($page,$pagesize)->select();
        foreach($integral_info as $k=>$v){
            $integral_info[$k]['integral']=$proportion=$v['integral']?$v['integral']:0;
            $integral_info[$k]['int'] = $v['price']*$proportion;
            $integral_info[$k]['status_show']=$v['status']==4?'已到账':'未到账';
      }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$integral_info]);
    }
    //积分提现明细
    public function integral_detail_withdraw(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $withdraworder=new Withdraworder();
        $info=$withdraworder->where('uid',$uid)->order('create_time desc')
            ->field('id as withdraw_id,money,realname,status,create_time')
            ->page($page,$pagesize)->select();
//        if( $info ){
//            $info = collection( $info )->append(['status_text'])->toArray();
//        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$info]);
    }
    //推荐享积分页面显示
    public function recommend(){
        $uid = $this->user_id;
        $user=new Member();
        $userInfo=$user->where('id',$uid)->field('code,level_int')->find();
        $recommend_member=$user->where('pcode|liable',$userInfo['code'])->column('id');
        $out_integral = 0;//未到账积分
        $recommend_order_count=0;//推荐订单数
        $recommend_member_count=0; //下级成员数
        if ($recommend_member){
            $recommend_member_count=count($recommend_member);
            $order=new Order();
            $recommend_order=$order->alias('a')->join('cmf_integral b','a.service_name=b.service_name','left')
                ->where('a.uid','in',$recommend_member)->field('a.service_name,a.price,a.status,b.integral')
                ->select();
            if ($recommend_order){
                $recommend_order_count=count($recommend_order);
                foreach($recommend_order as $k=>$v){
                    if ($v['status']==3){
                        $proportion=$v['integral']?$v['integral']:0;
                        $out_integral+= $v['price']*$proportion;
                    }
                }
            }
        }
        $data=[
            'out_integral'=>$out_integral,
            'integral'=>$userInfo['level_int'],
            'recommend_member_count'=>$recommend_member_count,
            'recommend_order_count'=>$recommend_order_count
            ];
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //我的推荐下查看成员页面显示
    public function my_member(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $user=new Member();
        $userInfo=$user->where('id',$uid)->field('code,level_int')->find();
        $recommend_member_count=$user->where('pcode|liable',$userInfo['code'])->count();
        $recommend_member=$user->where('pcode|liable',$userInfo['code'])->field('nickname,picture,create_time,id as uid')
            ->order('create_time','desc')->page($page,$pagesize)->select();
        if ($recommend_member){
            $nextId = array();//推荐成员ID
            foreach($recommend_member as $k=>$v){
                $nextId[$k] = $v['uid'];
            }
            $order=new Order();
            $orderInfo=$order->where('uid','in',$nextId)->where('status','>',1)->field('price,uid')->select();
            if ($orderInfo){
                foreach($recommend_member as $ke=>$va){
                    $recommend_member[$ke]['num'] = 0;
                    $recommend_member[$ke]['price'] = 0.00;
                    foreach($orderInfo as $key=>$value){
                        if($value['uid']==$va['uid']){
                            $recommend_member[$ke]['num']+=1;
                            $recommend_member[$ke]['price']+=$value['price'];
                        }
                    }
                }
            }else{
                foreach($recommend_member as $ke=>$va){
                    $recommend_member[$ke]['num'] = 0;
                    $recommend_member[$ke]['price'] = 0.00;
                }
            }
            $data=['recommend_member_count'=>$recommend_member_count,'recommend_member'=>$recommend_member];
            parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
        }else{
            $data=['recommend_member_count'=>0,'recommend_member'=>[]];
            parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
        }
    }
    //积分提现 必须先绑定支付宝
    public function integral_withdraw(){
        $uid = $this->user_id;
        $money = data_isset($this->get_post('money'),'intval','');
        //$account = data_isset($this->get_post('account'),'trim','');
        //$realname = data_isset($this->get_post('realname'),'trim','');
        //var_dump($money);exit;
        if (!is_numeric($money)) {
            parent::put_post(['status' => 1003, 'info' => '缺少必要参数！']);
        }
        //是否已绑定支付宝
        $is_bind=db('user_account')->where(['uid'=>$uid,'type'=>1])->find();
        if (!$is_bind){
            parent::put_post(['status' => 1005, 'info' => '请先绑定支付宝！']);
        }
        $user=new Member();
        $userInfo=$user->where('id',$uid)->field('code,integral')->find();
        if ($userInfo['integral']<$money){
            parent::put_post(['status' => 1004, 'info' => '积分不足！']);
        }
        $withdraw_order=new Withdraworder();
        $withdraw_order->money=$money;
        $withdraw_order->account=$is_bind['account'];
        $withdraw_order->realname=$is_bind['realname'];
        $withdraw_order->uid=$uid;
        $withdraw_order->startTrans();
        try{
            $withdraw_order->save();//echo $membercoupon->getLastSql();exit;
            $userInfo->integral=$userInfo['integral']-$money;
            $userInfo->save();
            $withdraw_order->commit();
        } catch (Exception $e) {
            $withdraw_order->rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //发布需求
    public function add_demand(){
        $uid = $this->user_id;
        $content = data_isset($this->get_post('content'),'trim','');
        $type = data_isset($this->get_post('type'),'intval','');//可选
        if (empty($content)) {
            parent::put_post(['status' => 1003, 'info' => '缺少必要参数！']);
        }
        $demand=new Demand();
        $demand->uid=$uid;
        $demand->content=$content;
        $demand->type=$type;
        $demand->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //合同列表显示
    public function pact_list(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $type = data_isset($this->get_post('type'),'intval','');//1未签订 2签订
        if (!is_numeric($type)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        //获取资讯列表数据
        $pact=new Pact();
        $pact_info=$pact->alias('a')->join('cmf_order b','a.order_id=b.id')
            ->where(['a.uid'=>$uid,'a.status'=>$type])->order('a.create_time','desc')
            ->field('a.id as pact_id,a.name,a.create_time,b.service_name')->page($page,$pagesize)->select();
        $pact_info=$pact_info?$pact_info:[];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$pact_info]);
    }
    //合同详情
    public function pact_detail(){
        $uid = $this->user_id;
        $id = data_isset($this->get_post('pact_id'),'intval','');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $pact=new Pact();
        $pact_info=$pact->where(['id'=>$id,'uid'=>$uid])->field('id as pact_id,order_id,status,name,options,master,detail,master_sign_time,update_time')->find();
        if (!$pact_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的合同不存在！']);
        }
        $pact_info['facilitator']='一棵松';
        $option_list=json_decode($pact_info['options'],true);
        $pactoption=new PactOption();
        $pact_list=$pactoption->where('option_id','in',$option_list)
            ->field('option_id,name,fieldname,icon')->select()->toArray();// halt($pact_list);
        $pact=json_decode($pact_info['detail'],true); //var_dump($pact_list);  halt($pact);
        foreach ($pact_list as $k=>$v){
            $pact_list[$k]['content']=$pact[$v['name']];
        }
        $data=['pact_list'=>$pact_list,'pact_info'=>$pact_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    public function pact_detail2(){
        $uid = $this->user_id;
        $id = data_isset($this->get_post('pact_id'),'intval','');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $pact=new Pact();
        $pact_info=$pact->where(['id'=>$id,'uid'=>$uid])->field('id as pact_id,uid,order_id,temp_id,status,name,facilitator,master,detail,master_sign_time,create_time')->find();
        if (!$pact_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的合同不存在！']);
        }
        $pact_info['detail']=json_decode($pact_info['detail'],true);
        $pact_option=new PactOption();
        $alloptions=$pact_option->field('option_id,name,icon')->select();
        $pact_temp=new PactTemp();
        $options=$pact_temp->where('temp_id',$pact_info['temp_id'])->column('options');//var_dump($options);exit;
        $options=json_decode($options[0],true);
        $data=['alloptions'=>$alloptions,'options'=>$options,'pact_info'=>$pact_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //用户确认签署合同
    public function sign_pact(){
        $uid = $this->user_id;
        $id = data_isset($this->get_post('pact_id'),'intval','');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $pact=new Pact();
        $pact_info=$pact->where(['id'=>$id,'uid'=>$uid])->find();
        if (!$pact_info || $pact_info['status']!=1){
            parent::put_post(['status' => 1004, 'info' => '合同不存在！']);
        }
        if ($pact_info['status']==1){//雇主在后台添加合同时已添加，此处不用处理
            $member=new Member();
            //$realname=$member->where('id',$uid)->value('realname');
            $pact_info->status=2;
            $pact_info->master_sign_time=get_time(time(),0);
            //$pact_info->master=$realname;
            $pact_info->save();
            db('order')->where('id',$pact_info['order_id'])->update(['pact'=>3]);
            parent::put_post(['status' => 1000, 'info' => 'OK']);
        }else{
            parent::put_post(['status' => 1004, 'info' => '合同已签订！']);
        }
    }
    //知识产品查询
    public function intell_property(){
        $uid = $this->user_id;
        $brand = data_isset($this->get_post('brand'),'trim','');//查询的品牌
        $mobile = data_isset($this->get_post('mobile'),'trim','');//
        if (empty($brand) || empty($mobile)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $intell_property=new IntellProperty();
        $info=$intell_property->where(['uid'=>$uid,'brand'=>$brand,'mobile'=>$mobile])->find();
        if ($info){
            parent::put_post(['status' => 1004, 'info' => '该品牌您已咨询过，请等待客服人员与您联系！']);
        }
        $intell_property->uid=$uid;
        $intell_property->mobile=$mobile;
        $intell_property->brand=$brand;
        $intell_property->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //商标查询
    public function trademark_inquiry(){
        $uid = $this->user_id;
        $trademark = data_isset($this->get_post('trademark'),'trim','');//查询的商标
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $url = "http://japi.juhe.cn/trademark/search";
        $params = array(
            "key" => config('juhe.trademark_appkey'),//您申请的appKey
            "keyword" => $trademark,//待搜索的关键词
            "pageSize" => $pagesize,//    页面大小，即一次api调用最大获取多少条记录，取值范围：[1-50]
            "pageNo" => $page,//当前页码数，即本次api调用是获得结果的第几页，从1开始计数
            "searchType" => 4,//默认=4,按什么来查，1: 商标名， 2：注册号， 3：申请人 4：商标名/注册号/申请人只要模糊匹配
            "intCls   " => 0,//默认 =0,0：全部国际分类,非0：限定在指定类别，类别间用分号分割。如：4;12;34  表示在第4、12、34类内查询
        );
        $paramstring = http_build_query($params);
        $content = juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                $data=$result['result'];
            }else{
                $data= $result['error_code'].":".$result['reason'];
                parent::put_post(['status' => 1004, 'info' => $data]);
            }
        }else{
            parent::put_post(['status' => 1003, 'info' => '请求失败']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //公司查询
    public function company_inquiry(){
        $uid = $this->user_id;
        $company= data_isset($this->get_post('company'),'trim','');//查询的公司
        $url = "http://japi.juhe.cn/enterprise/simpleList";
        $params = array(
            "key" => config('juhe.company_appkey'),//您申请的appKey
            "keyword" => $company//待搜索的关键词
        );
        $paramstring = http_build_query($params);
        $content = juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                $data=$result['result'];
            }else{
                $data= $result['error_code'].":".$result['reason'];
                parent::put_post(['status' => 1004, 'info' => $data]);
            }
        }else{
            parent::put_post(['status' => 1003, 'info' => '请求失败']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //0元预约
    public function order(){
        $uid = $this->user_id;
        $service_name = data_isset($this->get_post('service_name'),'trim','');//服务名称
        $service_id = data_isset($this->get_post('service_id'),'intval','');//所在服务表是content_id
        $mod = data_isset($this->get_post('mod'),'intval','');//服务模型，用来判断对应哪个服务表 1验资审计等正常服务 2园区服务 3资质转让
        $content = data_isset($this->get_post('content'),'trim','');//用户选择服务内容，mod为1时必传
        $mtc = data_isset($this->get_post('mtc'),'intval','');//1季付2半年付3年付
        if (empty($service_name) || empty($mod) || !is_numeric($service_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        if ($mod==1 && empty($content)){
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        if ($service_name=='财税管理'&& empty($mtc)){
            parent::put_post(['status' => 1003, 'info' => '支付方式必传！']);
        }
        //是否实名
        $member=new Member();
        $member_info=$member->where('id',$uid)->field('realname,card_num,id_photo')->find();
        if (!$member_info['realname']){
            parent::put_post(['status' => 1005, 'info' => '请先实名认证！']);
        }
        $mess=[];
        $order= new Order();
        switch ($mod){
            case 2:
               $order->mod='Housecontent';
               $model=new Housecontent();
               break;
            case 3:
                $order->mod='Companycontent';
                $model=new Companycontent();
                break;
            case 1:
                $order->mod='Servicecontent';
                $model=new Servicecontent();
                break;
        }
        $info=$model->where('content_id',$service_id)->find()->toArray();
        if (!$info){
            parent::put_post(['status' => 1006, 'info' => '预约服务信息错误！']);
        }
        if (isset($info['service'])){
            if ($info['service']!=$service_name){
                parent::put_post(['status' => 1006, 'info' => '预约服务信息错误！']);
            }
        }
        //产品信息（包括产品名称，主图即第一张图，标签）
        $product_name=isset($info['name'])?$info['name']:$service_name;
        $product_picture=$info['picture_show']?$info['picture_show'][0]:'';
        if (isset($info['filter_id'])){
            $filter_id=json_decode($info['filter_id'],true);
            $filter =db('filter')->where('id','in',$filter_id)->limit(3)->column('condition');
        }else{
            $filter=[];
        }
        $found_time=isset($info['found_time'])?$info['found_time']:'';
        $product_log=[];
        $product_log=['product_name'=>$product_name,'product_picture'=>$product_picture,'filter'=>$filter,'found_time'=>$found_time];
        //财税订单选择付款方式
        if ($mtc){
            $order->mtc=$mtc;
            switch($mtc){
                case 1://一季以後催賬
                    $order->nextpay_time = get_time(strtotime("+ 3 months",time()),0);
                    break;
                case 2://半年以後催賬
                    $order->nextpay_time = get_time(strtotime("+ 6 months",time()),0);
                    break;
                case 3://一年以後催賬
                    $order->nextpay_time = get_time(strtotime("+ 1 years",time()),0);
                    break;

            }
        }
        $order->product_log=json_encode($product_log);
        $order->service_name=$service_name;
        $order->service_id=$service_id;
        $order->uid=$uid;
        $order->content=$content;
        $order->sprice=$info['price'];
        $order->ordernum = time().sixRandNumber(4);
        $rs=$order->save();
        if ($rs){
            //向客户发送模板消息
            $app_key=config('jpush.app_key');
            $master_secret=config('jpush.master_secret');
            $client=new \JPush\Client($app_key,$master_secret,null);
            $client_arr = UserData::getGeTuiByUid( $uid );
            $pusher = $client->push();
            $pusher->setPlatform('all');
            $message=new Message();
            foreach ($client_arr as $key=>$val){//用户可能登录多个终端（员工端不需要发送此消息，会员端只能有一个终端登录，故其实不存在多个终端）
                $pusher->addRegistrationId($val['registration_id']);
                $pusher->setNotificationAlert("尊敬的用户您好,您的 ".$service_name." 服务已0元预约成功！");
                try {
                    $r=$pusher->send();
                    print_r($r);
                } catch (\JPush\Exceptions\JPushException $e) {
                    // try something else here
                    print $e;
                }
                $message->registration_id=$val['registration_id'];
                $message->title='预约订单成功提醒';
                $message->content="尊敬的用户您好,您的 ".$service_name." 服务已0元预约成功！";
                $message->isUpdate(false)->save();
            }
        }else{
            parent::put_post(['status' => 1006, 'info' => '预约失败！']);
        }
        $data=['ordernum'=>$order->ordernum];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //订单列表显示付款前以列表形式检查订单信息
    public function order_check(){
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        //获取资讯列表数据
        $order=new Order();
        $order_info=[];
        $order_info=$order->where(['uid'=>$uid,'status'=>2])->field('id as order_id,mod,ordernum,service_name,price,product_log')
            ->page($page,$pagesize)->select();
        if ($order_info){
            foreach($order_info as $k=>$v){
                if ($v['product_log']){
                    $product_log=json_decode($v['product_log'],true);
                    $order_info[$k]['product_name']=isset($product_log['product_name'])?$product_log['product_name']:'';
                    $order_info[$k]['product_picture']=isset($product_log['product_picture'])?$product_log['product_picture']:'';
                    $order_info[$k]['filter']=isset($product_log['filter'])?$product_log['filter']:[];
                    $order_info[$k]['found_time']=isset($product_log['found_time'])?$product_log['found_time']:'';
                }else{
                    $order_info[$k]['product_name']='';
                    $order_info[$k]['product_picture']='';
                    $order_info[$k]['filter']=[];
                    $order_info[$k]['found_time']='';
                }
            }
            //通过当前订单获取优惠券 (未用，满足满减和对应服务，自动抵扣适用的最大优惠的优惠券）
            $member_coupon=new MemberCoupon();
            $time = get_time(time(),0);
            $coupon_info=$member_coupon->alias('a')->join('cmf_coupon b','a.coupon_id=b.coupon_id')
                ->where(['a.uid'=>$uid,'a.status'=>1])->where('a.end_time','>',$time)
                ->field('a.coupon_id,a.member_coupon_id,b.service_name,b.name,b.price,b.fullprice')
                ->order('b.price desc')->select();

            if ($coupon_info){
                foreach ($order_info as $k=>$v){
                    $order_info[$k]['coupon_id']=0;
                    $order_info[$k]['pay_price']=$v['price'];
                    foreach ($coupon_info as $key=>$val){
                        if ($val['service_name']==$v['service_name'] && $val['fullprice']<=$v['price']){
                            $order_info[$k]['coupon_id']=$coupon_info[$key]['member_coupon_id'];
                            $order_info[$k]['pay_price']=$v['price']-$val['price'];
                            break;
                        }
                    }
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$order_info]);
    }
    /*付款之后订单列表管理*/
    public function order_list(){
        //$url=url('member/order_list');// /yikesong/public/api/member/order_list.html
        $uid = $this->user_id;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $type = data_isset($this->get_post('type'),'intval',1);//1全部，2办理中3已完成
        $map=[];
        switch($type){
            case 1:
                $map['status']=['in',[3,4]];
                break;
            case 2:
                $map['status']=3;
                break;
            case 3:
                $map['status']=4;
                break;
        }
        //获取资讯列表数据
        $order=new Order();
        $order_info=$order->where(['uid'=>$uid,'is_del'=>0])->where($map)->field('id as order_id,ordernum,service_name,status,price,pay_money,product_log')
            ->page($page,$pagesize)->select();//echo $order->getLastSql(); exit;
        //$order_info=$order_info?$order_info:[];
        if ($order_info){
            foreach($order_info as $k=>$v){
                if ($v['product_log']){
                    $product_log=json_decode($v['product_log'],true);
                    $order_info[$k]['product_name']=isset($product_log['product_name'])?$product_log['product_name']:'';
                    $order_info[$k]['product_picture']=isset($product_log['product_picture'])?$product_log['product_picture']:'';
                    $order_info[$k]['filter']=isset($product_log['filter'])?$product_log['filter']:[];
                    $order_info[$k]['found_time']=isset($product_log['found_time'])?$product_log['found_time']:'';
                }else{
                    $order_info[$k]['product_name']='';
                    $order_info[$k]['product_picture']='';
                    $order_info[$k]['filter']=[];
                    $order_info[$k]['found_time']='';
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$order_info]);
    }
    /*付款之后订单进度管理*/
    public function order_schedule()
    {
        $uid = $this->user_id;
        $id = data_isset($this->get_post('order_id'), 'intval', '');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where(['id'=>$id,'uid'=>$uid])->find();
        if (!$order_info){
            parent::put_post(['status' => 1004, 'info' => '订单不存在！']);
        }
        $servicejd=new Servicejd();
        $info=$servicejd->where('service_name',$order_info['service_name'])->field('name')->find();
        if (!$info){
            parent::put_post(['status' => 1004, 'info' => '该服务暂不存在进度管理！']);
        }
        $info=$info->toArray();
        $jin = explode(",",$info['name']);
        //从订单进度表获取相关订单进度
        $schedule = model('OrderSchedule');
        $schedule_info = $schedule->where('order_id',$id)->select();
        $jindu_info=[];
        foreach($jin as $k=>$v){
            $v = trim($v);
            $jindu_info[$k]['name'] = $v;
            foreach($schedule_info as $ke=>$va){
                if($va['name']==$v){
                    $jindu_info[$k]['create_time'] = $va['create_time'];
                    break;
                }else{
                    $jindu_info[$k]['create_time']='';
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$jindu_info]);
    }
    /*订单完成之后删除订单，即在订单管理列表里看不到，后台还是正常的*/
    public function order_del()
    {
        $uid = $this->user_id;
        $id = data_isset($this->get_post('order_id'), 'intval', '');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where(['id'=>$id,'uid'=>$uid])->find();
        if (!$order_info){
            parent::put_post(['status' => 1004, 'info' => '订单不存在！']);
        }
        if ($order_info['status']!=4 || $order_info['is_del']==1){
            parent::put_post(['status' => 1004, 'info' => '此订单不可删除！']);
        }
        $order_info->is_del=1;
        $order_info->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    /*微信app支付*/
    public function wxpay()
    {
        $uid = $this->user_id;
        $order_id = data_isset($this->get_post('order_id'), '', "");
        //$type = data_isset($this->get_post('type'), '', "");
        $money = data_isset($this->get_post('money'), '', "");//支付价格，可传可不传，传时需要验证
        $coupon_id = data_isset($this->get_post('coupon_id'), 'intval', "");//使用优惠券id,对应我的优惠券表
        if (!$order_id || !$money) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->find();
        if (!$order_info || $order_info['status']!=2 ||$order_info['uid']!=$uid){
            parent::put_post(['status' => 1005, 'info' => '订单错误或已支付！']);
        }
        $pact_info=db('pact')->where('order_id',$order_id)->field('status')->find();
        if ($pact_info['status']!=2){
            parent::put_post(['status' => 1005, 'info' => '请先签订订单合同！']);
        }
        $time = get_time(time(),0);
        $pay_money=$order_info['price'];
        if ($coupon_id>0){
            $member_coupon=new MemberCoupon();
            $coupon_info=$member_coupon->alias('a')->join('cmf_coupon b','a.coupon_id=b.coupon_id')
                ->where('a.member_coupon_id',$coupon_id)
                ->field('a.member_coupon_id,a.uid,a.status,a.end_time,b.service_name,b.price,b.fullprice')->find();
            if (!$coupon_info || $coupon_info['uid']!=$uid || $coupon_info['status']!=1 || $coupon_info['end_time']<$time || $coupon_info['fullprice']>$order_info['price']){
                parent::put_post(['status' => 1005, 'info' => '优惠券无效！']);
            }
            $pay_money-=$coupon_info['price'];
            session('coupon',$coupon_id);
        }
        if ($pay_money!=$money){
            parent::put_post(['status' => 1005, 'info' => '支付金额错误！']);
        }
        $response = weixin_pay($uid, $money, $order_id,  1);//halt($response);
        if ($response) {
            /*$updata = [];
            $updata['sign'] = $response['sign'];
            db('order')->where(['id' => $order_id])->update($updata);*/
            $result['status'] = 1000;
            $result['info'] = '支付成功';
            $result['data'] = $response;
            parent::put_post($result);
        } else {
            $result['status'] = 1002;
            $result['info'] = '支付失败';
            parent::put_post($result);
        }
    }
}