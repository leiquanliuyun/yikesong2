<?php
namespace app\api\controller;
use think\Db;
use app\api\model\Housecontent;
use app\api\model\Servicecontent;
use app\api\model\Companycontent;
use app\api\model\Pact;
use app\api\model\PactOption;
use app\api\model\PactTemp;
use app\api\model\Order;
use app\api\model\Servicejd;
use app\common\lib\lbs\Geohash;
use app\api\model\Users;
use app\api\model\Member;
use app\api\model\Demand;
use app\api\model\UserAccount;
use app\api\model\Coupon;
use app\api\model\MemberCoupon;
use app\api\model\Withdraworder;
use app\common\lib\push\Getui;
/**
 * 员工端控制器 20180904
 */
class  ServerController extends CommonController
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
        $this->user = Users::get($this->user_id);
    }

    //待接单订单
    public function order_list(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);//halt($this->user);
        $realname=$this->user->realname;
        //获取资讯列表数据
        $order=new Order();
        $order_info=$order->where(['liable'=>$realname,'assign'=>2])
            ->field('id as order_id,ordernum,service_name,product_log')
            ->page($page,$pagesize)->select();//halt($order_info);
        //$order_info=$order_info?$order_info:[];
        if ($order_info){
            foreach($order_info as $k=>$v){
                if ($v['product_log']){
                    $product_log=json_decode($v['product_log'],true);
                    $order_info[$k]['product_name']=$product_log['product_name'];
                    $order_info[$k]['product_picture']=$product_log['product_picture'];
                    //$order_info[$k]['filter']=$product_log['filter'];
                }else{
                    $order_info[$k]['product_name']='';
                    $order_info[$k]['product_picture']='';
                    //$order_info[$k]['filter']=[];
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$order_info]);
    }
    //非正常订单,可合并到已接单订单列表
    public function unusual_order_list(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);//halt($this->user);
        $realname=$this->user->realname;
        //获取资讯列表数据
        $order=new Order();
        $order_info=$order->where(['liable'=>$realname,'assign'=>3,'audit'=>0])
            ->field('id as order_id,ordernum,service_name,price,pact,status,product_log,pay_time,audit')
            ->page($page,$pagesize)->select();//halt($order_info);
        //$order_info=$order_info?$order_info:[];
        if ($order_info){
            foreach($order_info as $k=>$v){
                if ($v['product_log']){
                    $product_log=json_decode($v['product_log'],true);
                    $order_info[$k]['product_name']=$product_log['product_name'];
                    $order_info[$k]['product_picture']=$product_log['product_picture'];
                    //$order_info[$k]['filter']=$product_log['filter'];
                }else{
                    $order_info[$k]['product_name']='';
                    $order_info[$k]['product_picture']='';
                    //$order_info[$k]['filter']=[];
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$order_info]);
    }
    //接单，拒单
    public function order_recieve(){
        $uid = $this->user_id;
        $realname=$this->user->realname;
        $order_id= data_isset($this->get_post('order_id'),'intval','');
        $type= data_isset($this->get_post('type'),'intval','');//1接单 2拒单
        if (!$order_id || !$type) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->find();
        if (!$order_info || $order_info['assign']!=2 ||$order_info['liable']!=$realname){
            parent::put_post(['status' => 1005, 'info' => '订单错误或已被接单！']);
        }
        if ($type==1){
            $order_info->assign=3;
        }else{
            $order_info->assign=1;
        }
        $order_info->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //订单详情，不同类型订单返回信息不一样，可考虑根据订单类型分为3个接口
    public function order_detail(){
        $uid = $this->user_id;
        $realname=$this->user->realname;
        $order_id= data_isset($this->get_post('order_id'),'intval','');
        //$service_name= data_isset($this->get_post('service_name'),'trim','');//所属服务
        if (!$order_id && is_numeric($order_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->find();
        if (!$order_info ||$order_info['liable']!=$realname){
            parent::put_post(['status' => 1005, 'info' => '订单错误或已被接单！']);
        }
        if ($order_info['service_name']=='园区服务'){
            $housecontemt=new Housecontent();
            $info=$housecontemt->where('content_id',$order_info['service_id'])
                ->field('content_id,name,type,size,orientation,fixture,rental_method,floor,price,introduce_mess,trade_type')->find();
        }else if($order_info['service_name']=='资质转让'){
            $companycontent=new Companycontent();
            $info=$companycontent->where('content_id',$order_info['service_id'])
                ->field('content_id,name,found_time,aptitude,tally,solid_assets,regmoney,remark,price,introduce_mess,trade_type')->find()->toArray();
        }else{
            $content=explode(',',$order_info['content']);//halt($content);
            $info=[];
            foreach ($content as $k=>$v){
                $r=explode(':',$v);
                $info[$r[0]]=$r[1];
            }
        }
        $data=['order_info'=>$info,'service_name'=>$order_info['service_name']];
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //已接单订单(),如果已有合同，要返回合同信息，在查询合同时前端才能直接返回合同id
    public function my_order_list(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);//halt($this->user);
        $type = data_isset($this->get_post('type'),'intval','');//1 办理中 2已完成 3非正常 4费用催缴
        if (!is_numeric($type)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $uid = $this->user_id;
        $realname=$this->user->realname;
        //获取资讯列表数据
        $order=new Order();
        $where=[];
        if ($type==1){
            $where['status']=['<',4];
            $where['audit']=1;
        }else if($type==2){
            $where['status']=4;
            $where['audit']=1;
        }else if($type==3){
            $where['audit']=0;
        }else{//已完成,已确定了下次支付时间,财税管理，且处于未催缴状态
            $time=get_time(strtotime("+ 1 months",time()),0);
            $where['status']=4;
            $where['service_name']='财税管理';
            $where['debt']=1;
            $where['nextpay_time']=['<',$time];
        }
        $order_info=$order->where(['liable'=>$realname,'assign'=>3])->where($where)
            ->field('id as order_id,belong_id,ordernum,service_name,price,pact,status,product_log,pay_time,nextpay_time,audit')
            ->page($page,$pagesize)->select();//halt($order_info);
        //$order_info=$order_info?$order_info:[];
        if ($order_info){
            foreach($order_info as $k=>$v){
                if ($v['product_log']){
                    $product_log=json_decode($v['product_log'],true);
                    $order_info[$k]['product_name']=$product_log['product_name'];
                    $order_info[$k]['product_picture']=$product_log['product_picture'];
                    //$order_info[$k]['filter']=$product_log['filter'];
                }else{
                    $order_info[$k]['product_name']='';
                    $order_info[$k]['product_picture']='';
                    //$order_info[$k]['filter']=[];
                }
                $order_info[$k]['pact_id']=0;
                if ($v['pact']>1){
                    if ($v['belong_id']){
                        $where2['order_id']=$v['belong_id'];
                    }else{
                        $where2['order_id']=$v['order_id'];
                    }
                    $order_info[$k]['pact_id']=db('pact')->where($where2)->value('id');
                }
            }
        }
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$order_info]);
    }
    //修改订单价格  可以多次修改吗？需要判断与合同是否一致否？
    public function chang_price(){
        $realname=$this->user->realname;
        $order_id= data_isset($this->get_post('order_id'),'intval','');
        $price= data_isset($this->get_post('price'),'trim','');
        $mtc = data_isset($this->get_post('mtc'),'intval','');//1季付2半年付3年付 财税订单必须有
        if (!$order_id) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->find();
        if (!$order_info ||$order_info['liable']!=$realname || $order_info['status']!=1){
            parent::put_post(['status' => 1005, 'info' => '订单错误或已被接单！']);
        }
        if ($mtc){
            $order_info->mtc=$mtc;
            switch($mtc){
                case 1://一季以後催賬
                    $order_info->nextpay_time = get_time(strtotime("+ 3 months",time()),0);
                    break;
                case 2://半年以後催賬
                    $order_info->nextpay_time = get_time(strtotime("+ 6 months",time()),0);
                    break;
                case 3://一年以後催賬
                    $order_info->nextpay_time = get_time(strtotime("+ 1 years",time()),0);
                    break;

            }
        }
        $order_info->status=2;
        $order_info->price=$price;
        $order_info->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //添加合同（显示合同模板及合同项）
    public function pact_option(){
        $order_id = data_isset($this->get_post('order_id'),'intval',0);
        if (!is_numeric($order_id)|| $order_id< 1) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order_info=db('order')->where('id',$order_id)->field('id,uid,pact,liable')->find();
        $realname=$this->user->realname;
        if ($order_info['pact']>1){
            parent::put_post(['status' => 1004, 'info' => '该订单合同已存在！']);
        }
        if ($order_info['liable']!=$realname){
            parent::put_post(['status' => 1004, 'info' => '对不起，你不是该订单的负责人，无法添加合同！']);
        }
        //所有合同模板
        $temps=db('pact_temp')->field('temp_id,options,description')->order('temp_id')->select();
        if (!$temps){
            parent::put_post(['status' => 1003, 'info' => '请先添加合同模板！']);
        }
        //$order_id = data_isset($this->get_post('order_id'),'intval','');
        $temp_id = data_isset($this->get_post('temp_id'),'intval',0);//默认选择第一个合同模板
        if ($temp_id==0){
            $temp_id=$temps[0]['temp_id'];
        }
        $temp=db('pact_temp')->where('temp_id',$temp_id)->find();
        if (!$temp){
            parent::put_post(['status' => 1003, 'info' => '所选模板不存在！']);
        }
        $option_list=json_decode($temp['options'],true);
        $pactoption=new PactOption();
        $options=$pactoption->where('option_id','in',$option_list)
            ->field('option_id,name,fieldname,icon')->select();
        $data=['temps'=>$temps,'options'=>$options];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //添加合同,直接将合同项存入合同，以后显示合同详情时就没有必要再去合同模板里查询对应的合同项
    //对于前端传过来的json字符串数据，可以$_POST接收，直接存入数据库,也可以用get_post接收，然后用htmlspecialchars_decode将html实体转换成字符串
    public function add_pact(){
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        $temp_id = data_isset($this->get_post('temp_id'),'intval','');
        $detail = data_isset($this->get_post('detail'),'trim','');//合同内容 {"工期":"略","支付验收":"略","双方责任":"略","rr":"略"}
        //$detail=isset($_POST['detail'])?$_POST['detail']:'';
        $master = data_isset($this->get_post('master'),'trim','');//雇主名
        $liable = data_isset($this->get_post('liable'),'trim','');//合同负责人
        $name= data_isset($this->get_post('name'),'trim','');//合同名称
        //$param=$_POST;parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$detail]);
        if (!is_numeric($order_id) || !is_numeric($temp_id) || empty($detail) ||empty($master) ||empty($liable)||empty($name)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order_info=db('order')->where('id',$order_id)->field('id,uid,pact,liable')->find();
        $pact_info=db('pact')->where('order_id',$order_id)->field('id')->find();
        $realname=$this->user->realname;
        if ($order_info['pact']>1 || $pact_info){
            parent::put_post(['status' => 1004, 'info' => '该订单合同已存在！']);
        }
        if ($order_info['liable']!=$realname){
            parent::put_post(['status' => 1004, 'info' => '对不起，你不是该订单的负责人，无法添加合同！']);
        }
        $pact=new Pact();
        $options=db('pact_temp')->where('temp_id',$temp_id)->value('options');
        $detail=htmlspecialchars_decode($detail);//echo '<pre>'; var_dump($detail);exit;
        $pact->order_id=$order_id;
        $pact->uid=$order_info['uid'];
        $pact->temp_id=$temp_id;
        $pact->detail=$detail;
        $pact->master=$master;
        $pact->liable=$liable;
        $pact->name=$name;
        $pact->options=$options;
        $pact->save();
        db('order')->where('id',$order_id)->setField('pact',2);
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //合同详情 用户未签订时点击查看合同调用此接口,严格来讲需要判断该合同所属订单是否归该员工负责
    public function pact_detail_before(){
        $id = data_isset($this->get_post('pact_id'),'intval','');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        //所有合同模板
        $temps=db('pact_temp')->field('temp_id,options,description')->order('temp_id')->select();
        if (!$temps){
            parent::put_post(['status' => 1003, 'info' => '请先添加合同模板！']);
        }
        $pact=new Pact();
        $pact_info=$pact->where(['id'=>$id,'status'=>1])->field('id as pact_id,order_id,temp_id,status,name,options,master,liable,detail')->find();
        if (!$pact_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的合同不存在！']);
        }
        $option_list=json_decode($pact_info['options'],true);
        $pactoption=new PactOption();
        $pact_list=$pactoption->where('option_id','in',$option_list)
            ->field('option_id,name,fieldname,icon')->select();// halt($pact_list);
        $pact=json_decode($pact_info['detail'],true);halt($pact);
        foreach ($pact_list as $k=>$v){
            $pact_list[$k]['content']=$pact[$v['name']];
        }
        $data=['pact_list'=>$pact_list,'pact_info'=>$pact_info,'temps'=>$temps];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //合同详情
    public function pact_detail(){
        $id = data_isset($this->get_post('pact_id'),'intval','');
        if (!is_numeric($id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $pact=new Pact();
        $pact_info=$pact->where(['id'=>$id,'status'=>2])->field('id as pact_id,order_id,status,name,options,master,detail,master_sign_time,update_time')->find();
        if (!$pact_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的合同不存在！']);
        }
        $option_list=json_decode($pact_info['options'],true);
        $pactoption=new PactOption();
        $pact_list=$pactoption->where('option_id','in',$option_list)
            ->field('option_id,name,fieldname,icon')->select()->toArray();// halt($pact_list);
        $pact=json_decode($pact_info['detail'],true);
        foreach ($pact_list as $k=>$v){
            $pact_list[$k]['content']=$pact[$v['name']];
        }
        $data=['pact_list'=>$pact_list,'pact_info'=>$pact_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //合同列表显示
    public function pact_list(){
        $uid = $this->user_id;
        $realname=$this->user->realname;
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $type = data_isset($this->get_post('type'),'intval','');//1未签订 2签订
        //获取资讯列表数据
        $pact=new Pact();
        $pact_info=$pact->alias('a')->join('cmf_order b','a.order_id=b.id')
            ->where(['b.liable'=>$realname,'a.status'=>$type])->order('a.create_time','desc')
            ->field('a.id as pact_id,a.name')->page($page,$pagesize)->select();
        $pact_info=$pact_info?$pact_info:[];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$pact_info]);
    }
    //订单备注 订单正常也要备注吗
    public function add_remark(){
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        $audit = data_isset($this->get_post('audit'),'intval','');
        $remark = data_isset($this->get_post('remark'),'trim','');//备注
        //halt($audit);
        if (!is_numeric($order_id) || !is_numeric($audit)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->field('id,uid')->find();
        if (!$order_info){
            parent::put_post(['status' => 1004, 'info' => '订单不存在！']);
        }
        $order_info->audit=$audit;
        $order_info->remark=$remark;
        $order_info->save();
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //显示备注
    public function remark(){
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        if (!is_numeric($order_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->field('id as order_id,audit,remark')->find();
        if (!$order_info){
            parent::put_post(['status' => 1004, 'info' => '订单不存在！']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$order_info]);
    }
    //订单进度
    public function schedule(){
        $realname=$this->user->realname;
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        if (!is_numeric($order_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->field('id as order_id,liable,service_name,status,audit,remark')->find();
        if (!$order_info || $order_info['status']<3 ||$order_info['liable']!=$realname){
            parent::put_post(['status' => 1004, 'info' => '订单不存在或订单尚未支付！']);
        }
        //获取进度
        $servicejd = model('Servicejd');
        $jindu = $servicejd->where('service_name',$order_info['service_name'])->order("create_time desc")->find();
        if (!$jindu){
            parent::put_post(['status' => 1004, 'info' => '订单不存在进度管理！']);
        }
        $jinduName = explode(",", $jindu['name']);
        $schedule = model('OrderSchedule');
        $scheduleInfo = $schedule->where('order_id',$order_id)->select();
        //var_dump($scheduleInfo);
        $jindu_info= array();
        foreach ($jinduName as $key => $value) {
            $value = trim($value);
            $jindu_info[$key]['name'] = $value;
        }//var_dump($jinduInfo);exit;
        if ($scheduleInfo) {
            foreach ($jindu_info as $k => $v) {
                $is_exits=false;
                foreach ($scheduleInfo as $ke => $va) {
                    if ($va['name'] == $v['name']) {
                        $jindu_info[$k]['status'] = 1;
                        $jindu_info[$k]['create_time'] = $va['create_time'];
                        $is_exits=true;
                    }
                }
                if (!$is_exits){
                    $jindu_info[$k]['status'] = 0;
                }
            }
        }
        $data=['jindu_info'=>$jindu_info,'order_id'=>$order_id,'order_status'=>$order_info['status']];
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
    //确认进度
    public function confirm_schedule(){
        $realname=$this->user->realname;
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        $jindu = data_isset($this->get_post('jindu'),'trim','');//当前进度名
        if (!is_numeric($order_id) || empty($jindu)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->field('id as order_id,uid,liable,service_name,pay_money,status,audit,remark')->find();
        if (!$order_info || $order_info['status']!=3 ||$order_info['liable']!=$realname){
            parent::put_post(['status' => 1004, 'info' => '订单不存在或订单尚未支付！']);
        }
        //获取服务进度
        $servicejd = model('Servicejd');
        $service_jindu = $servicejd->where('service_name',$order_info['service_name'])->order("create_time desc")->find();
        if (!$service_jindu){
            parent::put_post(['status' => 1004, 'info' => '订单不存在进度管理！']);
        }
        $jinduName = explode(',', $service_jindu['name']);
        $end = end($jinduName);
        $end = trim($end);
        $check = 1;
        $len = 0;//用来判断当前确认完成的进度是第几个

        foreach ($jinduName as $k => $v) {//该订单服务是否有该服务进度，有则为第几个订单进度
            $v = trim($v);
            if ($v == $jindu) {
                $check = 2;
                $len = $k;
                break;
            }
        }
        if ($check == 1) {
            parent::put_post(['status' => 1004, 'info' => '订单不存在此进度！']);
        }
        //判断该订单下该进度是否已经存在
        $schedule = model('OrderSchedule');
        $scheduleInfo = $schedule->where('order_id', $order_id)->column('name');
        if ($scheduleInfo) {
            if (in_array($jindu,$scheduleInfo)){
                parent::put_post(['status' => 1004, 'info' => '此进度已完成！']);
            }
        }
        if ($len > 0) {
            if (!$scheduleInfo) {
                parent::put_post(['status' => 1004, 'info' => '请先完成当前进度之前的进度！']);
            }
            $lenarray = array();
            //判断该进度之前的进度是否已完成
            for ($i = 0; $i < $len; $i++) {
                $lenarray[$i] = trim($jinduName[$i]);//该进度前的所有进度
            }//var_dump($lenarray); var_dump($scheduleInfo);
            foreach ($lenarray as $k => $v) {
                if (!in_array($v,$scheduleInfo)){
                    parent::put_post(['status' => 1004, 'info' => '请先完成当前进度之前的进度！']);
                }
            }
        }
        Db::startTrans();
        try {
            //添加订单进度
            $mess['uid'] = $order_info['uid'];
            $mess['order_id'] = $order_id;
            $mess['name'] = $jindu;
            $mess['create_time']=get_time(time(),0);//halt($mess);
            db('order_schedule')->insert($mess);
            /*if ($jindu==$end){//确认最后一个进度
                db('order')->where('id',$order_id)->setField('status',4);
                $member_info=db('member')->where('id',$order_info['uid'])->field('id,pcode,level_int,integral')->find();
                if ($member_info['pcode']){
                    //获取当前服务对上级返回积分比例
                    $integral=model('Integral');
                    $integralInfo = $integral->where('service_name',$order_info['service_name'])->find();
                    if ($integralInfo) {
                        $integral = $integralInfo['integral'];
                    } else {
                        $integral = 0;
                    }
                    $add_integral=$order_info['pay_money']*$integral;
                    $member_mess=['integral'=>(int)$member_info['integral']+(int)$add_integral,'level_int'=>(int)$member_info['level_int']+(int)$add_integral];
                    db('member')->where('code',$member_info['pcode'])->update($member_mess);
                }
            }*/
            Db::commit();
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //确认订单完成
    public function confirm_complete(){
        $realname=$this->user->realname;
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        if (!is_numeric($order_id) ) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $order=new Order();
        $order_info=$order->where('id',$order_id)->field('id as order_id,uid,liable,service_name,pay_money,status,audit,remark')->find();
        if (!$order_info || $order_info['status']!=3 ||$order_info['liable']!=$realname){
            parent::put_post(['status' => 1004, 'info' => '订单不存在或订单尚未支付！']);
        }
        //获取服务进度
        $servicejd = model('Servicejd');
        $service_jindu = $servicejd->where('service_name',$order_info['service_name'])->order("create_time desc")->find();
        if (!$service_jindu){
            parent::put_post(['status' => 1004, 'info' => '订单不存在进度管理！']);
        }
        //判断是否所有进度已完成
        $jinduName = explode(',', $service_jindu['name']);
        $end =stripslashes(end($jinduName));//parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$service_jindu['name']]);
        $schedule = model('OrderSchedule');
        $scheduleInfo = $schedule->where(['order_id'=>$order_id,'name'=>$end])->value('name');
        if (!$scheduleInfo) {
            parent::put_post(['status' => 1004, 'info' => '该订单还有进度未完成！']);
        }

        Db::startTrans();
        try {
            db('order')->where('id',$order_id)->setField('status',4);
            $member_info=db('member')->where('id',$order_info['uid'])->field('id,pcode,level_int,integral')->find();
            if ($member_info['pcode']){
                //获取当前服务对上级返回积分比例
                $integral=model('Integral');
                $integralInfo = $integral->where('service_name',$order_info['service_name'])->find();
                if ($integralInfo) {
                    $integral = $integralInfo['integral'];
                } else {
                    $integral = 0;
                }
                $add_integral=$order_info['pay_money']*$integral;
                $member_mess=['integral'=>(int)$member_info['integral']+(int)$add_integral,'level_int'=>(int)$member_info['level_int']+(int)$add_integral];
                db('member')->where('code',$member_info['pcode'])->update($member_mess);
            }
            Db::commit();
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            var_dump($e->getMessage());
            parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //费用催缴（实为续单）
    public function debt(){
        $order_id = data_isset($this->get_post('order_id'),'intval','');
        $price= data_isset($this->get_post('price'),'trim','');
        if (!is_numeric($order_id) || empty($price)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $time=get_time(strtotime("+ 1 months",time()),0);
        $realname=$this->user->realname;
        $order=new Order();
        $order_info=$order->where('id',$order_id)->find();
        if (!$order_info || $order_info['service_name']!='财税管理' ||$order_info['debt']==2 ||$order_info['status']!=4 ||$order_info['nextpay_time']>$time ||$order_info['liable']!=$realname ||$order_info['assign']!=3 ){
            parent::put_post(['status' => 1004, 'info' => '订单不能催缴！']);
        }
        //新增订单，关键是新订单的合同处理,合同仍用第一个财税订单的合同,通过belong_id可由催缴订单找到其合同
        $nextpay_time=get_time(strtotime($order_info['nextpay_time']),0);
        $addinfo['company_name'] = $order_info['company_name'];
        $addinfo['service_name'] = $order_info['service_name'];
        $addinfo['uid'] = $order_info['uid'];
        $addinfo['mod'] = $order_info['mod'];
        $addinfo['content'] = $order_info['content'];
        $addinfo['liable'] = $order_info['liable'];
        $addinfo['service_id'] = $order_info['service_id'];
        $addinfo['sprice'] = $order_info['sprice'];
        $addinfo['price'] = $price;
        $addinfo['status']=2;
        $addinfo['debt']=1;
        $addinfo['ordernum'] = time().sixRandNumber(4);
        $addinfo['mtc'] = $order_info['mtc'];
        $addinfo['product_log'] = $order_info['product_log'];
        $addinfo['pact'] = $order_info['pact'];
        $addinfo['assign'] =3;
        $addinfo['belong_id'] = $order_info['belong_id']?$order_info['belong_id']:$order_info['id'];
        $addinfo['nextpay_time'] = $nextpay_time;
        $addinfo['create_time'] = get_time(time(),0);
        $order->startTrans();
        try{
            $order->isUpdate(false)->save($addinfo);
            $order_info->debt=2;
            $order_info->save();
            //发送模板消息给用户
            $order->commit();
        } catch (\Exception $e) {
            // 回滚事务
            $order->rollback();
            var_dump($e->getMessage());
        }
        parent::put_post(['status' => 1000, 'info' => 'OK']);
    }
    //我的推广 用户头像问题   员工的推广需要先获取该员工的前端会员信息，因为推广是会员
    public function my_recommend(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $year = data_isset($this->get_post('year'),'intval','');
        $month = data_isset($this->get_post('month'),'intval','');
        if (!is_numeric($year)||!is_numeric($month)) {
            parent::put_post(['status' => 1003, 'info' => '缺少必要参数！']);
        }
        $user=new Member();
        $mobile=$this->user->mobile;
        $user_info=$user->where('mobile',$mobile)->field('code,level_int,id')->find();
        if (!$user_info){
            $data=['recommend_member_count'=>0,'recommend_order_count'=>0,'recommend_money_count'=>0,'recommend_member'=>[]];
            parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
        }
        $start_time=get_time(strtotime($year.'-'.$month),0);
        $month=$month+1;
        $end_time=get_time(strtotime($year.'-'.$month),0);
        $where['create_time']=[['>',$start_time],['<',$end_time],'and'];
        $recommend_member_count=$user->where('pcode|liable',$user_info['code'])->where($where)->count();
        $recommend_order_count=0;
        $recommend_money_count=0;
        if ($recommend_member_count){
            $recommend_member=$user->where('pcode|liable',$user_info['code'])->where($where)->field('nickname,picture,create_time,id as uid')
                ->order('create_time','desc')->page($page,$pagesize)->select();//echo $user->getLastSql();exit;
            $nextId = array();//推荐成员ID

            foreach($recommend_member as $k=>$v){
                $nextId[$k] = $v['uid'];
            }halt($nextId);
            $order=new Order();
            $order_info=$order->where('uid','in',$nextId)->where($where)->where('status','>',1)->field('price,uid')->select();
            //echo $order->getLastSql();exit;
            if ($order_info){
                foreach($recommend_member as $ke=>$va){
                    $recommend_member[$ke]['num'] = 0;
                    $recommend_member[$ke]['price'] = 0.00;
                    foreach($order_info as $key=>$value){
                        $recommend_order_count++;
                        $recommend_money_count+=$value['price'];
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
            $data=['recommend_member_count'=>$recommend_member_count,'recommend_order_count'=>$recommend_order_count,'recommend_money_count'=>$recommend_money_count,'recommend_member'=>$recommend_member];
            parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
        }else{
            $data=['recommend_member_count'=>0,'recommend_order_count'=>0,'recommend_money_count'=>0,'recommend_member'=>[]];
            parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
        }
    }
}