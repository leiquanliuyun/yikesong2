<?php
namespace app\index\controller;

use think\Controller;

/**
 *　公共控制器，所有的控制器都继承此控制器（除登录控制器）
 */
class  CommonController extends Controller
{
    /**
     * 自动执行的方法()
     */
    public function _initialize() {
        //获取浏览器信息
        $user_agent = $_SERVER['HTTP_USER_AGENT'];//var_dump($user_agent);  
      //session('user_info', ''); echo '<pre>';    var_dump(session('user_info')); exit;
        //判断是否是微信浏览器
        if (strpos($user_agent, 'MicroMessenger') != FALSE) {
            //判断是否登录
            if(empty(session('user_info'))) {//echo 999; var_dump(input('param.'));exit;
                $uid = input('param.uid') ? input('param.uid') : 0;
                //重定向
                $this->redirect('login/login', ['uid' => $uid]);
            }
        } else {
            echo '请在微信浏览器登录';
        }
    }


    //验证是否登录
    public function check_login() {
        $user_info = session('user_info'); 
        if(empty($user_info)) {
            $this->success('未登录，请先登录','login/login','',1);
        } else {
            //用户表 模型
            $user = model('User');
            //存入session
            session('user_info', $user::get($user_info['uid']));//echo '<pre>';    var_dump(session('user_info')); //exit;
            return session('user_info');
        }
    }



    /**
     * 获取客服用户数据
     *
     * @return $data
     */
    public function user_service(){
        //客服信息表 模型
        $info_service = model('InfoService');
        //查询客服信息表数据
        $result = $info_service->field('name')->find();
        //返回数据
        return $result['name'];
    }



}