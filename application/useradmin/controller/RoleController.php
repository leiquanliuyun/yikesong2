<?php
namespace app\useradmin\controller;

/**
 * 角色、权限 控制器
 *
 */
class  RoleController extends CommonController
{
    /**
     * 角色管理
     */
    public function role()
    {
        //获取数据
        //$param = input('post.');

        //规则用户组表 模型
        $role= model('Role');
        //查询角色数据
        $list = $role->select();
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 角色添加、修改操作
     */
    public function role_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //规则用户组表 验证器
            $validate = validate('Role');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //规则用户组表 模型
            $role = model('Role');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $role->allowField(true)->save($param);
                //获取表自增id
                $id = $role->id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $role->allowField(true)->save($param,['id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'role/role','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        $list = array('status'=>1);
        $list['rules'] = array();
        //判断页面标签
        if($option == 'update') {
            //规则用户组表 模型
            $role = model('Role');
            //查询角色信息
            $list = $role->get($id);
        }

        //规则分类表 模型
        /*$auth_rule_classify = model('AuthRuleClassify');
        //查询规则分类及下属规则数据
        $list_classify = $auth_rule_classify->all();
        foreach($list_classify as $key=>$value) {
            $list_classify[$key]['rule'] = $value->rule()->where('status',1)->select();
        }*/

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //$this->assign('list_classify', $list_classify);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 角色 删除
     */
    public function role_delete()
    {
        //获取id数据
        $id = input('post.id');
        //规则用户组表 模型
        $role = model('Role');
        //删除数据
        $list = $role::get($id);
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
     * 角色 正常/禁用
     */
    public function role_show()
    {
        //获取id数据
        $id = input('post.id');
        //获取角色状态值
        $show = input('post.show');
        //规则用户组表 模型
        $role = model('Role');
        //更新数据
        $result = $role->save([
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
     * 权限分类管理
     */
    public function jurisdiction_classify()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //规则分类表名称
        if(!empty($param['name'])) {
            $where['name'] = ['like',"%{$param['name']}%"];
            $this->assign('name', $param['name']);
        }
        //规则分类表 模型
        $auth_rule_classify = model('AuthRuleClassify');
        //查询规则分类数据
        $list = $auth_rule_classify->where($where)->select();
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 权限分类添加、修改操作
     */
    public function jurisdiction_classify_edit()
    {
        //判断是否是post ajax提交方式
        if(request()->isPost()) {
            //规则分类表 验证器
            $validate = validate('AuthRuleClassify');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //规则分类表 模型
            $auth_rule_classify = model('AuthRuleClassify');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                $result = $auth_rule_classify->allowField(true)->save($param);
                //获取表自增id
                $id = $auth_rule_classify->classify_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $auth_rule_classify->allowField(true)->save($param,['classify_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'role/jurisdiction_classify','',1);
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
            //规则分类表 模型
            $auth_rule_classify = model('AuthRuleClassify');
            //查询权限规则信息
            $list = $auth_rule_classify->get($id);
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 权限分类 删除
     */
    public function jurisdiction_classify_delete()
    {
        //获取id数据
        $id = input('post.id');
        //规则分类表 模型
        $auth_rule_classify = model('AuthRuleClassify');
        //删除数据
        $list = $auth_rule_classify::get($id);
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
     * 权限分类 批量删除
     */
    public function jurisdiction_classify_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //规则分类表 模型
        $auth_rule_classify = model('AuthRuleClassify');
        //删除数据
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $auth_rule_classify::get($value);
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



    /**
     * 权限管理
     */
    public function jurisdiction()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //权限规则名称
        if(!empty($param['title'])) {
            $where['title'] = ['like',"%{$param['title']}%"];
            $this->assign('title',$param['title']);
        }
        //权限规则分类id
        if(!empty($param['classify_id'])) {
            $where['classify_id'] = ['=',$param['classify_id']];
            $this->assign('classify_id',$param['classify_id']);
        }
        //规则表 模型
        $auth_rule = model('AuthRule');
        //查询规则和规则分类 数据
        $list = $auth_rule->all($where,'classify');
        //规则分类表 模型
        $auth_rule_classify = model('AuthRuleClassify');
        //查询规则分类数据
        $list_classify = $auth_rule_classify->select();
        //渲染数据
        $this->assign('list', $list);
        $this->assign('list_classify', $list_classify);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 权限规则添加、修改操作
     */
    public function jurisdiction_edit()
    {
        //判断是否是post ajax提交方式
        if(request()->isPost()) {
            //规则表 验证器
            $validate = validate('AuthRule');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //规则表 模型
            $auth_rule = model('AuthRule');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                $result = $auth_rule->allowField(true)->save($param);
                //获取表自增id
                $id = $auth_rule->rule_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $auth_rule->allowField(true)->save($param,['rule_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'role/jurisdiction','',1);
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
            //规则表 模型
            $auth_rule = model('AuthRule');
            //查询权限规则信息
            $list = $auth_rule->get($id);
        }

        //规则分类表 模型
        $auth_rule_classify = model('AuthRuleClassify');
        //查询规则分类数据
        $list_classify = $auth_rule_classify->select();

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('list_classify', $list_classify);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 权限规则 删除
     */
    public function jurisdiction_delete()
    {
        //获取id数据
        $id = input('post.id');
        //规则表 模型
        $auth_rule = model('AuthRule');
        //删除数据
        $list = $auth_rule::get($id);
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
     * 权限规则 批量删除
     */
    public function jurisdiction_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //规则表 模型
        $auth_rule = model('AuthRule');
        //删除数据
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $auth_rule::get($value);
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