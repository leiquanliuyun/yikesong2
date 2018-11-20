<?php
namespace app\index\controller;

/**
 * 首页控制器
 */
class IndexController extends CommonController {

    /**
     * 底部导航栏
     */
    public function footer() {

        //渲染数据
        $this->assign('rate_month1', $rate_month1);
        //模板渲染
        return $this->fetch();
    }



}
