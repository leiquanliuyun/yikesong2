<?php
namespace app\useradmin\controller;
use think\Controller;

/**
 * 登录控制器
 */
class  LoginController extends Controller
{
    /**
     * 后台管理系统登录页
     */
    public function index()
    {
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询艺术品数据
        $list = $system_deploy->get(1);
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 用户登录,检验验证码、验证用户名和密码
     */
    public function login()
    {
        //获取数据
        $request = request();
        //表单提交数据
        $data = $request->param();
        //用户表 验证器
        $validate = validate('Users');
        //验证 表单数据
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        //用户表 模型
        $user = model('Users');
        //where条件
        $where['user_login'] = trim($data['user_login']);
        $where['status'] = ['neq',2];
        //查询用户表数据
        $result = $user->where($where)->find();
        //echo $result->status_text;exit;
        //判断
        if(empty($result['user_login'])) {
            $this->error('此账号不存在！');
        }
        if($result['status'] == 0) {
            $this->error('账号未启用！');
        }
        //拼装密码并md5处理
        $password = md5(trim($data['password']).$result['salt']);
        if($result['password'] == $password) {
            //更新用户最后登录时间和ip
            $user->save(["last_login_time"=>date("Y-m-d H:i:s"),"last_login_ip"=>request()->ip()],["id"=>$result['id']]);
            //存入session
            session('admin', $result);

            $this->success('登录成功', 'Home/index','',1);
        } else {
            $this->error('账号或密码错误！');
        }

    }

    /**
     * 用户退出登录操作
     */
    public function doLogout()
    {
        // 删除（当前作用域）
        session('admin',null);

        $this->success('退出成功', 'Login/index');
    }





}