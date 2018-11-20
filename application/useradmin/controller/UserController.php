<?php
namespace app\useradmin\controller;

/**
 * 用户管理 控制器
 */
class  UserController extends CommonController
{

    /**
     * 用户 修改密码
     */
    public function change_password()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(empty($param['password']) || $param['password'] != $param['password2'] || strlen($param['password']) < 6) {
                $this->error('输入两次密码不一致或者密码为空或者密码少于6位');
            }
            //用户表 模型
            $user = model('User');
            //封装处理所需参数
            $data['salt'] = randCode(8);                                           //加密串
            $data['password'] = md5(trim($param['password']).$data['salt']);       //密码
            //更新数据
            $result = $user->save($data,['uid'=>$param['id']]);
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/'.$param['option'],'',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取表id数据
        $id =  input('param.id');
        //获取页面标签数据
        $option = input('param.option');
        //用户表 模型
        $user = model('User');
        //查询用户数据
        $list = $user->get($id);
        //渲染数据
        $this->assign('id',$id);
        $this->assign('list',$list);
        $this->assign('option',$option);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 用户 修改手机号码
     */
    public function change_mobile()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(empty($param['mobile']) || !preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $param['mobile'])) {
                $this->error('手机号码为空或者格式错误!');
            }
            //用户表 模型
            $user = model('User');
            //查询管理员用户数据
            $list = $user->get(['mobile' => $param['mobile']]);
            if(!empty($list) ) {
                $this->error('手机号码已被注册，请重新设置!');
            }
            //封装处理所需参数
            $data['mobile'] = $param['mobile'];                                   //手机号码
            //更新数据
            $result = $user->save($data,['uid'=>$param['id']]);
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/'.$param['option'],'',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取表id数据
        $id =  input('param.id');
        //获取页面标签数据
        $option = input('param.option');
        //用户表 模型
        $user = model('User');
        //查询用户数据
        $list = $user->get($id);
        //渲染数据
        $this->assign('id',$id);
        $this->assign('list',$list);
        $this->assign('option',$option);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 用户 启用/停用
     */
    public function status()
    {
        //获取id数据
        $id = input('post.id');
        //获取用户状态值
        $show = input('post.show');
        //用户表 模型
        $user = model('User');
        //更新数据
        $result = $user->save([
            'status'  => $show
        ],['uid' => $id]);
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
     * 管理员列表管理
     */
    public function admin()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',1];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //管理员用户名称
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }//echo '<pre>'; print_r($where);
        //用户表 模型
        $user = model('User');
        //查询用户表和管理员 数据
        $list = $user->all($where,'admin');
 
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
            $user_validate = validate('User');
            //管理员表 验证器
            $admin_validate = validate('InfoAdmin');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$admin_validate->check($param)){
                $this->error($admin_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //管理员表 模型
            $info_admin = model('InfoAdmin');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 1;                                                 //角色：超级管理员
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
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
                }
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $info_admin->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/admin','',1);
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
            //用户表 模型
            $user = model('User');
            //查询管理员用户数据
            $list = $user->get($id,'admin');
        }

        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 管理员用户 删除
     */
    public function admin_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
        $user = model('User');
        //删除数据,用户状态3为删除
        foreach($id_array['id_array'] as $key=>$value) {
            $result = $user->update(['uid' => $value, 'status' => 3]);
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
     * 客服列表管理
     */
    public function service()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',2];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //客服账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表和客服 数据
        $list = $user->all($where,'service');

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 客服用户添加、修改操作
     */
    public function service_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('User');
            //客服信息表 验证器
            $service_validate = validate('InfoService');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$service_validate->check($param)){
                $this->error($service_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //客服信息表 模型
            $info_service = model('InfoService');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 2;                                                 //角色：客服
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_service->allowField(true)->save($param);

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 2;      //客服组别
                    $auth_group_access->save($param_access);
                }
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $info_service->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/service','',1);
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
            //用户表 模型
            $user = model('User');
            //查询客服用户数据
            $list = $user->get($id,'service');
        }
        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 客服用户 删除
     */
    public function service_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
     * 所有用户列表管理
     */
    public function all_user()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态
        $where['status'] = ['<>',3];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //客服账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表 数据
        $list = $user->all($where);

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 代理人列表管理
     */
    public function agent()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',3];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //代理商账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表和代理人 数据
        $list = $user->all($where,'client');

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 代理人用户添加、修改操作
     */
    public function agent_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('User');
            //代理商/顾客信息表 验证器
            $client_validate = validate('InfoClient');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$client_validate->check($param)){
                $this->error($client_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //代理商/顾客信息表 模型
            $info_client = model('InfoClient');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 3;                                                 //角色：代理商
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_client->allowField(true)->save($param);

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 3;      //客服组别
                    $auth_group_access->save($param_access);
                }
            }elseif($option == 'update'){
                //=============================发送邮件
                //用户表 模型
                $user = model('User');
                //查询顾客用户数据
                $data_user = $user->get($id);
                //判断是否需要发送申请接口成功邮件
                if($data_user['port_type'] == 2 && $param['port_type'] == 1) {
                    //邮件表 模型
                    $email = model('Email');
                    //查询邮件表无询价记录数据
                    $data_email = $email->get(10);
                    //判断是否查询到数据
                    if(!empty($data_email)) {
                        //判断客户或客服是否需要发送邮件
                        if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                            //获取邮件模板内容
                            $email_content = $data_email['content'];
                            //替换用户手机号
                            $email_content = str_replace('{$mobile}',$data_user['mobile'],$email_content);

                            //判断是否需要发送给客户
                            switch ($data_email['client_status']) {
                                case 1:
                                    //给客户发送smtp163邮件
                                    send_email($data_email['title'],$email_content,$data_user['client']['email']);
                                    break;
                                default :
                            }
                            //判断是否需要发送给客服
                            switch ($data_email['service_status']) {
                                case 1:
                                    //系统配置参数表
                                    $system_deploy = model('SystemDeploy');
                                    //查询系统配置参数数据
                                    $data_system = $system_deploy->field('email')->find();
                                    //给客服发送smtp163邮件
                                    if(!empty($data_system['email'])) {
                                        //获取客服email数组
                                        $service_email = explode(';',$data_system['email']);
                                        //给每个客服发送邮件
                                        foreach($service_email as $key=>$value) {
                                            send_email($data_email['title'],$email_content,$value);
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                //更新,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param,['uid'=>$id]);
                $result = $info_client->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/agent','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        //用户表 模型
        $user = model('User');

        $list = array();
        if($option == 'update') {
            //查询代理商用户数据
            $list = $user->get($id,'client');
        }
        //查询机构数据
        $list_organization = $user->where(['group_id'=>5,'status'=>1])->select();

        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        $this->assign('list_organization',$list_organization);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 代理人用户 删除
     */
    public function agent_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
     * 机构列表管理
     */
    public function organization()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',5];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //机构账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表和机构 数据
        $list = $user->all($where,'client');

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 机构用户添加、修改操作
     */
    public function organization_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('User');
            //前端管理员/机构/代理人/顾客信息表 验证器
            $client_validate = validate('InfoClient');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$client_validate->check($param)){
                $this->error($client_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //前端管理员/机构/代理人/顾客信息表 模型
            $info_client = model('InfoClient');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 5;                                                 //角色：机构
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_client->allowField(true)->save($param);

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 5;      //机构组别
                    $auth_group_access->save($param_access);
                }
            }elseif($option == 'update'){
                //=============================发送邮件
                //用户表 模型
                $user = model('User');
                //查询顾客用户数据
                $data_user = $user->get($id);
                //判断是否需要发送申请接口成功邮件
                if($data_user['port_type'] == 2 && $param['port_type'] == 1) {
                    //邮件表 模型
                    $email = model('Email');
                    //查询邮件表无询价记录数据
                    $data_email = $email->get(10);
                    //判断是否查询到数据
                    if(!empty($data_email)) {
                        //判断客户或客服是否需要发送邮件
                        if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                            //获取邮件模板内容
                            $email_content = $data_email['content'];
                            //替换用户手机号
                            $email_content = str_replace('{$mobile}',$data_user['mobile'],$email_content);

                            //判断是否需要发送给客户
                            switch ($data_email['client_status']) {
                                case 1:
                                    //给客户发送smtp163邮件
                                    send_email($data_email['title'],$email_content,$data_user['client']['email']);
                                    break;
                                default :
                            }
                            //判断是否需要发送给客服
                            switch ($data_email['service_status']) {
                                case 1:
                                    //系统配置参数表
                                    $system_deploy = model('SystemDeploy');
                                    //查询系统配置参数数据
                                    $data_system = $system_deploy->field('email')->find();
                                    //给客服发送smtp163邮件
                                    if(!empty($data_system['email'])) {
                                        //获取客服email数组
                                        $service_email = explode(';',$data_system['email']);
                                        //给每个客服发送邮件
                                        foreach($service_email as $key=>$value) {
                                            send_email($data_email['title'],$email_content,$value);
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }

                //更新,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param,['uid'=>$id]);
                $result = $info_client->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/organization','',1);
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
            //用户表 模型
            $user = model('User');
            //查询机构用户数据
            $list = $user->get($id,'client');
        }
        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 机构用户 删除
     */
    public function organization_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
     * 前端管理员列表管理
     */
    public function leading()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',6];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表和前端管理员 数据
        $list = $user->all($where,'client');

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 前端管理员用户添加、修改操作
     */
    public function leading_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('User');
            //前端管理员/机构/代理人/顾客信息表 验证器
            $client_validate = validate('InfoClient');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$client_validate->check($param)){
                $this->error($client_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //前端管理员/机构/代理人/顾客信息表 模型
            $info_client = model('InfoClient');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 6;                                                 //角色：前端管理员
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_client->allowField(true)->save($param);

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 6;      //机构组别
                    $auth_group_access->save($param_access);
                }
            }elseif($option == 'update'){
                //=============================发送邮件
                //用户表 模型
                $user = model('User');
                //查询顾客用户数据
                $data_user = $user->get($id);
                //判断是否需要发送申请接口成功邮件
                if($data_user['port_type'] == 2 && $param['port_type'] == 1) {
                    //邮件表 模型
                    $email = model('Email');
                    //查询邮件表无询价记录数据
                    $data_email = $email->get(10);
                    //判断是否查询到数据
                    if(!empty($data_email)) {
                        //判断客户或客服是否需要发送邮件
                        if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                            //获取邮件模板内容
                            $email_content = $data_email['content'];
                            //替换用户手机号
                            $email_content = str_replace('{$mobile}',$data_user['mobile'],$email_content);

                            //判断是否需要发送给客户
                            switch ($data_email['client_status']) {
                                case 1:
                                    //给客户发送smtp163邮件
                                    send_email($data_email['title'],$email_content,$data_user['client']['email']);
                                    break;
                                default :
                            }
                            //判断是否需要发送给客服
                            switch ($data_email['service_status']) {
                                case 1:
                                    //系统配置参数表
                                    $system_deploy = model('SystemDeploy');
                                    //查询系统配置参数数据
                                    $data_system = $system_deploy->field('email')->find();
                                    //给客服发送smtp163邮件
                                    if(!empty($data_system['email'])) {
                                        //获取客服email数组
                                        $service_email = explode(';',$data_system['email']);
                                        //给每个客服发送邮件
                                        foreach($service_email as $key=>$value) {
                                            send_email($data_email['title'],$email_content,$value);
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }

                //更新,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param,['uid'=>$id]);
                $result = $info_client->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/leading','',1);
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
            //用户表 模型
            $user = model('User');
            //查询前端管理员用户数据
            $list = $user->get($id,'client');
        }
        //渲染数据
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 前端管理员用户 删除
     */
    public function leading_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
     * 普通用户列表管理
     */
    public function client()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //用户状态和角色
        $where['status'] = ['<>',3];
        $where['group_id'] = ['=',4];
        //开始时间
        if(!empty($param['start_time'])) {
            $where['create_time'] = ['>=',"{$param['start_time']}"];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['create_time'] = ['<=',"{$param['end_time']}"];
            $this->assign('end_time',$param['end_time']);
        }
        //代理商账号
        if(!empty($param['username'])) {
            $where['username'] = ['like',"%{$param['username']}%"];
            $this->assign('username',$param['username']);
        }
        //用户表 模型
        $user = model('User');
        //查询用户表和普通用户 数据
        $list = $user->all($where,'client');

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 顾客用户添加、修改操作
     */
    public function client_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //用户表 验证器
            $user_validate = validate('User');
            //代理商/顾客信息表 验证器
            $client_validate = validate('InfoClient');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$user_validate->check($param)){
                $this->error($user_validate->getError());
            }
            if(!$client_validate->check($param)){
                $this->error($client_validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //用户表 模型
            $user = model('User');
            //代理商/顾客信息表 模型
            $info_client = model('InfoClient');
            if($option == 'add'){
                //封装处理所需参数
                $param['salt'] = randCode(8);                                           //加密串
                $param['username'] = trim($param['username']);                          //账号
                $param['password'] = md5(trim($param['password']).$param['salt']);      //密码
                $param['group_id'] = 4;                                                 //角色：顾客
                //添加,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param);
                //获取表自增id
                $id = $user->uid;
                //添加关联表数据,过滤非数据表字段的数据
                if($result) {
                    //关联表外键id
                    $param['uid'] = $id;
                    $info_client->allowField(true)->save($param);

                    //用户组明细表 模型
                    $auth_group_access = model('AuthGroupAccess');
                    //封装数据
                    $param_access['uid'] = $id;
                    $param_access['group_id'] = 4;      //顾客组别
                    $auth_group_access->save($param_access);
                }
            }elseif($option == 'update'){
                //=============================发送邮件
                //用户表 模型
                $user = model('User');
                //查询顾客用户数据
                $data_user = $user->get($id);
                //判断是否需要发送申请接口成功邮件
                if($data_user['port_type'] == 2 && $param['port_type'] == 1) {
                    //邮件表 模型
                    $email = model('Email');
                    //查询邮件表无询价记录数据
                    $data_email = $email->get(10);
                    //判断是否查询到数据
                    if(!empty($data_email)) {
                        //判断客户或客服是否需要发送邮件
                        if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                            //获取邮件模板内容
                            $email_content = $data_email['content'];
                            //替换用户手机号
                            $email_content = str_replace('{$mobile}',$data_user['mobile'],$email_content);

                            //判断是否需要发送给客户
                            switch ($data_email['client_status']) {
                                case 1:
                                    //给客户发送smtp163邮件
                                    send_email($data_email['title'],$email_content,$data_user['client']['email']);
                                    break;
                                default :
                            }
                            //判断是否需要发送给客服
                            switch ($data_email['service_status']) {
                                case 1:
                                    //系统配置参数表
                                    $system_deploy = model('SystemDeploy');
                                    //查询系统配置参数数据
                                    $data_system = $system_deploy->field('email')->find();
                                    //给客服发送smtp163邮件
                                    if(!empty($data_system['email'])) {
                                        //获取客服email数组
                                        $service_email = explode(';',$data_system['email']);
                                        //给每个客服发送邮件
                                        foreach($service_email as $key=>$value) {
                                            send_email($data_email['title'],$email_content,$value);
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                //判断是否需要发送申请代理人成功邮件
                if($data_user['group_id'] == '普通用户' && ($param['group_id'] == 3 || $param['group_id'] == 5)) {
                    //邮件表 模型
                    $email = model('Email');
                    //查询邮件表无询价记录数据
                    $data_email = $email->get(5);
                    //判断是否查询到数据
                    if(!empty($data_email)) {
                        //判断客户或客服是否需要发送邮件
                        if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                            //获取邮件模板内容
                            $email_content = $data_email['content'];
                            //替换用户手机号
                            $email_content = str_replace('{$mobile}',$data_user['mobile'],$email_content);
                            //判断类型
                            switch($param['group_id']) {
                                case 3:
                                    $type = '个人';
                                    break;
                                case 5:
                                    $type = '机构';
                                    break;
                                default:
                                    $type = '';
                            }
                            //替换类型
                            $email_content = str_replace('{$type}',$type,$email_content);

                            //判断是否需要发送给客户
                            switch ($data_email['client_status']) {
                                case 1:
                                    //给客户发送smtp163邮件
                                    send_email($data_email['title'],$email_content,$data_user['client']['email']);
                                    break;
                                default :
                            }
                            //判断是否需要发送给客服
                            switch ($data_email['service_status']) {
                                case 1:
                                    //系统配置参数表
                                    $system_deploy = model('SystemDeploy');
                                    //查询系统配置参数数据
                                    $data_system = $system_deploy->field('email')->find();
                                    //给客服发送smtp163邮件
                                    if(!empty($data_system['email'])) {
                                        //获取客服email数组
                                        $service_email = explode(';',$data_system['email']);
                                        //给每个客服发送邮件
                                        foreach($service_email as $key=>$value) {
                                            send_email($data_email['title'],$email_content,$value);
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
                //更新,过滤非数据表字段的数据
                $result = $user->allowField(true)->save($param,['uid'=>$id]);
                $result = $info_client->allowField(true)->save($param,['uid'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'user/client','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');

        //用户表 模型
        $user = model('User');
        $list = array();
        if($option == 'update') {
            //查询顾客用户数据
            $list = $user->get($id,'client');
        }
        //查询代理商数据
        $list_agent = $user->where(['group_id'=>3,'status'=>1])->select();

        //渲染数据
        $this->assign('list_agent', $list_agent);
        $this->assign('option',$option);
        $this->assign('id',$id);
        $this->assign('list',$list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 顾客用户 删除
     */
    public function client_delete()
    {
        //获取id数据
        $id = input('post.id');
        //用户表 模型
        $user = model('User');
        //删除数据,用户状态3为删除
        $result = $user->save(['status'=>3],['uid'=>$id]);
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
     * 经纪注册管理页
     */
    public function register()
    {
        //获取数据
        $param = input('post.');

        //封装搜索条件
        $where = array();
        //开始时间
        if(!empty($param['start_time'])) {
            $where['update_time'] = ['>=',$param['start_time']];
            $this->assign('start_time',$param['start_time']);
        }
        //结束时间
        if(!empty($param['end_time'])) {
            $where['update_time'] = ['<=',$param['end_time']];
            $this->assign('end_time',$param['end_time']);
        }

        //经纪注册表 模型
        $broker_register = model('BrokerRegister');
        //查询经纪注册表数据
        $list = $broker_register->where($where)->order('create_time desc')->select();

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }



    /**
     * 经纪注册表 删除
     */
    public function register_delete()
    {
        //获取id数据
        $id = input('post.id');
        //经纪注册表 模型
        $broker_register = model('BrokerRegister');
        //删除数据
        $list = $broker_register::get($id);
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
     * 经纪注册表 批量删除
     */
    public function register_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //经纪注册表 模型
        $broker_register = model('BrokerRegister');
        //删除数据
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $broker_register::get($value);
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
     * 查看销售订单
     */
    public function sales_order()
    {
        //获取id数据
        $id =  input('param.id');
        //订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['sell_uid'] = $id;
        $where['status'] = ['not in',[9,10]];
        //查询销售订单数据
        $list = $order->where($where)->order("create_time desc")->select();

        //封装销售总金额
        $price = 0;
        $count = 0;
        foreach($list as $key=>$value) {
            $price = $price + $value['price'];
        }
        $count = count($list);
        //渲染数据
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('price', $price);
        $this->assign('id', $id);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 代理人查看绩效订单
     */
    public function performance()
    {
        //获取id数据
        $id =  input('param.id');
        //订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['superior_uid'] = $id;
        $where['audit'] = 1;
        //查询绩效订单数据
        $list = $order->where($where)->order("create_time desc")->select();

        //渲染数据
        $this->assign('list', $list);
        $this->assign('id', $id);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 机构查看绩效订单
     */
    public function organization_performance()
    {
        //获取id数据
        $id =  input('param.id');
        //用户表 模型
        $user = model('User');
        //查询机构下属代理人
        $data_user = $user->field('uid')->where('belong_id',5)->select();
        //封装uid条件
        $where_uid = array();
        foreach($data_user as $key=>$value) {
            $where_uid[] = $value['uid'];
        }
        $where_uid = implode(',',$where_uid);
        //订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['superior_uid'] = ['in',[$where_uid]];
        $where['audit'] = 1;
        //查询绩效订单数据
        $list = $order->where($where)->order("create_time desc")->select();

        //渲染数据
        $this->assign('list', $list);
        $this->assign('id', $id);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 查看绩效订单
     */
    public function self_order()
    {
        //获取id数据
        $id =  input('param.id');
        //订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['possessor_uid'] = $id;
        //查询绩效订单数据
        $list = $order->where($where)->order("create_time desc")->select();

        //渲染数据
        $this->assign('list', $list);
        $this->assign('id', $id);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 查看下线
     */
    public function subordinate()
    {
        //获取id数据
        $id =  input('param.id');
        //用户表 模型
        $user = model('User');
        //获取当前用户数据
        $list = $user->where('belong_id',$id)->select();

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 机构查看代理人
     */
    public function organization_subordinate()
    {
        //获取id数据
        $id =  input('param.id');
        //用户表 模型
        $user = model('User');
        //获取当前用户数据
        $list = $user->where('belong_id',$id)->select();

        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }



}