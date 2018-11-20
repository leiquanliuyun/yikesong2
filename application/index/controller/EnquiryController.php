<?php
namespace app\index\controller;

/**
 * 期权计算器 询价 控制器
 */
class EnquiryController extends CommonController {

    /**
     * 期权计算器 页面
     */
    public function calculator() {
        //判断是否登录
        $user_info = parent::check_login();
        //获取数据
        $param =  input('param.');
        //股票数据
        $list_stock['title'] = '';
        $list_stock['symbol'] = '';
        $list_stock['trade'] = '';
        //验证数据
        if(!empty($param['option'])) {
            switch ($param['option']) {
                case 'add':
                    if(!empty($param['stock_id'])) {
                        //添加搜索记录
                        $param_search['stock_id'] = intval($param['stock_id']);       //转为整形
                        $param_search['uid'] = $user_info['uid'];       //用户表id
                        //股票搜索记录表 模型
                        $stock_search = model('StockSearch');
                        //添加,股票搜索记录表数据
                        $stock_search->save($param_search);
                        //股票数据表 模型
                        $stock = model('Stock');
                        //查询股票数据
                        $data_stock = $stock->get($param['stock_id']);
                        //判断是否有数据
                        if(!empty($data_stock)) {
                            $list_stock['title'] = $data_stock['name'].'('.$data_stock['symbol'].')';   //股票名称、代码
                            $list_stock['symbol'] = $data_stock['symbol'];      //股票代码
                            $list_stock['trade'] = $data_stock['trade'];        //股票最新价
                        }
                    }
                    break;
                case 'search':
                    if(!empty($param['stock_id'])) {
                        //股票数据表 模型
                        $stock = model('Stock');
                        //查询股票数据
                        $data_stock = $stock->get(intval($param['stock_id']));
                        //判断是否有数据
                        if(!empty($data_stock)) {
                            $list_stock['title'] = $data_stock['name'].'('.$data_stock['symbol'].')';   //股票名称、代码
                            $list_stock['symbol'] = $data_stock['symbol'];      //股票代码
                            $list_stock['trade'] = $data_stock['trade'];        //股票最新价
                        }
                    }
                    break;
            }
        }

        //渲染数据
        $this->assign('list_stock', $list_stock);
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 股票数据搜索  页面
     */
    public function search() {
        //判断是否登录
        $user_info = parent::check_login();

        //渲染数据
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



}
