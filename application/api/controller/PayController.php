<?php

namespace app\api\controller;

use think\Cache;
use think\Db;
use think\Loader;
use app\api\model\Order;
//use JPush\Client;
use think\Log;
use app\api\model\UserData;
use app\api\model\Message;
class PayController extends CommonController {

    protected $order;

    public function index() {
        $this->order = new Order();
    }

    public function alipayCallback() {
        $config = config('alipay');
        Loader::import('pay.alipay.AopClient');

        $aop = new \AopClient();
        $aop->alipayrsaPublicKey = $config['alipay_public_key'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        $data = $_POST;

        if ($flag) {

            $sellerid = $data['seller_id'];
            $outtradno = $data['out_trade_no'];
            $tradeno = $data['trade_no'];
            $money = $data['total_amount'];
            $sign = $data['sign'];

            $payinfo = db('pay_log')->field('id,user_id,order_id,sign,style,status')->where(['out_trade_no' => $outtradno, 'status' => 1])->find();

            if (!$payinfo) {
                $this->success("FAIL");
            }

            $list = db('order')->where(['id' => $payinfo['order_id']])->find();
            $shopinfo = db('shop_info')->field('auto_order')->where(['id' => $list['shop_id']])->find();

//            $ordergoods = db('order_goods')->field('product_id')->where(['order_id' => $payinfo['order_id']])->select();


            if (!$list) {
                $this->success("FAIL");
            }

            if (!in_array($list['status'], [1, 8])) {
                $this->success("SUCCESS");
            }

            if ($data['trade_status'] == 'TRADE_SUCCESS') {

                $status = 1;
                if ($payinfo['style'] == 3) {
                    //尾款付完 订单执行已经完成
                    $status = 17;
                } else {
                    if ($list['type'] == 3) {//如果是劵产品，生成劵号 劵购买时没有预约时间和人员选项
                        $res = $this->get_ticket_no($list);
                        $status = 3;
                        //推送信息
                        parent::order_push($list, $status);
                    } elseif ($list['type'] == 1) {
                        $goodsid = db('order_goods')->field('product_id')->where(['order_id' => $list['id']])->find();
                        $goodsinfo = db('shop_product')->field('sell_type')->where(['id' => $goodsid['product_id']])->find();

                        if ($shopinfo['auto_order'] == 1) {


                            if ($list['worker_id'] != 0) {
                                $status = 7;
                                //添加订单记录 包含预约成功
                                $res1 = parent::add_order_log($list, 1);
                                //添加订单记录 包含商家接单
                                $res2 = parent::add_order_log($list, 2);
                                //添加订单记录 包含服务人员接单
                                $res3 = parent::add_order_log($list, 3);
                            } else {
                                $status = 6;
                                //添加订单记录 包含预约成功
                                $res1 = parent::add_order_log($list, 1);
                                //添加订单记录 包含商家接单
                                $res2 = parent::add_order_log($list, 2);
                            }
                            //推送信息
                            parent::order_push($list, $status);
                        } else {
                            if ($goodsinfo['sell_type'] == 1) {
                                $status = 3;
                            } else {
                                $status = 2;
                            }
                        }
                    } elseif ($list['type'] == 4) {
                        db('user_money')->where(['user_id' => $payinfo['user_id']])->setInc('balance', 200);
                        $this->get_ticket($payinfo['order_id'], $payinfo['user_id']);
                        $status = 3;
                    } else {
                        if ($shopinfo['auto_order'] == 1) {
                            if ($list['worker_id'] != 0) {
                                //添加订单记录 包含预约成功
                                $res1 = parent::add_order_log($list, 1);
                                //添加订单记录 包含商家接单
                                $res2 = parent::add_order_log($list, 2);
                                //添加订单记录 包含服务人员接单
                                $res3 = parent::add_order_log($list, 3);

                                $status = 7;
                            } else {
                                //添加订单记录 包含预约成功
                                $res1 = parent::add_order_log($list, 1);
                                //添加订单记录 包含商家接单
                                $res2 = parent::add_order_log($list, 2);

                                $status = 6;
                            }
                            //推送信息
                            parent::order_push($list, $status);
                        } else {
                            $status = 3;
                            $res1 = parent::add_order_log($list, $status);
                        }
                    }
                }
                $logupdata['status'] = 2;
                $updata['status'] = $status;

                //处理缓存
//                    $ordercache = Cache::get('order_'.$payinfo['order_id']);
//                    if($ordercache){
//                        $ordercache['status_str'] = order_status($status);
//                        $ordercache['order_status'] = $status;
//                        $ordercache['update_time'] = time();
//                        Cache::set('order_'.$payinfo['order_id'], $ordercache);
//                    }
                //付款成功修改商品及商家的销售缓存数据，用于后台统计
//                sales_cache(1,$list['id']);
            } elseif ($data['trade_status'] == 'WAIT_BUYER_PAY') {
                $logupdata['status'] = 1;
                $updata['status'] = 1;

                //处理缓存
//                    $ordercache = Cache::get('order_'.$orderid);
//                    if($ordercache){
//                        $ordercache['status_str'] = order_status(9);
//                        $ordercache['order_status'] = 1;
//                        $ordercache['update_time'] = time();
//                        Cache::set('order_'.$orderid, $ordercache);
//                    }
            } else {
                $logupdata['status'] = 3;

                if ($payinfo['style'] == 3) {
                    $updata['status'] = 16;
                } else {
                    $updata['status'] = 4;
                }

                //支付失败 恢复库存
                $ordergoods = db('order_goods')->field('product_id,goods_number')->where(['order_id' => $payinfo['order_id'], 'status' => 1])->select();
                if ($ordergoods) {
                    foreach ($ordergoods as $ok => $ov) {
                        $oldnum = db('shop_product')->where(['id' => $ov['product_id']])->value('stock');
                        $upnum = [];
                        $upnum['stock'] = $oldnum + $ov['goods_number'];
                        $res2 = db('shop_product')->where(['id' => $ov['product_id']])->update($upnum);
                    }
                }

                //处理缓存
//                    $ordercache = Cache::get('order_'.$orderid);
//                    if($ordercache){
//                        $ordercache['status_str'] = order_status(9);
//                        $ordercache['order_status'] = 10;
//                        $ordercache['update_time'] = time();
//                        Cache::set('order_'.$orderid, $ordercache);
//                    }
            }
            //添加流动记录
            $res3 = parent::add_money_log($payinfo['user_id'], 0, 0, $money, $payinfo['order_id']);
            $res1 = db('pay_log')->where(['id' => $payinfo['id']])->update($logupdata);
            $res2 = db('order')->where(['id' => $payinfo['order_id']])->update($updata);
            if (false === $res1 || false === $res2) {
                $this->success("FAIL");
            } else {
                $this->success("SUCCESS");
            }
        }

        $this->success("SUCCESS");
    }

    public function weixinCallback() {
        Log::write('支付回调开始' , Log::INFO);
        $data = file_get_contents("php://input");
        Log::write('回调的数据：'.$data, Log::INFO);
        $returnstr1 = '<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>';
        $returnstr2 = '<xml><return_code><![CDATA[FAIL]]></return_code></xml>';
        // halt($data);
        if ($data) {
            $newdataobj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            $newdataobj = json_encode($newdataobj);
            $newdata = json_decode($newdataobj, true);
            echo '<pre>'; print_r($newdata);
            $sign = $newdata["sign"];
            $newsign = $this->MakeSign($newdata);
            if ($sign === $newsign) {
                $this->success($returnstr2);
            }
            if ($newdata['return_code'] == 'SUCCESS') {
                //核实金额
                $outtradeno = $newdata['out_trade_no'];
                $money = round((float)$newdata['total_fee'] / 100,2);
                $payinfo = db('pay_log')->where(['out_trade_no' => $outtradeno])->field('money,order_id,uid')->find();
                if ($payinfo['money']!=$money) {
                    $this->success($returnstr2);
                }
                if ($newdata['result_code'] == 'SUCCESS') {
                    Db::startTrans();
                    try{
                        $order_info=db('order')->where('id',$payinfo['order_id'])->find();
                        $order_save=[];
                        //优惠券管理
                        //echo '<pre>'; print_r($_SESSION);
                        if (isset($_SESSION['coupon'])){
                            $use_time=get_time(time(),0);
                            db('member_coupon')->where('member_coupon_id',$_SESSION['coupon'])
                                ->update(['status'=>2,'use_time'=>$use_time]);
                            $order_save['member_coupon_id']=session('coupon');
                        }
                        //订单管理
                        if ($order_info['service_name']=='财税管理'){
                            $order_save['status']=4;
                            //积分管理(有上级且订单所属服务有返回积分，且必须订单完成）
                            $member_info=db('member')->where('id',$payinfo['uid'])->find();
                            $integral_info=db('integral')->where('service_name',$order_info['service_name'])->find();
                            if ($member_info['pcode']!='' && $integral_info){
                                $parent_info=db('member')->where('code',$member_info['pcode'])->field('integral,id,level_int')->find();
                                if ($parent_info){
                                    $add_integral = (float)$integral_info['integral']*(float)$order_info['price'];
                                    $parent_save=[
                                        'integral'=>(float)$parent_info['integral']+$add_integral,
                                        'level_int'=>(float)$parent_info['level_int']+$add_integral
                                    ];
                                    db('member')->where('id',$parent_info['id'])->update($parent_save);
                                }
                            }
                        }else{
                            $order_save['status']=3;
                        }
                        $order_save['pay_money']=$money;
                        $order_save['pay_time']=get_time(time(),0);
                        db('order')->where('id',$payinfo['order_id'])->update($order_save);
                        //向客户发送模板消息,此时需要给订单负责人推送消息吗？
                        $app_key=config('jpush.app_key');
                        $master_secret=config('jpush.master_secret');
                        $client=new \JPush\Client($app_key,$master_secret,null);
                        $client_arr = UserData::getGeTuiByUid( $payinfo['uid'] );
                        $pusher = $client->push();
                        $pusher->setPlatform('all');
                        $message=new Message();
                        foreach ($client_arr as $key=>$val){//用户可能登录多个终端（员工端不需要发送此消息，会员端只能有一个终端登录，故其实不存在多个终端）
                            $pusher->addRegistrationId($val['registration_id']);
                            $pusher->setNotificationAlert("尊敬的客户,我司已收到您的款项！");
                            $message->registration_id=$val['registration_id'];
                            $message->title='付款成功提醒';
                            $message->content='尊敬的客户,我司已收到您的款项！';
                            $message->isUpdate(false)->save();
                            try {
                                $pusher->send();
                            } catch (\JPush\Exceptions\JPushException $e) {
                                // try something else here
                                print $e;
                            }
                        }
                        Db::commit();
                    }catch (\Exception $e){
                        Db::rollback();
                        var_dump($e->getMessage());
                        Log::write('回调处理失败：'.$e->getMessage() , Log::ERROR);
                        Log::write('回调处理文件：'.$e->getFile() , Log::ERROR);
                        Log::write('回调处理行号：'.$e->getLine() , Log::ERROR);
                        // parent::put_post(['status' => 1004, 'info' => '服务器异常！']);
                    }
                }
            } else {
                $this->success($returnstr2);
            }
        }
    }

    public function wxpay_refund_callback() {
        $data = file_get_contents("php://input");
        if ($data) {
            $newdataobj = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            $newdataobj = json_encode($newdataobj);
            $newdata = json_decode($newdataobj, true);
            db('error_log')->insert(['desc' => json_encode($newdata), 'num' => 2]);
            if ($newdata['return_code'] == 'SUCCESS') {
                $reqinfo = $newdata['req_info'];
                $orderinfo = $this->Decryption($reqinfo);

                $outrefundno = $orderinfo['out_refund_no'];
                $cacheno = Cache::get('order' . $outrefundno);
                $payinfo = db('pay_log')->field('order_id')->where(['refund_no' => $outrefundno, 'status' => 2])->find();

                if ($cacheno) {
                    $result = [];
                    $result['RETURN_CODE'] = 'SUCCESS';
                    $result['RETURN_MSG'] = 'OK';
                    $result = arrayToXml($result);
                    return $result;
                }
                if ($orderinfo['refund_status'] == 'SUCCESS') {

                    $uplog = [];
                    $uplog['status'] = 4;
                    $res = db('pay_log')->where(['refund_no' => $outrefundno])->update($uplog);

                    $updata = [];
                    $updata['status'] = 10;
                    $res1 = db('order')->where(['id' => $payinfo['order_id']])->update($updata);

                    if (false === $res || false === $res1) {
                        $result = [];
                        $result['RETURN_CODE'] = 'SUCCESS';
                        $result['RETURN_MSG'] = 'OK';
                        $result = arrayToXml($result);
                        return $result;
                    } else {

                        Cache::set('order_' . $outrefundno, 1, 43200);

                        $result = [];
                        $result['RETURN_CODE'] = 'SUCCESS';
                        $result['RETURN_MSG'] = 'OK';
                        $result = arrayToXml($result);
                        return $result;
                    }
                } else {
                    $uplog = [];
                    $uplog['status'] = 5;
                    $res = db('pay_log')->where(['refund_no' => $outrefundno])->update($uplog);

                    $updata = [];
                    $updata['status'] = 11;
                    $res1 = db('order')->where(['id' => $payinfo['order_id']])->update($updata);

                    if (false === $res || false === $res1) {
                        $result = [];
                        $result['return_code'] = 'FAIL';
                        $result['return_msg'] = 'OK';
                        $result = arrayToXml($result);
                        return $result;
                    } else {
                        $result = [];
                        $result['return_code'] = 'SUCCESS';
                        $result['return_msg'] = 'OK';
                        $result = arrayToXml($result);
                        return $result;
                    }
                }
            }
        }
    }

    /*
     * 验证微信SIGN
     * 可以多种方式 直接将pay_log表内sign 字段和微信回调内的签名（需经过MD5）对比 
     * 可重新生成签名对比 更安全
     */

    private function MakeSign($inputarr) {

        //签名步骤一：按字典序排序参数
        ksort($inputarr);
        $config = config('weixinpay');

        $string = $this->ToUrlParams($inputarr);

        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $config['pay_secret'];

        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        if ($inputarr['sign'] == $result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 格式化参数格式化成url参数
     */
    private function ToUrlParams($inputarr) {
        $buff = "";
        foreach ($inputarr as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /*
     * 退款回调解密函数
     */

    public function Decryption($str) {
        $config = config('weixinpay');
        $key = $config['pay_secret'];

        $newstr = base64_decode($str);
        $decrypted = openssl_decrypt($newstr, 'aes-256-ecb', md5($key), OPENSSL_RAW_DATA);
        $newdataobj = simplexml_load_string($decrypted, 'SimpleXMLElement', LIBXML_NOCDATA);
        $newdataobj = json_encode($newdataobj);
        $newdata = json_decode($newdataobj, true);
        return $newdata;
    }

    //微信
    public function wxPay() {
        $post = V('p');
        //unset($post['m']);
        if (empty($post)) {
            JSOND('-1', 'Not post request!');
        }
        extract($post);

        $order = DS('publics.get_info', '', 'amount_detail', 'orderno="' . $orderno . '"', '', 1);

        if ($order['money'] != $money) {
            JSOND('-3', '订单金额不正确,不允许进行支付');
        }

        $tempMoney = 0;
        if ($memberid == '418') {
            $tempMoney = 1;
        } else if ($memberid == '358') {
            $tempMoney = 2;
        } else {
            if (IS_PRODUCT) {
                $tempMoney = $money * 100;
            } else {
                $tempMoney = 1;
            }
        }

        WxPayApi::setConfig(WX_APPID, WX_MCHID, WX_PAY_KEY);
        $outTradeNo = substr(WX_APPID, -6) . $memberid . date("YmdHis");
        $rt = DS('publics._update', '', array('out_trade_no' => $outTradeNo), 'amount_detail', 'orderno', $orderno);
        if (!$rt) {
            JSOND('-4', '订单更新出错,不允许进行支付');
        }
        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($order['intro']);
        $input->SetOut_trade_no($outTradeNo);
        // 微信的金额单位为：分
        $input->SetTotal_fee($tempMoney);
        $input->SetNotify_url('http://' . API_DOMAIN . '/admin.php/mgr.notify.wxPay');
        $input->SetTrade_type("APP");
        // 限制信用卡；
        $input->SetLimitPay("no_credit");
        $response = WxPayApi::unifiedOrder($input);

        if ($response['result_code'] == 'SUCCESS') {
            // 进行二次签名
            //$two_data = new WxPayTwoSign();
            $response = WxPayApi::twoSign($response);
        }

        LogMgr::log('wxpay ===', 'wxpay_str:' . json_encode($response));
        JSOND('0', 'ok', $response);
    }

    /*
     * 生成劵号
     */

    public function get_ticket_no($list) {
        if ($list['type'] == 3) {
            $goods = db('order_goods')->field('product_id,shop_id')->where(['order_id' => $list['id']])->find();
            $ticketinfo = db('shop_ticket')->field('sell_type')->where(['id' => $goods['product_id']])->find();
            $num = $list['total_num'];
            for ($i = 0; $i < $num; $i++) {
                $insert = [];
                $insert['order_id'] = $list['id'];
                $insert['user_id'] = $list['user_id'];
                $insert['ticket_id'] = $goods['product_id'];
                $insert['ticket_no'] = time() . rand(10000, 99999) . $i;
                $insert['shop_id'] = $goods['shop_id'];
                $insert['status'] = 1;
                if ($ticketinfo) {
                    if ($ticketinfo['sell_type'] == 1) {
                        $insert['type'] = 2;
                    } else {
                        $insert['type'] = 3;
                    }
                }
                $insert['create_time'] = time();
                db('user_ticket')->insert($insert);
            }
            return true;
        } else {
            return false;
        }
    }

    //得到充值成功后的抵用券
    private function get_ticket($orderid, $userid) {
        $insert = [];
        $insert['user_id'] = $userid;
        $insert['order_id'] = $orderid;
        $insert['ticket_id'] = 127;
        $insert['ticket_no'] = '';
        $insert['shop_id'] = 0;
        $insert['status'] = 1;
        $insert['type'] = 1;
        $insert['create_time'] = time();
        db('user_ticket')->insert($insert);
        db('user_ticket')->insert($insert);
        return true;
    }

//    public function test() {
//        $list['id'] = 88;
//        $list['user_id'] = 31;
//        $list['type'] = 3;
//        $list['total_num'] = 3;
//        $res = $this->get_ticket_no($list);
//        halt($res);
//    }
}
