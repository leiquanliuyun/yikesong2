<?php
namespace app\useradmin\controller;

use think\Controller;
/**
 * 合同控制器
 */
class  PactController extends Controller
{
    /*合同项*/
    public function pact_option()
    {
        $pactoption = model('PactOption');
        $list = $pactoption->select();//halt($Servicecontent);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 合同项添加、修改操作
     */
    public function pactoption_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //合同项表 验证器
            $validate = validate('PactOption');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //合同项表 模型
            $pactoption = model('PactOption');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                $result = $pactoption->allowField(true)->save($param);
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $pactoption->allowField(true)->save($param,['option_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'pact/pact_option','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        $list = array();
        if($option == 'update') {
            //合同项表 模型
            $pactoption = model('PactOption');
            //查询合同项信息
            $list = $pactoption->get($id);
        } else {
            $list=[];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 合同项 删除
     */
    public function pactoption_delete()
    {
        //获取id数据
        $id = input('post.id');
        //合同项表 模型
        $pactoption = model('PactOption');
        //删除数据
        $list = $pactoption::get($id);//echo $pactoption->getLastSql();exit;
        $result = $list->delete();

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
     * 合同项 批量删除
     */
    public function pactoption_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //合同项表 模型
        $pactoption = model('PactOption');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $pactoption::get($value);
            $result = $list->delete();
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

    /*合同模板*/
    public function pact_temp()
    {
        $pacttemp = model('PactTemp');
        $list = $pacttemp->select();//halt($Servicecontent);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 合同模板添加、修改操作
     */
    public function pacttemp_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //合同模板表 验证器
            $validate = validate('PactTemp');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            $param['options']=json_encode($param['options']);
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //合同模板表 模型
            $pacttemp = model('PactTemp');
            if($option == 'add'){
                $temp=$pacttemp->where('options',$param['options'])->find();
                if ($temp){
                    $this->error('模板已存在，请不要重复添加！');
                }
                //添加,过滤非数据表字段的数据
                $result = $pacttemp->allowField(true)->save($param);
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $pacttemp->allowField(true)->save($param,['temp_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'pact/pact_temp','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');
        $pactoption=model('PactOption');
        $options=$pactoption->select();
        $list = array();
        if($option == 'update') {
            //合同模板表 模型
            $pacttemp = model('PactTemp');
            //查询合同模板信息
            $list = $pacttemp->get($id);
            $list['options']=json_decode($list['options']);
        } else {
            $list=[];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('options', $options);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 合同模板 删除
     */
    public function pacttemp_delete()
    {
        //获取id数据
        $id = input('post.id');
        //合同模板表 模型
        $pacttemp = model('PactTemp');
        //删除数据
        $list = $pacttemp::get($id);//echo $pacttemp->getLastSql();exit;
        $result = $list->delete();

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
     * 合同模板 批量删除
     */
    public function pacttemp_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //合同模板表 模型
        $pacttemp = model('PactTemp');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $pacttemp::get($value);
            $result = $list->delete();
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
    /*合同*/
    public function pact()
    {
        $pact = model('Pact');
        $list = $pact->with('member,order')->select(); //echo $pact->getLastSql();  halt($list);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /*选择合同模板 可采用表单提交，将模板id参数传递到合同编辑页面,未采用*/
    public function choose_temp()
    {
        $pacttemp = model('PactTemp');
        $list = $pacttemp->order('temp_id')->select(); //echo $pact->getLastSql();  halt($list);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 合同添加操作
     */
    public function pact_add()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //合同表 验证器
            $validate = validate('Pact');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //拼合同内容
            $mess=$param;
            unset($mess['ordernum']);
            unset($mess['temp_id']);
            unset($mess['master']);
            unset($mess['liable']);
            unset($mess['option']);
            unset($mess['order_id']);
            unset($mess['uid']);
            unset($mess['__token__']);
            foreach ($mess as $key=>$val){
                $mess[$key]=htmlspecialchars_decode($val);
            }
            $param['detail']=json_encode($mess);
            //订单合同雇主信息
            $order=model('Order');
            /*$orderinfo=$order->where('id',$param['order_id'])->find();
            $param['uid']=$orderinfo['uid'];
            $param['master']=$orderinfo->member->realname;*/
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }

            //获取数据标签和表id
            $option = $param['option'];
            $order_id=$param['order_id'];
            //合同表 模型
            $pact = model('Pact');
            if($option == 'add'){//halt($param);
                //添加,过滤非数据表字段的数据
                $param['options']=db('pact_temp')->where('temp_id',$param['temp_id'])->value('options');
                $result = $pact->allowField(true)->save($param); //echo $pact->getLastSql();  halt($result);
                //更新订单表的合同状态
                $order->save(["pact"=>2],['id'=>$param['order_id']]);
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $pact->allowField(true)->save($param,['order_id'=>$order_id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                //$this->success('操作成功', 'pact/pact','',1);
                $this->redirect( 'member/member_detail',['id'=>$param['uid']],5,'操作成功');
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取所有合同项
        $id =  input('param.order_id');//订单id
        $pactoption=model('PactOption');
        $alloptions=$pactoption->select();
        $stringoptions=json_encode($alloptions);
        //获取订单信息
        $order=model('Order');
        $orderinfo=$order->where('id',$id)->field('id,ordernum,uid')->find();
        //默认选中第一个合同模板
        $pacttemp=model('PactTemp');
        $temps=$pacttemp->order('temp_id')->select();
        $temp=$pacttemp->order('temp_id')->find();
        $options=json_decode($temp['options'],true); //默认显示合同项
        $options=json_encode($options);

        //渲染数据
        $this->assign('option', $option);
        $this->assign('options', $options);
        $this->assign('alloptions', $alloptions);
        $this->assign('stringoptions', $stringoptions);
        $this->assign('orderinfo', $orderinfo);
        $this->assign('temps', $temps);
        $this->assign('id', $id);

        //模板渲染
        return $this->fetch();
    }
    /*修改合同模板*/
    public function changeTemp(){
        if (request()->isAjax()){
            $param=input('post.');
            $pacttemp=model('PactTemp');
            $options=$pacttemp->where('temp_id',$param['id'])->find();
            $options=json_decode($options['options'],true);
            $pactoption=model('PactOption');
            $alloptions=$pactoption->order('option_id')->select();
            $data['options']=$options;
            $data['alloptions']=$alloptions;
            //返回数据
            return json(array('status'=>'200','msg'=>'success','data'=>$data));
        }else{
            return json(array('status'=>'201','msg'=>'更换合同模板失败'));
        }
    }
    /**
     * 合同修改操作
     */
    public function pact_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //合同表 验证器
            $validate = validate('Pact');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //拼合同内容
            $mess=$param;
            unset($mess['ordernum']);
            unset($mess['temp_id']);
            unset($mess['master']);
            unset($mess['liable']);
            unset($mess['option']);
            unset($mess['order_id']);
            unset($mess['uid']);
            unset($mess['__token__']);
            foreach ($mess as $key=>$val){
                $mess[$key]=htmlspecialchars_decode($val);
            }
            $param['detail']=json_encode($mess);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            //合同表 模型
            $pact = model('Pact');
            if($option == 'add'){//
                //添加,过滤非数据表字段的数据
                $result = $pact->allowField(true)->save($param); //echo $pact->getLastSql();  halt($result);
            }elseif($option == 'update'){//halt($param);
                //更新,过滤非数据表字段的数据
                $result = $pact->allowField(true)->save($param,['order_id'=>$param['order_id']]);//echo $pact->getLastSql();  halt($result);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                //$this->success('操作成功', 'pact/pact','',1);
                $this->redirect( 'member/member_detail',['id'=>$param['uid']],5,'操作成功');
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取所有合同项
        $id =  input('param.order_id');//订单id
        $pactoption=model('PactOption');
        $alloptions=$pactoption->select();
        $stringoptions=json_encode($alloptions);
        //获取合同信息
        $pact=model('Pact');
        $list=$pact->where('order_id',$id)->find();
        //合同内容处理
        $pact=json_decode($list['detail'],true);//halt($pact);
        $pacttemp=model('PactTemp');
        //$temps=$pacttemp->order('temp_id')->select();
        $temp=$pacttemp->where('temp_id',$list['temp_id'])->find();
        $options=json_decode($temp['options'],true); //默认显示合同项
        $options=json_encode($options);
        //halt($pact);
        //渲染数据
        $this->assign('option', $option);
        $this->assign('options', $options);
        $this->assign('alloptions', $alloptions);
        $this->assign('stringoptions', $stringoptions);
        //$this->assign('orderinfo', $orderinfo);
        $this->assign('list', $list);
        $this->assign('pact', $pact);
        $this->assign('id', $id);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 合同修改操作
     */
    public function pact_edit2()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //合同表 验证器
            $validate = validate('Pact');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            $param['options']=json_encode($param['options']);
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //合同表 模型
            $pact = model('Pact');
            if($option == 'add'){
                $temp=$pact->where('options',$param['options'])->find();
                if ($temp){
                    $this->error('模板已存在，请不要重复添加！');
                }
                //添加,过滤非数据表字段的数据
                $result = $pact->allowField(true)->save($param);
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $pact->allowField(true)->save($param,['temp_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'pact/pact_temp','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');
        $pactoption=model('PactOption');
        $options=$pactoption->select();
        $list = array();
        if($option == 'update') {
            //合同表 模型
            $pact = model('Pact');
            //查询合同信息
            $list = $pact->get($id);
            $list['options']=json_decode($list['options']);
        } else {
            $list=[];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('options', $options);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 合同 删除
     */
    public function pact_delete()
    {
        //获取id数据
        $id = input('post.id');
        //合同表 模型
        $pact = model('Pact');
        //删除数据
        $list = $pact::get($id);//echo $pact->getLastSql();exit;
        $result = $list->delete();

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
     * 合同 批量删除
     */
    public function pact_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //合同表 模型
        $pact = model('Pact');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $pact::get($value);
            $result = $list->delete();
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