<?php
namespace app\index\controller;

use think\Controller;

/**
 * 协议控制器
 */
class AgreementController extends Controller {

    /**
     * 平台推广协议
     */
    public function econmic_agreement() {

        //模板渲染
        return $this->fetch();
    }



}
