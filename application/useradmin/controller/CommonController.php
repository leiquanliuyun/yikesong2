<?php
namespace app\useradmin\controller;

use think\Controller;

/**
 *　公共控制器，所有的控制器都继承此控制器（除登录控制器）
 */
class  CommonController extends Controller
{
    /**
     * 自动执行的方法()
     */
    public function _initialize()
    {
        //登录状态判断
        if (session('admin') == null) {
            $this->success('没登陆，页面跳转中...', 'Login/index');
        }

        /*// 进行权限判断
        $auth = new \think\Auth();
        //获取当前的请求信息
        $request = request();
        // 进行权限验证
        if ($auth->check($request->controller().'-'.$request->action(),session('admin.uid'))) {
            return true;
        } else {
            //redirect(U('Home/index'), 1, '权限不够，页面跳转中...');
            //$this->redirect('admin/home/index','权限不够，页面跳转中...');
            //$this->success('权限不够，页面跳转中...', 'admin/home/index','',1);
            echo "权限不够,无法查看！";
            exit;
        }*/
    }


}