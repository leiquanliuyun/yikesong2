<?php
namespace app\useradmin\controller;

use app\common\model\Member;
use app\common\model\Withdraworder;
use think\Controller;
use app\common\model\Order;
use JPush\Client;
use app\api\model\UserData;
/**
 * 订单控制器
 */
class  OrderController extends Controller
{
    /*订单列表  显示哪些订单啊？尚未接单的*/
    public function index()
    {
        $param=input('post.');
        //封装搜索条件
        $where = array();
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',$param['start_time']];
        } else {
            $param['start_time'] = '';
        }
        $this->assign('start_time', $param['start_time']);
        //结束时间
        if(!empty($param['end_time'])) {
            $where['update_time'] = ['<=',$param['end_time']];
        } else {
            $param['end_time'] = '';
        }
        $this->assign('end_time', $param['end_time']);
        //负责人
        if(!empty($param['liable'])) {
            $where['liable'] = $param['liable'];
        } else {
            $param['liable'] = '';
        }
        $this->assign('liable', $param['liable']);
        //服务名
        if(!empty($param['service_name'])) {
            $where['service_name'] = $param['service_name'];
        } else {
            $param['service_name'] = '';
        }
        $this->assign('service_name', $param['service_name']);
        //订单状态
        if(!empty($param['assign'])) {
            $where['assign'] = $param['assign'];
        } else {
            $param['assign'] = '';
        }
        $this->assign('assign', $param['assign']);
        $order = model('Order');
        $list = $order->where('assign','<',3)->where($where)->select();//halt($Servicecontent);
        //echo $order->getLastSql(); halt($list);
        foreach ($list as $k=>$v){
            /*$list[$k]['remark']=$v['remark']?$v['remark']:'订单正常';*/
            $mess="";
            if($v['content']!=""){
                if($v['service_name']=="工商变更" || $v['service_name']=="法律咨询"){
                    $allcontent = explode(";",$v['content']);
                    foreach($allcontent as $key=>$value){
                        $partcontent = explode(":",$value);
                        $mess.=$partcontent[0].":";
                        $content = explode(",",$partcontent[1]);
                        $i=0;
                        foreach($content as $ke=>$va){
                            $i++;
                            $mess.=$i.".".$va."&nbsp;&nbsp;";
                        }
                    }
                    $list[$k]['content'] = $mess;
                }else{
                    $content = explode(",",$v['content']);
                    $i=0;
                    $mess = "";
                    foreach($content as $ke=>$va){
                        $i++;
                        $mess.=$i.".".$va."&nbsp;&nbsp;";
                    }
                    $list[$k]['content'] = $mess;
                }
            }
        }
        $user_info=db('users')->where(['user_type'=>2,'status'=>1])->field('id,realname')->select();
        //渲染数据
        $this->assign('list', $list);
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }
    /*财税订单列表*/
    public function fiscal()
    {
        $param=input('post.');
        //封装搜索条件
        $where = array();
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',$param['start_time']];
        } else {
            $param['start_time'] = '';
        }
        $this->assign('start_time', $param['start_time']);
        //结束时间
        if(!empty($param['end_time'])) {
            $where['update_time'] = ['<=',$param['end_time']];
        } else {
            $param['end_time'] = '';
        }
        $this->assign('end_time', $param['end_time']);
        //负责人
        if(!empty($param['liable'])) {
            $where['liable'] = $param['liable'];
        } else {
            $param['liable'] = '';
        }
        $this->assign('liable', $param['liable']);

        //订单状态
        if(!empty($param['status'])) {
            if ($param['status']==6){
                $where['audit']=0;
            }else{
                $where['status'] = $param['status'];
            }
        } else {
            $param['status'] = '';
        }
        $this->assign('status', $param['status']);
        $order = model('Order');
        $list = $order->where('service_name','财税管理')->where('status','<>',5)->where($where)->select();//halt($Servicecontent);
        //echo $order->getLastSql(); halt($list);
        $time = get_time(time(),0);
        foreach ($list as $k=>$v){
            $list[$k]['remark']=$v['remark']?$v['remark']:'订单正常';
            //是否催单
            if($v['debt']==1&&$v['nextpay_time']!=""&&$time>$v['nextpay_time']&&$v['status']==4){
                $list[$k]['febt'] = 1;
            }else{
                $list[$k]['febt'] = 0;
            }
            $mess="";
            if($v['content']!=""){
                if($v['service_name']=="工商变更" || $v['service_name']=="法律咨询"){
                    $allcontent = explode(";",$v['content']);
                    foreach($allcontent as $key=>$value){
                        $partcontent = explode(":",$value);
                        $mess.=$partcontent[0].":";
                        $content = explode(",",$partcontent[1]);
                        $i=0;
                        foreach($content as $ke=>$va){
                            $i++;
                            $mess.=$i.".".$va."&nbsp;&nbsp;";
                        }
                    }
                    $list[$k]['content'] = $mess;
                }else{
                    $content = explode(",",$v['content']);
                    $i=0;
                    $mess = "";
                    foreach($content as $ke=>$va){
                        $i++;
                        $mess.=$i.".".$va."&nbsp;&nbsp;";
                    }
                    $list[$k]['content'] = $mess;
                }
            }
        }
        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /*积分提现订单列表*/
    public function integral_withdraw(){
        $param=input('post.');
        //封装搜索条件
        $where = array();
        //开始时间
        if(!empty($param['status'])) {
            $where['status'] = $param['status'];
        } else {
            $param['status'] = '';
        }
        $this->assign('status', $param['status']);
        $withdraw_order=new Withdraworder();
        $list=$withdraw_order->where($where)->select();
        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }

    /*积分提现申请审核通过*/
    public function confirm_withdraw(){
        $id=input('post.id');
        $withdraw_order=new Withdraworder();
        $info=$withdraw_order->where('id',$id)->field('status,uid')->find();
        if ($info){
            $account=db('user_account')->where(['uid'=>$info['uid'],'type'=>1])->find();
            if (!$account){
                $data['status'] = 'error';
                $data['msg']='用户没有绑定支付宝！';
            }
            $info->status=2;
            $result=$info->save();
            if($result) {
                $data['status'] = 'success';
            } else {
                $data['status'] = 'error';
                $data['msg']='操作失败';
            }
        }else{
            $data['status'] = 'error';
            $data['msg']='订单不存在';
        }
        //返回json数据
        return json($data);
    }
    /*积分提现申请审核退回*/
    public function back_withdraw(){
        $id=input('post.id');
        $withdraw_order=new Withdraworder();
        $info=$withdraw_order->where('id',$id)->field('status,uid,money')->find();
        if ($info){
            $info->status=3;
            $withdraw_order->startTrans();
            $result=$info->save();
            if($result) {
                $user=new Member();
                $rs=$user->where('id',$info['uid'])->setInc('integral',$info['money']);
                if($rs){
                    $withdraw_order->commit();
                    $data['status'] = 'success';
                }else{
                    $withdraw_order->rollback();
                    $data['status'] = 'error';
                    $data['msg']='操作失败';
                }
            } else {
                $data['status'] = 'error';
                $data['msg']='操作失败';
            }
        }else{
            $data['status'] = 'error';
            $data['msg']='订单不存在';
        }
        //返回json数据
        return json($data);
    }
    /*知识产权查询确认处理*/
    public function confirm_intell(){
        $id=input('post.id');
        $intell_property=model('IntellProperty');
        $info=$intell_property->where(['id'=>$id])->find();
        if ($info){

            if (!$info->member->realname){
                $data['status'] = 'error';
                $data['msg']='用户没有实名认证！';
            }
            $info->status=1;
            $result=$info->save();
            if($result) {
                $data['status'] = 'success';
            } else {
                $data['status'] = 'error';
                $data['msg']='操作失败';
            }
        }else{
            $data['status'] = 'error';
            $data['msg']='订单不存在';
        }
        //返回json数据
        return json($data);
    }
    /*添加、修改订单负责人*/
    public function changeLiable(){
        $param=input('post.');
        $order=model('Order');
        $r=db('users')->where('realname',$param['liable'])->find();
        if (!$r){
            $data['status']='error';
            $data['msg']='该负责人不存在！';
            return json($data);
        }
        $param['assign']=2;
        $result=$order->allowField(true)->save($param,['id'=>$param['id']]);//echo $order->getLastSql();exit;
        //判断是否修改成功
        if($result) {
            $app_key=config('jpush.app_key');
            $master_secret=config('jpush.master_secret');
            $client=new \JPush\Client($app_key,$master_secret,null);
            $client_arr = UserData::getGeTuiByUid( $r['id'] );
            $pusher = $client->push();
            $pusher->setPlatform('all');
            foreach ($client_arr as $key=>$val){//用户可能登录多个终端（员工端不需要发送此消息，会员端只能有一个终端登录，故其实不存在多个终端）
                $pusher->addRegistrationId($val['registration_id']);
                $pusher->setNotificationAlert('接到一条新订单，请注意查收！');
                $pusher->send();
               try {
                    $pusher->send();
                } catch (\JPush\Exceptions\JPushException $e) {
                    // try something else here
                    print $e;
                   Log::write('推送处理失败：'.$e->getMessage() , Log::ERROR);
                   Log::write('推送处理文件：'.$e->getFile() , Log::ERROR);
                   Log::write('推送处理行号：'.$e->getLine() , Log::ERROR);
                }
            }
            $data['status'] = 'success';
        } else {
            $data['status'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    /*添加订单价格*/
    public function changePrice(){
        $param=input('post.');
        $order=model('Order');
        $param['status']=2;
        $result=$order->allowField(true)->save($param,['id'=>$param['id']]);//echo $order->getLastSql();exit;
        //判断是否修改成功
        if($result) {
            //发送模板消息
            $orderinfo=$order->where('id',$param['id'])->field('status,uid')->find();
            $member=model('Member');
            $memberinfo=$member->where('id',$orderinfo['uid'])->find();
            $data['status'] = 'success';
            //发送模板消息给用户
           /* $muId = 'vTkCs5lzBFvpwvP_VOCX7o92iQbygMAXvi3iw4m4Jz4';
            $mOpenid = $memberinfo['openid'];
            $muData = editTemplateMessage($param['price'],$orderinfo['service_name'],$orderinfo['ordernum'],"","",1);
            $saveinfo=sendTemplateMessage($mOpenid,$muId,$muData);
            if ($saveinfo){
                $data['status'] = 'success';
            }else{
                $data['status'] = '发送模板消息失败';
            }*/
        } else {
            $data['status'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    /*添加订单价格*/
    public function change_price(){
        //判断是否是post提交方式
        if(request()->isPost()) {
            //订单表 验证器
            $validate = validate('Order');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            if (isset($param['mtc'])){
                switch($param['mtc']){
                    case 1://一季以後催賬
                        $param['nextpay_time'] = get_time(strtotime("+ 3 months",time()),0);
                        break;
                    case 2://半年以後催賬
                        $param['nextpay_time'] = get_time(strtotime("+ 6 months",time()),0);
                        break;
                    case 3://一年以後催賬
                        $param['nextpay_time'] = get_time(strtotime("+ 1 years",time()),0);
                        break;

                }
            }
            $order = model('Order');
            $param['status']=2;
            $result=$order->allowField(true)->save($param,['id'=>$param['id']]);
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                //$this->success('操作成功', 'order/fiscal','',1);
                $this->success('修改成功', url('member/member_detail',['id'=>$param['uid'],'index'=>1]),'',3);
                //$this->redirect( 'member/member_detail',['id'=>$param['uid']],5,'修改成功');
            } else {
                $this->error('操作失败！');
            }
        }
        //获取id数据
        $id = input('param.id');//订单id
        //订单表 模型
        $order = model('Order');
        $list=$order->where('id',$id)->find();//halt($list);
        //渲染数据
        $this->assign('list', $list);
        $this->assign('id', $id);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 合同 删除
     */
    public function order_delete()
    {
        //获取id数据
        $id = input('post.id');
        //合同项表 模型
        $order = model('Order');
        //删除数据
        $result=$order->save(['status'=>5],['id'=>$id]);
        //echo $order->getLastSql();exit;
        //如果存在合同，将删除合同
        $pact=model('Pact');
        $pactInfo=$pact->where('order_id',$id)->find();
        if ($pactInfo){
            $pactInfo->status=3;
            $pactInfo->save();
        }

        //判断是否删除成功
        if($result) {
            $data['status'] = 'success';
        } else {
            $data['status'] = 'error';
        }
        //返回json数据
        return json($data);
    }

    /*订单审核是否异常*/
    public function audit(){
        //判断是否是post提交方式
        if(request()->isPost()) {

            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            if ($param['audit']==1){
                $param['remark']="";
            }
            $order=model('Order');
            $result = $order->allowField(true)->save($param,['id'=>$param['id']]);
            if($result) {
                //添加用户操作记录
                if ($param['option']=='fiscal'){
                    $this->success('审核成功', 'order/fiscal','',1);
                }else{
                    $this->success('审核成功', 'order/index','',1);
                }
            } else {
                $this->error('审核失败！');
            }
        }

        //获取表id数据
        $id =  input('param.id');
        $option =  input('param.option');
        $order=model('Order');
        $list=$order->where('id',$id)->find();
        $this->assign('list', $list);
        $this->assign('id', $id);
        $this->assign('option', $option);
        //模板渲染
        return $this->fetch();
    }
    /*催单*/
    public function debt(){
        $id=input('param.id');
        $price=input('param.price');
        $order=model('Order');
        $order_info=$order->where('id',$id)->find();
        if ($order_info){
            $time=get_time(strtotime("+ 1 months",time()),0);
            if($order_info['service_name']=='财税管理'&&$order_info['debt']!=2&&$order_info['status']==4&&$order_info['nextpay_time']!=""&&$time>$order_info['nextpay_time']){
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
                    //$order_info->save();
                    //发送模板消息给用户
                    $order->commit();
                    $data['status'] = 'success';;
                    return json($data);
                } catch (\Exception $e) {
                    // 回滚事务
                    $order->rollback();
                    var_dump($e->getMessage());
                    $data['status'] = 'fail';
                    $data['msg'] = '非法操作';
                    return json($data);
                }

            }else{
                $data['status'] = 'fifa';
                $data['msg'] = '非法操作';
                return json($data);
            }
        }else{
            $data['status'] = 'fifa';
            $data['msg'] = '非法操作';
            return json($data);
        }
    }
    /*进度管理页面显示*/
    public function schedule(){
        $param=input('param.');
        $order=model('Order');
        $id=$param['id'];//订单id
        $orderInfo=$order->where('id',$id)->find();
        if($orderInfo&&$orderInfo['status']>=3) {
            $this->assign('ordernum', $orderInfo['ordernum']);
            $this->assign('id', $id);
            //获取进度
            $servicejd = model('Servicejd');
            $jindu = $servicejd->where('service_name', $orderInfo['service_name'])->order("create_time desc")->find();
            if ($jindu) {
                $jinduName = explode(",", $jindu['name']);
                $this->assign('sname', $orderInfo['service_name']);
                $schedule = model('OrderSchedule');
                $scheduleInfo = $schedule->where('order_id',$id)->select();
                //var_dump($scheduleInfo);
                $jinduInfo = array();
                foreach ($jinduName as $key => $value) {
                    $value = trim($value);
                    $jinduInfo[$key]['name'] = $value;
                }//var_dump($jinduInfo);
                if ($scheduleInfo) {
                    foreach ($jinduInfo as $k => $v) {
                        $is_exits=false;
                        foreach ($scheduleInfo as $ke => $va) {
                            if ($va['name'] == $v['name']) {
                                $jinduInfo[$k]['status'] = 1;
                                $jinduInfo[$k]['create_time'] = $va['create_time'];
                                $is_exits=true;
                            }
                        }
                        if (!$is_exits){
                            $jinduInfo[$k]['status'] = 0;
                        }
                    }
                }//var_dump($jinduInfo);exit;
                $this->assign('jinduname', $jinduInfo);//halt($jinduInfo);
                //模板渲染
                return $this->fetch();
            } else {
                $this->error('该服务暂没有进度规划！');//该服务没有进度规划
                exit;
            }
        }else{
            $this->error('该订单尚未支付，无法查看进度！');//该服务没有进度规划
            exit;
        }
    }
    //ajax确认进度完成
    public function confirm_schedule(){
        $sname = input('post.sname');//当前服务名
        $jindu = input('post.jindu');//当前进度名
        $id = input('post.id');//当前订单id
        if($sname!=""&&$jindu!=""&&$id>0) {
            $order = model('Order');
            $orderInfo = $order->where('id', $id)->find();
            if ($orderInfo && $orderInfo['status'] == 3 && $orderInfo['service_name'] == $sname) {
                //获取服务下的进度
                $servicejd = model('Servicejd');
                $serviceJindu = $servicejd->where('service_name', $sname)->order('create_time desc')->find();
                if ($serviceJindu) {
                    $jinduName = explode(',', $serviceJindu['name']);
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
                        $data['status'] = 'fifa';
                        $data['msg'] = '该订单不存在此进度';
                        return json($data);
                    }
                    //判断该订单下该进度是否已经存在
                    $schedule = model('OrderSchedule');
                    $scheduleInfo = $schedule->where('order_id', $id)->select();
                    if ($scheduleInfo) {
                        foreach ($scheduleInfo as $k => $v) {
                            if ($v['name'] == $jindu) {
                                $data['status'] = 'fifa';
                                $data['msg'] = '该进度已经完成';
                                return json($data);
                            }
                        }
                    }
                    if ($len > 0) {
                        if (!$scheduleInfo) {
                            $data['status'] = 'fifa';
                            $data['msg'] = '请先完成当前进度之前的进度';
                            return json($data);
                        }
                        $lenarray = array();
                        //判断该进度之前的进度是否已完成
                        for ($i = 0; $i < $len; $i++) {
                            $lenarray[$i] = trim($jinduName[$i]);//该进度前的所有进度
                        }//var_dump($lenarray); var_dump($scheduleInfo);
                        foreach ($lenarray as $k => $v) {
                            foreach ($scheduleInfo as $ke => $va) {//已完成的所有进度
                                if ($va['name'] == $v) {
                                    unset($lenarray[$k]);
                                }
                            }
                        }
                        if (!empty($lenarray)) {
                            $data['status'] = 'fifa';
                            $data['msg'] = '请先完成当前进度之前的进度';
                            return json($data);
                        }
                    }
                    $mess['uid'] = $orderInfo['uid'];
                    $mess['order_id'] = $id;
                    $mess['name'] = $jindu;
                    $schedule->startTrans();//开启订单进度事务
                    $addInfo = $schedule->allowField(true)->save($mess);//echo $schedule->getLastSql(); exit;
                    if ($addInfo) {
                        //判断是否是最后一个进度
                        if ($jindu == $end) {
                            //修改订单状态为已完成
                            $orderInfo->status=4;
                            $order->startTrans();//开启订单事务
                            $saveOrderInfo=$orderInfo->save();
                            //$saveOrderInfo = $order->where('id',$id)->save();
                            //echo $order->getLastSql(); exit;
                            if ($saveOrderInfo) {
                                //判断当前订单用户是否有推荐人，有的话给其推荐人增加积分
                                $member=model('Member');
                                $memberInfo = $member->where('id',$orderInfo['uid'])->find();
                                if ($memberInfo) {
                                    if ($memberInfo['liable'] != "" || $memberInfo['pcode'] != ""){
                                        if ($memberInfo['liable'] != "") {//有推荐人，增加推荐人的积分
                                            $parentInfo = $member->where('code',$memberInfo['liable'])->find();
                                        } elseif ($memberInfo['pcode'] != "" && $memberInfo['liable'] == "") {
                                            $parentInfo = $member->where('code',$memberInfo['pcode'])->find();
                                        }
                                        //获取当前服务对上级返回积分比例
                                        $integral=model('Integral');
                                        $integralInfo = $integral->where('service_name',$orderInfo['service_name'])->find();
                                        if ($integralInfo) {
                                            $integral = $integralInfo['integral'];
                                        } else {
                                            $integral = 0;
                                        }
                                        //$parentMess['id'] = $parentInfo['id'];
                                        $orderPrice = $orderInfo['pay_money'] * $integral;
                                        $parentInfo->integral=(int)$parentInfo['integral'] + (int)$orderPrice;
                                        $parentInfo->level_int=(int)$parentInfo['level_int'] + (int)$orderPrice;
                                        //$parentMess['integral'] = (int)$parentInfo['integral'] + (int)$orderPrice;
                                        //$parentMess['level_int'] = (int)$parentInfo['level_int'] + (int)$orderPrice;
                                        $saveMemberInfo = $parentInfo->save();
                                        if ($saveMemberInfo) {
                                            $schedule->commit();//订单进度事务提交
                                            $order->commit();//订单事务提交
                                            $data['status'] = 'success';
                                            return json($data);
                                        } else {
                                            $schedule->rollback();//订单进度事务回滚
                                            $order->rollback();//订单事务回滚
                                            $data['status'] = 'fail';
                                            $data['msg'] = '上级积分返回失败';
                                            return json($data);
                                        }
                                    } else {//没有推荐人，无需增加推荐人积分
                                        $schedule->commit();//订单进度事务提交
                                        $order->commit();//订单事务提交
                                        $data['status'] = 'success';
                                        return json($data);
                                    }

                                } else {
                                    $schedule->rollback();//订单进度事务回滚
                                    $order->rollback();//订单事务回滚
                                    $data['status'] = 'fail';
                                    $data['msg'] = '确认进度失败';
                                    return json($data);
                                }
                            } else {
                                $schedule->rollback();//订单进度事务回滚
                                $data['status'] = 'fail';
                                $data['msg'] = '订单用户不存在';
                                return json($data);
                            }

                        } else {
                            $schedule->commit();//提交订单进度事务
                            $data['status'] = 'success';
                            return json($data);
                        }
                    } else {
                        $data['status'] = 'fail';
                        $data['msg'] = '添加订单进度失败';
                        return json($data);
                    }
                } else {
                    $data['status'] = 'fifa';
                    $data['msg'] = '该订单服务暂没有进度管理';
                    return json($data);
                }
            } else {
                $data['status'] = 'fifa';
                $data['msg'] = '非法操作';
                return json($data);
            }
        }else{
            $data['status'] = 'fifa';
            $data['msg'] = '非法操作';
            return json($data);
        }
    }
}