<?php
namespace app\index\controller;

/**
 * 首页控制器
 */
class IndexController extends CommonController {
    //php curl
    public function getJson($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }


    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 主页面
     */
    public function index1() {
        //判断是否登录
        $user_info = parent::check_login();
        //用户表 模型
        $user = model('User');
        //查询用户表 数据
        $response = $user->get(['uid'=>$user_info['uid']]);

        //微信公众平台JS-SDK
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询 系统配置参数 数据
        $system = $system_deploy->field('app_id,secret,deploy_id,access_token,expires_in,js_ticket,ticket_expires_in')->find();
        $appid = $system['app_id'];
        $secret = $system['secret'];
        //获取js_ticket
        //判断js_ticket是否过期
        if(!empty($system['ticket_expires_in']) && !empty($system['js_ticket']) && $system['ticket_expires_in'] > time()) {
            //从数据库获取js_ticket
            $js_ticket = $system['js_ticket'];
        } else {
            //判断access_token时间戳是否过期
            if(!empty($system['expires_in']) && !empty($system['access_token']) && $system['expires_in'] > time()) {
                //从数据库获取access_token
                $access_token = $system['access_token'];
            } else {
                //获取普通access_token
                $url_token="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
                $return_token = self::getJson($url_token);
                $access_token = $return_token["access_token"];

                //更新access_token
                $system->save(['access_token'=>$access_token,'expires_in'=>time()+$return_token['expires_in']],['deploy_id'=>$system['deploy_id']]);;
            }
            //获取普通js_ticket
            $url_ticket = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";
            $return_ticket = self::getJson($url_ticket);
            $js_ticket = $return_ticket["ticket"];

            //更新js_ticket
            $system->save(['js_ticket'=>$js_ticket,'ticket_expires_in'=>time()+$return_ticket['expires_in']],['deploy_id'=>$system['deploy_id']]);;
        }
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = "$protocol$_SERVER[HTTP_HOST]/index/index/index1.html";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$js_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );//echo 888; echo '<pre>'; var_dump($signPackage); exit;
        //渲染数据
        $this->assign('user_info', $response);
        $this->assign('signPackage', $signPackage);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 首页
     */
    public function index() {
        //判断是否登录
        //echo 777;exit;
        $user_info = parent::check_login();
        //幻灯片表 模型
        $slide = model('Slide');
        //查询幻灯片数据
        $list_slide = $slide->where("show",1)->where('type',1)->order("sort desc")->select();

        //公告表 模型
        $notice = model('Notice');
        //查询公告表数据
        $list_notice = $notice->where("show",1)->order("recommend asc")->select();

        //股票汇率表 模型
        $rate = model('Rate');
        //查询一月份股票汇率数据,要求是期权费率最低的五只一个月的票
        $rate_month1 = $rate->field('code,month1')->order("month1 asc")->limit(5)->select();
        foreach ($rate_month1 as $key => $value) {
            $rate_month1[$key]['month1'] = round($value['month1']*100,3).'%'; 
        }
        //查询三月份股票汇率数据
        $rate_month3 = $rate->field('code,month3')->order("month3 asc")->limit(5)->select();
        foreach ($rate_month3 as $key => $value) {
            $rate_month3[$key]['month3'] = round($value['month3']*100,3).'%'; 
        }
        //查询六月份股票汇率数据
        $rate_month6 = $rate->field('code,month6')->order("month6 asc")->limit(5)->select();
        foreach ($rate_month6 as $key => $value) {
            $rate_month6[$key]['month6'] = round($value['month6']*100,3).'%'; 
        }
        //合并股票汇率数据
        $list_rate = array_merge($rate_month1,$rate_month3,$rate_month6);
        //echo '<pre>'; print_r($rate_month1); echo '<hr>'; print_r($list_rate);exit;
        //订单表 模型
        $order = model('Order');
        //获取期权交易动态数据
        $data_order = $order->where('possessor_uid','<>',0)->field('possessor_uid,title,royalty,initial_price,principal,initial_price,create_time')->order('create_time desc')->limit(6)->select();
        //定义返回数据空数组
        $list_order = array();
        //循环处理数据
        foreach($data_order as $key=>$value) {
            //获取距离当前时间的时间戳
            $timestamp = time() - strtotime($value['create_time']);
            //判断时间戳 差值
            if($timestamp < 3600) {
                $list_order[$key]['time'] = intval($timestamp/60)."分钟前";
            } elseif($timestamp >= 3600 && $timestamp < 86400) {
                $list_order[$key]['time'] = intval($timestamp/3600)."小时前";
            } else {
                $today = strtotime(date("Y-m-d"),time());
                $list_order[$key]['time'] = date("Y-m-d",$today-86400);
            }

            //处理持有人姓名
            if(!empty($value['possessor']['client']['name'])) {
                $list_order[$key]['name'] = mb_substr($value['possessor']['client']['name'],0,1,"utf-8").'**';
            } else {
                $list_order[$key]['name'] = '**';
            }
            $list_order[$key]['principal'] = $value['principal']/10000;     //名义本金(万)
            $list_order[$key]['title'] = $value['title'];     //股票名称
            $list_order[$key]['royalty'] = $value['royalty'];     //权利金
        }

        //询价记录表 模型
        $enquiry = model('Enquiry');
        //封装搜索条件
        $where['uid'] = ['=',$user_info['uid']];
        //获取期权交易动态数据
        $list_enquiry = $enquiry->where($where)->order('create_time desc')->limit(6)->select();

        //渲染数据
        $this->assign('list_slide', $list_slide);
        $this->assign('list_notice', $list_notice);
        $this->assign('list_rate', $list_rate);
        $this->assign('rate_month1', $rate_month1);
        $this->assign('rate_month3', $rate_month3);
        $this->assign('rate_month6', $rate_month6);
        $this->assign('list_order', $list_order);
        $this->assign('list_enquiry', $list_enquiry);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 推荐股票汇率
     */
    public function recommends() {
        //股票汇率表 模型
        $rate = model('Rate');
        $rate_month1 = $rate->field('code,month1')->order("month1 asc")->limit(5)->select();
        foreach ($rate_month1 as $key => $value) {
            $rate_month1[$key]['month1'] = round($value['month1']*100,3).'%'; 
        }
        //查询三月份股票汇率数据
        $rate_month3 = $rate->field('code,month3')->order("month3 asc")->limit(5)->select();
        foreach ($rate_month3 as $key => $value) {
            $rate_month3[$key]['month3'] = round($value['month3']*100,3).'%'; 
        }
        //查询六月份股票汇率数据
        $rate_month6 = $rate->field('code,month6')->order("month6 asc")->limit(5)->select();
        foreach ($rate_month6 as $key => $value) {
            $rate_month6[$key]['month6'] = round($value['month6']*100,3).'%'; 
        }

        //渲染数据
        $this->assign('rate_month1', $rate_month1);
        $this->assign('rate_month3', $rate_month3);
        $this->assign('rate_month6', $rate_month6);
        //模板渲染
        return $this->fetch();
    }
    /**
     * 筛选页面
     */
    public function screen() {
        //获取数据
        $param = input('post.');//echo '<pre>'; print_r($param)
        //封装搜索条件
        $where = array();
        $is_search=0;
        $month=empty($param['month'])?1:$param['month'];
        //月份  
        if(!empty($param)){
            $is_search=1;
            
                 //最小期权费率
            if(!empty($param['min_premium'])) {             
                $where['month'.$month]=array(array('>=',"{$param['min_premium']}"),array('<=',"{$param['max_premium']}"),'AND');
            } 
            //最大
           /* if(!empty($param['max_premium'])) {
                $where['month'.$month] = ['<=',"{$param['max_premium']}"];
                $this->assign('max_premium',$param['max_premium']);
            }*/
           //echo '<pre>'; print_r($where); 
            //股票汇率表 模型
            $rate = model('Rate');
            $rate_month = $rate->field('code,month'.$month)->where($where)->order("month".$month." asc")->select();
            //echo  $rate->getLastSql();exit;
            foreach ($rate_month as $key => $value) {
                $rate_month[$key]['month'] = round($value['month'.$month]*100,3).'%'; 

            }//var_dump($rate_month);exit;
            $this->assign('rate_month',$rate_month);
        }     
       
       
       
        switch ($month) {
            case 1 :
                $time =  '一个月';
                break;
            case 2 :
                $time =  '两个月';
                break;
            case 3 :
                $time =  '三个月';
                break;
            case 4 :
                $time =  '四个月';
                break;
            case 5 :
                $time =  '五个月';
                break;
            case 6 :
                $time =  '六个月';
                break;
            case 7 :
                $time =  '七个月';
                break;
            case 8 :
                $time =  '八个月';
                break;
            case 9 :
                $time =  '九个月';
                break;
            case 10 :
                $time =  '十个月';
                break;
            case 11 :
                $time =  '十一个月';
                break;
            case 12 :
                $time =  '十二个月';
                break;
            default :
                $time =  '一个月';
        }
        $min_premium=empty($param['min_premium'])?'':$param['min_premium'];
        $max_premium=empty($param['max_premium'])?'':$param['max_premium'];
        $this->assign('min_premium',$min_premium);
        $this->assign('max_premium',$max_premium);
        $this->assign('is_search',$is_search);
        $this->assign('month',$month);
        $this->assign('time',$time);
        
        //模板渲染
        return $this->fetch();
    }


    /**
     * 底部导航栏
     */
    public function footer() {

        //模板渲染
        return $this->fetch();
    }



}
