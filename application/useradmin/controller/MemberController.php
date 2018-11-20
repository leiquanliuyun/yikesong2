<?php
namespace app\useradmin\controller;

use app\useradmin\validate\Pact;
use think\Controller;
/**
 * 会员控制器
 */
class  MemberController extends Controller{
    /**
     * 会员列表管理
     */
    public function member()
    {
        //获取数据
        //$param = input('post.');

        //封装搜索条件
        $where = array();

        //会员用户名称,昵称，邮箱
        /*if(!empty($param['keyword'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }*///echo '<pre>'; print_r($where);
        //用户表 模型
        $member = model('Member');
        //查询用户表和会员 数据
        $list = $member->all($where);//此处如果加上toArray()后，前端就无法用获取器获取的属性
        $back_list=$list;
        foreach($list as $k=>$v){
            $list[$k]['recommend_count']=0;
            foreach ($back_list as $key=>$val){
                if ($val['pcode']==$v['code'] || $val['liable']==$v['code']){
                    $list[$k]['recommend_count']+=1;
                }
            }
            /*if(!empty($v['id_photo'])) {
                if (strpos($v['id_photo'],'##')){
                    $rs= explode("##",$v['id_photo']);
                    foreach ($rs as $key=>$val) {
                        $rs[$key]= __PUBLIC__."/uploads/images/".$val;
                    }
                }else{
                    $rs=__PUBLIC__."/uploads/images/".$v['id_photo'];
                }
                //多图地址
            }*/
        }//halt($list);
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 会员优惠券管理页
     */
    public function member_coupon()
    {
        $id=input('param.id');
        //用户表 模型
        $coupon = model('MemberCoupon');
        //查询优惠券数据
        $list = $coupon->alias('a')->join('cmf_coupon b','a.coupon_id=b.coupon_id')
            ->field('a.status,a.use_time,a.end_time,b.coupon_id,b.name,b.service_name,b.price,b.fullprice')
            ->where('a.uid',$id)->select();
        foreach ($list as $k=>$v){
            if ($v['status']==2){
                $list[$k]['status_show']='已使用';
            }else{
                if (strtotime($v['end_time'])<time()){
                    $list[$k]['status_show']='已过期';
                }else{
                    $list[$k]['status_show']='未使用';
                }
            }
        }
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 会员推荐会员管理页
     */
    public function member_recommend()
    {
        $id=input('param.id');
        //用户表 模型
        $member = model('Member');
        $member_info=$member->where('id',$id)->field('code')->find();
        //查询推荐会员数据
        $list = $member->where('pcode|liable',$member_info['code'])
            ->field('id,nickname,realname,username,sex,mobile,create_time')->select();
        if ($list){
            $id_list=[];
            foreach ($list as $k=>$v){
                $id_list[]=$v['id'];
                $list[$k]['order_count']=0;
                $list[$k]['total_money']=0;
            }
            $order=model('Order');
            $order_info=$order->where('uid','in',$id_list)->field('uid,price')->select();
            if ($order_info){
                foreach($list as $k=>$v){
                    foreach ($order_info as $key=>$val){
                        if ($val['uid']==$v['id']){
                            $list[$k]['order_count']+=1;
                            $list[$k]['total_money']+=$val['price'];
                        }
                    }
                }
            }
        }
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 会员详细信息页  名下公司在哪获取信息啊？
     */
    public function member_detail()
    {
        //$id=input('param.id');//halt($id);//用户id
        //$index=input('param.index');
        $param=input('param.');
        $uid=$param['id'];//用户id
        $index=isset($param['index'])?$param['index']:0;//控制显示哪个选项卡
        //用户表 模型
        $member = model('Member');
        $member_info=$member->where('id',$uid)->field('nickname,realname,mobile,picture,code,finance_detail')->find();
        //财务相关信息
        if ($member_info['finance_detail']){
            $finance_info=json_decode($member_info['finance_detail'],true);
        }else{
            $finance_info=['is_open_billing'=>1,'is_consign_billing'=>1,'is_open_shebao'=>1,'is_consign_shebao'=>1,
                'is_open_pfund'=>2,'is_consign_pfund'=>1,'is_open_receipt'=>1,'is_consign_receipt'=>1];
        }
        $company_list=[];
        //查询会员订单数据
        $order=model('Order');
        $order_list=$order->alias('a')->join('cmf_pact b','a.id=b.order_id','left')
            ->where('a.uid',$uid)->where('a.liable','not null')
            ->field('a.id,a.service_name,a.audit,a.debt,a.pact,a.nextpay_time,a.status as order_status,a.liable,a.create_time,a.remark,a.ordernum,a.price,b.name,b.master_sign_time,b.status,b.id as pact_id')
            ->select();
        if ($order_list){
            $order_schedule=model('OrderSchedule');
            $time=get_time(strtotime("+ 1 months",time()),0);
            foreach ($order_list as $k=>$v){
                $jindu=$order_schedule->where('order_id',$v['id'])
                    ->order('create_time desc')->value('name');
                $order_list[$k]['jindu']=$jindu?$jindu:'暂无进度';//暂改为进度管理与查看
                //合同状态
                if ($v['pact'])
                $order_list[$k]['pact_status']=$v['status']==1?'待签订':'已签订';
                //是否催单
                if($v['service_name']=='财税管理'&&$v['debt']==1&&$v['nextpay_time']!=""&&$time>$v['nextpay_time']&&$v['order_status']==4){
                    $order_list[$k]['febt'] = 1;
                }else{
                    $order_list[$k]['febt'] = 0;
                }
            }
        }//halt($order_list);
        //查看会员合同数据
       /* $pact=model('Pact');
        $pact_list=$pact->alias('a')->join('cmf_order b','a.order_id=b.id')
            ->field('a.name,a.master_sign_time,a.status,a.order_id,a.id,b.price')
            ->where('a.uid',$uid)->select();*/
        $user_info=db('users')->where(['user_type'=>2,'status'=>1])->field('id,realname')->select();//用于选择订单负责人
        //提现订单
        $withdraw_order=model('Withdraworder');
        $withdraw_list=$withdraw_order->where(['uid'=>$uid])->select();
        //知识产权查询
        $intell_property=model('IntellProperty');
        $intell_list=$intell_property->where(['uid'=>$uid])->select();//echo $intell_property->getLastSql(); exit;
        //渲染数据
        $this->assign('order_list', $order_list);
        $this->assign('withdraw_list', $withdraw_list);
        $this->assign('intell_list', $intell_list);
        $this->assign('id', $uid);
        $this->assign('index', $index);
        $this->assign('company_list', $company_list);
        $this->assign('user_info', $user_info);
        $this->assign('finance_info', $finance_info);
        $this->assign('member_info', $member_info);
        //模板渲染
        return $this->fetch();
    }
    /*会员财务相关*/
    public function finance(){
        //判断是否是post提交方式
        if(request()->isPost()) {

            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            $uid=$param['uid'];
            $member=model('Member');
            unset($param['uid']);
            unset($param['__token__']);
            $addmember=[];
            $addmember['finance_detail']=json_encode($param);//echo '<pre>' ;var_dump($addmember);exit;
            $result = $member->allowField(true)->save($addmember,['id'=>$uid]);
            if($result) {
                $this->success('操作成功', url('member/member_detail',['id'=>$uid,'index'=>5]),'',3);
                //$this->redirect( 'member/member_detail',['id'=>$param['uid']],5,'审核成功');
            } else {
                $this->error('操作失败！');
            }
        }
    }
    /*订单审核是否异常*/
    public function order_audit(){
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
                $this->success('审核成功', url('member/member_detail',['id'=>$param['uid'],'index'=>1]),'',3);
                //$this->redirect( 'member/member_detail',['id'=>$param['uid']],5,'审核成功');
            } else {
                $this->error('审核失败！');
            }
        }

        //获取表id数据
        $id =  input('param.id');
        $uid= input('param.uid');
        $order=model('Order');
        $list=$order->where('id',$id)->find();//halt($list);
        $this->assign('list', $list);
        $this->assign('id', $id);
        $this->assign('uid', $uid);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 会员用户 删除
     */
    public function member_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('Member');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>0],['id'=>$id]);
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    /**
     * 会员用户 批量删除
     */
    public function member_batch_delete()
    {
        //获取id数据
        $id_array = input('post.');//halt($id_array);
        //用户表 模型
        $user = model('Member');
        //删除数据,用户状态0为删除
        $is_success=true;
        foreach($id_array['id_array'] as $key=>$value) {
            $result = $user->save(['status'=>0],['id'=>$value]);
            if (!$result){
                $is_success=false;
                break;
            }
        }
        //判断是否删除成功
        if($is_success) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    /**
     * 管理员列表
     */
    public function admin()
    {
        $param=input('post.');
        $where['user_type']=['in',[1,2]];
        $where['status']=['<>',3];
        if (!empty($param['user_login'])){
            $where['user_login']=['like',"%{$param['user_login']}%"];
        }

        $users=model("Users");
        $list=$users->where($where)->select();  //echo $users->getLastSql(); halt($list);
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 管理员用户添加、修改操作
     */
    public function admin_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('Users');

            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }

            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('Users');
            $param['salt'] = randCode(8);                                           //加密串
            $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
            if($option == 'add'){
                //封装处理所需参数
                $r=db('users')->where('mobile',$param['mobile'])->find();
                if ($r){
                    $this->error('该管理员已添加！');
                }
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->id;
               /* //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_admin->allowField(true)->save($param);
                    //获取表自增id
                    $admin_id = $info_admin->admin_id;

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 1;      //超级管理员组别
                    $auth_group_access->save($param_access);
                }*/
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'member/admin','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        $list = array('user_type'=>5);
        if($option == 'update') {
            //用户表 模型
            $user = model('Users');
            //查询管理员用户数据
            $list = $user->get($id);
        }
        $role=model("Role");
        $roles=$role->where('status',1)->select();//halt($roles);
        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        $this->assign('roles',$roles);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 管理员 启用/停用
     */
    public function status()
    {
        //获取id数据
        $id = input('post.id');
        //获取用户状态值
        $show = input('post.show');
        //用户表 模型
        $user = model('Users');
        //更新数据
        $result = $user->save([
            'status'  => $show
        ],['id' => $id]);
        //判断是否修改成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    /**
     * 管理员用户 删除
     */
    public function admin_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('Users');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['id'=>$id]);
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 管理员用户 批量删除
     */
    public function admin_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //用户表 模型
        $user = model('Users');
        //删除数据,用户状态3为删除
        foreach($id_array['id_array'] as $key=>$value) {
            $result = $user->update(['id' => $value, 'status' => 3]);
        }
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }
}