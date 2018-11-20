<?php
namespace app\useradmin\controller;

use think\Controller;
use app\common\model\Phone;
use app\common\model\Filter;
/**
 * 特殊服务控制器（园区服务，资质转让等）
 */
class  SpecialServiceController extends Controller
{
    //客服列表
    public function phone(){
        $phone=new Phone();
        $list=$phone->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 客服添加、修改操作
     */
    public function phone_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //客服表 验证器
            $validate = validate('Phone');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //客服表 模型
            $phone = model('Phone');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $phone->allowField(true)->save($param);
                //获取表自增id
                $id = $phone->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $phone->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'specialservice/phone','',1);
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
            //客服表 模型
            $phone = model('Phone');
            //查询客服信息
            $list = $phone->get($id);
        } else {
            $list['service_name'] = '';
            $list['phone'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 客服 删除
     */
    public function phone_delete()
    {
        //获取id数据
        $id = input('post.id');
        //客服表 模型
        $phone = model('Phone');
        //删除数据
        $list = $phone::get($id);
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
    //服务筛选条件列表
    public function filter(){
        $filter=new Filter();
        $list=$filter->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 服务筛选条件添加、修改操作
     */
    public function filter_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务筛选条件表 验证器
            $validate = validate('Filter');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //服务筛选条件表 模型
            $filter = model('Filter');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $filter->allowField(true)->save($param);
                //获取表自增id
                $id = $filter->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $filter->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'specialservice/filter','',1);
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
            //服务筛选条件表 模型
            $filter = model('Filter');
            //查询服务筛选条件信息
            $list = $filter->get($id);
        } else {
            $list['service_name'] = '';
            $list['filter'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 服务筛选条件 删除
     */
    public function filter_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务筛选条件表 模型
        $filter = model('Filter');
        //删除数据
        $list = $filter::get($id);
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
    public function housecontent()
    {
        $housecontent = model('Housecontent');
        $list = $housecontent->select();//halt($housecontent);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 服务添加、修改操作
     */
    public function housecontent_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //服务表 验证器
            $validate = validate('Housecontent');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //处理服务内容数据
            if(!empty($param['introduce_mess'])) {
                $param['introduce_mess']=htmlspecialchars_decode($param['introduce_mess']);
            }
            if(!empty($param['trade_type'])) {
                $param['trade_type']=htmlspecialchars_decode($param['trade_type']);
            }//echo '<pre>' ;var_dump($param);exit;
            $param['filter_id']=json_encode($param['filter_id']);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];//halt($param);
            $id = $param['id'];
            //服务表 模型
            $housecontent = model('Housecontent');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                //$param['create_time']=date("Y-m-d H:i:s",time());
                $result = $housecontent->allowField(true)->save($param);
                //获取表自增id
                $id = $housecontent->content_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $housecontent->allowField(true)->save($param,['content_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'specialservice/housecontent','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');
        //筛选条件
        $filter=model('Filter');
        $filter_list=$filter->where('service_name','园区服务')->select();
        $list = array();
        if($option == 'update') {
            //服务表 模型
            $housecontent = model('Housecontent');
            //查询服务信息
            $list = $housecontent->get($id);
            $filter_id=json_decode($list['filter_id'],true);
            $filter_id=json_encode($filter_id);
        } else {
            $list=['introduce_mess'=>'','trade_type'=>'','housetype'=>'','filter_id'=>''];
            $filter_id=[];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('filter_list', $filter_list);
        $this->assign('filter_id', $filter_id);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 服务 删除
     */
    public function housecontent_delete()
    {
        //获取id数据
        $id = input('post.id');
        //服务表 模型
        $housecontent = model('Housecontent');
        //删除数据
        $list = $housecontent::get($id);//echo $housecontent->getLastSql();exit;
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
    public function companycontent()
    {
        $companycontent = model('Companycontent');
        $list = $companycontent->select();//halt($companycontent);

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 资质转让内容添加、修改操作
     */
    public function companycontent_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //资质转让内容表 验证器
            $validate = validate('Companycontent');
            //表单提交数据
            $param = input('post.'); //echo '<pre>' ;var_dump($param);exit;
            //处理资质转让内容内容数据
            if(!empty($param['introduce_mess'])) {
                $param['introduce_mess']=htmlspecialchars_decode($param['introduce_mess']);
            }
            if(!empty($param['trade_type'])) {
                $param['trade_type']=htmlspecialchars_decode($param['trade_type']);
            }//echo '<pre>' ;var_dump($param);exit;
            $param['filter_id']=json_encode($param['filter_id']);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];//halt($param);
            $id = $param['id'];
            //资质转让内容表 模型
            $companycontent = model('Companycontent');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                //$param['create_time']=date("Y-m-d H:i:s",time());
                $result = $companycontent->allowField(true)->save($param);
                //获取表自增id
                $id = $companycontent->content_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $companycontent->allowField(true)->save($param,['content_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'specialservice/companycontent','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');
        //筛选条件
        $filter=model('Filter');
        $filter_list=$filter->where('service_name','资质转让')->select();
        $list = array();
        if($option == 'update') {
            //资质转让内容表 模型
            $companycontent = model('Companycontent');
            //查询资质转让内容信息
            $list = $companycontent->get($id);
        } else {
            $list=['introduce_mess'=>'','trade_type'=>'','filter_id'=>''];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('filter_list', $filter_list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 资质转让内容 删除
     */
    public function companycontent_delete()
    {
        //获取id数据
        $id = input('post.id');
        //资质转让内容表 模型
        $companycontent = model('Companycontent');
        //删除数据
        $list = $companycontent::get($id);//echo $companycontent->getLastSql();exit;
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
}