<?php
namespace app\useradmin\controller;

use think\Controller;
/**
 * 首页控制器
 */
class  HomeController extends Controller
{
    /**
     * 后台管理系统登录页
     */
    public function index()
    {
        //角色名称
        $group_id = session('admin.user_type');

        //渲染数据
        $this->assign('group_id', $group_id);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 后台管理系统 我的桌面 页
     */
    public function welcome()
    {
        //模板渲染
        return $this->fetch();
    }

}