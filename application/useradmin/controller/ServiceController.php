<?php
namespace app\useradmin\controller;

use think\Controller;
/**
 * 服务控制器
 */
class  ServiceController extends Controller
{
    public function index()
    {
        $servicecontent = model('Servicecontent');
        $list = $servicecontent->select();//halt($servicecontent);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 服务添加、修改操作
     */
    public function servicecontent_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务表 验证器
            $validate = validate('Servicecontent');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //处理服务内容数据
            if(!empty($param['introduce_mess'])) {
                $param['introduce_mess']=htmlspecialchars_decode($param['introduce_mess']);
            }
            if(!empty($param['trade_type'])) {
                $param['trade_type']=htmlspecialchars_decode($param['trade_type']);
            }//echo '<pre>' ;var_dump($param);exit;
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //服务表 模型
            $servicecontent = model('Servicecontent');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                //$param['create_time']=date("Y-m-d H:i:s",time());
                $result = $servicecontent->allowField(true)->save($param);
                //获取表自增id
                $id = $servicecontent->content_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $servicecontent->allowField(true)->save($param,['content_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'service/index','',1);
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
            //服务表 模型
            $servicecontent = model('Servicecontent');
            //查询服务信息
            $list = $servicecontent->get($id);
        } else {
            $list=['introduce_mess'=>'','trade_type'=>'','service'=>''];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 服务 删除
     */
    public function servicecontent_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务表 模型
        $servicecontent = model('Servicecontent');
        //删除数据
        $list = $servicecontent::get($id);//echo $servicecontent->getLastSql();exit;
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
     * 服务 批量删除
     */
    public function servicecontent_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //服务表 模型
        $servicecontent = model('Servicecontent');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $servicecontent::get($value);
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
    /*服务进度*/
    public function schedule()
    {
        $servicejd = model('Servicejd');
        $list = $servicejd->select();//halt($servicejd);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 服务进度添加、修改操作
     */
    public function servicejd_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务表 验证器
            $validate = validate('Servicejd');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;

            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //服务表 模型
            $servicejd = model('Servicejd');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $param['create_time']=date("Y-m-d H:i:s",time());
                $id=$servicejd->where('service_name',$param['service_name'])->value('id');//halt($id);
                if ($id){
                    $param['id']=$id;
                    $result = $servicejd->allowField(true)->save($param,['id'=>$id]);echo $servicejd->getLastSql(); exit;
                }else{
                    $result = $servicejd->allowField(true)->save($param);
                }

                //获取表自增id
                $id = $servicejd->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $servicejd->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'service/schedule','',1);
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
            //服务表 模型
            $servicejd = model('Servicejd');
            //查询服务信息
            $list = $servicejd->get($id);
        } else {
            $list=['service_name'=>'','name'=>''];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 服务进度 删除
     */
    public function servicejd_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务表 模型
        $servicejd = model('Servicejd');
        //删除数据
        $list = $servicejd::get($id);//echo $servicejd->getLastSql();exit;
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

    /*服务积分*/
    public function Integral()
    {
        $integral = model('Integral');
        $list = $integral->order('create_time desc')->select();//halt($integral);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 服务积分添加、修改操作
     */
    public function integral_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务表 验证器
            $validate = validate('Integral');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;

            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //服务表 模型
            $integral = model('Integral');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $param['create_time']=date("Y-m-d H:i:s",time());
                $id=$integral->where('service_name',$param['service_name'])->value('id');//halt($id);
                if ($id){
                    $param['id']=$id;
                    $result = $integral->allowField(true)->save($param,['id'=>$id]);//echo $integral->getLastSql(); exit;
                }else{
                    $result = $integral->allowField(true)->save($param);
                }

                //获取表自增id
                $id = $integral->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $integral->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'service/integral','',1);
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
            //服务表 模型
            $integral = model('Integral');
            //查询服务信息
            $list = $integral->get($id);
        } else {
            $list=['service_name'=>'','name'=>''];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 服务积分 删除
     */
    public function integral_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务表 模型
        $integral = model('Integral');
        //删除数据
        $list = $integral::get($id);//echo $integral->getLastSql();exit;
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
    //服务选项列表
    public function csituation(){
        $csituation=model('Csituation');
        $list=$csituation->select();
        $info=$list;
        foreach($list as $k=>$v){
            foreach($info as $ke=>$va){
                if($v['pid']==$va['cs_id']){
                    $list[$k]['pname'] = $va['name'];
                    break;
                }
            }
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 服务选项添加操作
     */
    public function csituation_add()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务选项表 验证器
            $validate = validate('Csituation');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //服务选项表 模型
            $csituation = model('Csituation');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $csituation->allowField(true)->save($param);
                //获取表自增id
                $id = $csituation->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $csituation->allowField(true)->save($param,['cs_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'service/csituation','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        //$this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }
    //通过所属服务获取其顶级分类
    public function getClass(){
        $service_name=input('post.service');
        $csituation=model('Csituation');
        $info=$csituation->where(['service_name'=>$service_name,'pid'=>0])->field('cs_id,name')->select();
        if($info){
            $data['status'] = 'success';
            $data['msg'] = $info;
        }else{
            $data['status'] = 'fail';
        }
        //返回json数据
        return json($data);
    }
    //ajax判断当前导航下筛选条件是否存在
    public function checkClass(){
        $service_name=input('post.service');
        $name=input('post.name');
        $csituation=model('Csituation');
        $info=$csituation->where(['service_name'=>$service_name,'name'=>$name])->find();
        if($info){
            $data['status'] = 'fail';
            $data['msg'] = '该服务下该分类已存在,请勿重复添加';
        }else{
            $data['status'] = 'success';
        }
        //返回json数据
        return json($data);
    }
    /**
     * 服务选项 删除
     */
    public function csituation_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务选项表 模型
        $csituation = model('Csituation');
        //删除数据
        $list = $csituation::get($id);
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
     * 服务选项 批量删除
     */
    public function csituation_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //服务选项表 模型
        $csituation = model('Csituation');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $csituation::get($value);
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