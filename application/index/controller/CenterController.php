<?php
namespace app\index\controller;
use think\captcha\Captcha;      //验证码
/**
 * 我的个人中心 控制器
 */
class CenterController extends CommonController {

    /**
     * 底部导航栏
     */
    public function mine() {
        //判断是否登录
        $user_info = parent::check_login(); 
        //用户表 模型
        $user = model('User');
        //查询用户表 数据
        $response = $user->get(['uid'=>$user_info['uid']]);
        //判断是否数据接口用户
        $port_type = 2;     //数据接口用户标识
        if($response['port_type'] == 1) {
            //数据接口表 模型
            $port_data = model('PortData');
            //查询数据接口表数据
            $list_port = $port_data->field('status')->where("uid",$user_info['uid'])->find();
            //判断用户状态
            if($list_port['status'] != '启用') {
                $port_type = 1;
            }
        }
        //渲染数据
        $this->assign('user_info', $response);
        $this->assign('port_type', $port_type);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 消息中心
     */
    public function message() {
        //公告表 模型
        $notice = model('Notice');
        //获取公告表数据
        $map['show']=1;
        $map['recommend']=1;
        $list_notice = $notice->where($map)->order('create_time desc')->select();
        //渲染数据
        $this->assign('list_notice', $list_notice);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 我的订单列表
     */
    public function my_list() {
        //判断是否登录
        $user_info = parent::check_login();
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //获取  客服电话数据
        $list_deploy = $system_deploy->field('mobile')->find();
        //期权持仓订单表 模型
        $order = model('Order');
        //封装搜索条件
        $where['possessor_uid'] = ['=',$user_info['uid']];  //持有人uid
        $where['status'] = ['=',1];                         //1:未结算 2:已结算
        $where['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核
        //获取期权交易动态数据
        $list_order = $order->where($where)->order('create_time desc')->select();
        //股票数据表 模型
        $stock = model('Stock');
        //处理期权持仓订单数据
        foreach($list_order as $key=>$value) {
            $list_order[$key]['eventual_price'] = $value['stock']['trade'];   //现价
            $list_order[$key]['begin_time'] = date("Y-m-d",$value['begin_time']);       //开始时间
            $list_order[$key]['end_time'] = date("Y-m-d",$value['end_time']);       //结束时间
            $list_order[$key]['income'] = round(($list_order[$key]['eventual_price'] - $value['initial_price'])*($value['principal']/$value['initial_price']),2);   //创收(元)
            $list_order[$key]['income_format'] = number_format(($list_order[$key]['eventual_price'] - $value['initial_price'])*($value['principal']/$value['initial_price']),2);   //创收(元),加逗号
            $list_order[$key]['royalty'] = number_format($list_order[$key]['royalty'],2);   //权利金
            $list_order[$key]['principal'] = ($value['principal']/10000).'万';       //名义本金(万)
            //对接人姓名
            $list_order[$key]['superior_uid'] = empty($value['superior_uid'])? parent::user_service() : $value['superior']['client']['name'];
        }


        //封装搜索条件
        $where_accounts['possessor_uid'] = ['=',$user_info['uid']];  //持有人uid
        $where_accounts['status'] = ['=',2];                         //1:未结算 2:已结算
        $where_accounts['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核
        $time_period=$order->where($where_accounts)->order('begin_time')->column('begin_time');
        $min_year=date('Y',$time_period[0]);//$min_year=2000;
        $max_year=date('Y',end($time_period));//$max_year=2019;
        $array_year[0]=$min_year;
        if ($max_year>$min_year) {
            $n=$max_year-$min_year;
            for ($i=1; $i<$n+1 ; $i++) { 
                $array_year[$i]=$min_year+$i;

            }
        }
       
        $year=date('Y',time());//print_r($array_year);
        // $list_accounts = array();
        // foreach ($array_year as $k => $v) {
        //     //本年初始时间戳
        //     $yeartime =mktime(0,0,0,1,1,$v); 
        //     $nextyeartime=$yeartime+86400*365; 
        //     $where_accounts['begin_time'] = [['>',$yeartime],['<',$nextyeartime],'and']; //创建时间
        //     $profit[$k]=$order->where($where_accounts)->sum('actual_income');
        //     //获取期权交易结算数据
        //     $data_order[$k] = $order->where($where_accounts)->order('create_time desc')->limit(6)->select();
        //     //echo $order->getLastSql();exit;
        //     //处理期权持仓订单数据
            
        //     foreach($data_order[$k] as $key=>$value) {
        //         $list_accounts[$k][$key]['order_id'] = $value['order_id'];                       //订单表id
        //         $list_accounts[$k][$key]['title'] = $value['title'].' ('.$value['code'].')';     //股票名称和代码
        //         $list_accounts[$k][$key]['principal'] = ($value['principal']/10000).'万元';     //名义本金万元
        //         $list_accounts[$k][$key]['time'] = date("Y-m-d",$value['begin_time']).'--'.date("Y-m-d",$value['end_time']);       //开始时间--结束时间
        //         $list_accounts[$k][$key]['actual_income'] = number_format($value['actual_income'],2);     //创收
        //     }
        // }
        //本年初始时间戳
        $yeartime =mktime(0,0,0,1,1,date('Y')); 
        $nextyeartime=$yeartime+86400*365; 
        $where_accounts['begin_time'] = [['>',$yeartime],['<',$nextyeartime],'and']; //创建时间

        //计算年份总盈利
        $profit = $order->where($where_accounts)->sum('actual_income');
        //echo $order->getLastSql();exit;
        //获取期权交易结算数据
        $data_order = $order->where($where_accounts)->order('create_time desc')->limit(6)->select();
        //echo $order->getLastSql();exit;
        //处理期权持仓订单数据
        $list_accounts = array();
        foreach($data_order as $key=>$value) {
            $list_accounts[$key]['order_id'] = $value['order_id'];                       //订单表id
            $list_accounts[$key]['title'] = $value['title'].' ('.$value['code'].')';     //股票名称和代码
            $list_accounts[$key]['principal'] = ($value['principal']/10000).'万元';     //名义本金万元
            $list_accounts[$key]['time'] = date("Y-m-d",$value['begin_time']).'--'.date("Y-m-d",$value['end_time']);       //开始时间--结束时间
            $list_accounts[$key]['actual_income'] = number_format($value['actual_income'],2);     //创收
        }
       //echo '<pre>'; print_r($profit);//echo '<pre>'; print_r($data_order);
       //echo '<pre>'; print_r($list_accounts);exit;
        //快递公司表 模型
        $express_company = model('ExpressCompany');
        //获取快递公司表数据
        $list_company = $express_company->select();

        //渲染数据
        $this->assign('year', $year);
        $this->assign('array_year', $array_year);
        $this->assign('list_order', $list_order);
        $this->assign('list_deploy', $list_deploy);
        $this->assign('profit', $profit);
        $this->assign('list_accounts', $list_accounts);
        $this->assign('list_company', $list_company);
        $this->assign('identification', $user_info['identification']);
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 帮助中心
     */
    public function help() {
        //帮助中心表 模型
        $center = model('Center');
        //获取帮助中心表数据
        $list_center = $center->order('sort desc')->select();

        //渲染数据
        $this->assign('list_center', $list_center);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 地址管理
     */
    public function address() {
        //判断是否登录
        $user_info = parent::check_login();
        //我的地址表 模型
        $address = model('Address');
        //查询收货地址数据
        $list_address = $address->where("uid",$user_info['uid'])->order("create_time asc")->order("status asc")->select();
        //循环遍历
        foreach($list_address as $key=>$value) {
            //封装完整地址
            $list_address[$key]['address'] = $value['province'].$value['city'].$value['county'].$value['address'];
        }

        //渲染数据
        $this->assign('list_address', $list_address);
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 地址编辑
     */
    public function address_edit() {
        //判断是否登录
        $user_info = parent::check_login();
        //判断是否是post提交方式
        if(request()->isPost()) {
            //我的地址表 验证器
            $validate = validate('Address');
            //表单提交数据
            $param = input('post.');
            //封装用户uid
            $param['uid'] = $user_info['uid'];
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //我的地址表 模型
            $address = model('Address');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                $result = $address->allowField(true)->save($param);
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $address->allowField(true)->save($param,['address_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                $this->success('操作成功', 'center/address','',1);
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
            //我的地址表 模型
            $address = model('Address');
            //查询地址信息
            $list = $address->get($id);
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 个人信息修改
     */
    public function information() {
        //判断是否登录
        $user_info = parent::check_login();
        //验证码的配置参数
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 设置验证码字符为纯数字
            //'codeSet '    =>    '0123456789',
            // 验证码图片高度
            //'imageH'      =>    '500',
            // 验证码图片宽度
            //'imageW'      =>    '500',
        ];

        //实例化验证码类
        $captcha = new Captcha($config);
        //渲染数据
        $this->assign('img_code', $captcha->base64_entry());
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 在线留言
     */
    public function online_msg() {
        //判断是否登录
        $user_info = parent::check_login();

        //渲染数据
        $this->assign('token', $user_info['token']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 我的业绩
     */
    public function achievement() {
        //判断是否登录
        $user_info = parent::check_login();
        //总订单数
        $order_count = 0;
        //模型多次循环出错 Indirect modification of overloaded element of app\\api\\model\\User has no effect，不能修改其值
        //用户表
        $user = db('User');
        //期权持仓订单表
        $order = db('Order');
        //前端管理员/机构/代理人/普通用户信息表
        $info_client = db('InfoClient');
        //封装搜索条件
        $where['audit'] = ['=',1];                         //1:成功 2:失败 3:待审核

        //判断用户角色
        switch($user_info['group_id']) {
            case '前端管理员':
                //查询状态启用的机构用户
                $response = $user->field('uid')->where(['group_id'=>5,'status'=>1])->select();

                foreach($response as $key=>$value) {
                    //封装机构用户数据
                    $response[$key]['order_count'] = 0;                 //总单数
                    $response[$key]['principal_count'] = 0;             //总名义本金(万)
                    $client_name = $info_client->field('name')->where('uid',$value['uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    //查询状态启用的下级代理人
                    $response[$key]['agent'] = $user->field('uid')->where(['group_id'=>3,'belong_id'=>$value['uid'],'status'=>1])->select();
                    foreach($response[$key]['agent'] as $ke=>$va) {
                        //封装搜索条件
                        $where['superior_uid'] = $va['uid'];        //订单对接人uid
                        //封装代理人用户数据
                        $response[$key]['agent'][$ke]['order_count'] = $order->where($where)->count();         //总单数
                        $response[$key]['agent'][$ke]['principal_count'] = $order->where($where)->sum('principal')/10000;     //总名义本金(万)
                        $client_name = $info_client->field('name')->where('uid',$va['uid'])->find();
                        $response[$key]['agent'][$ke]['name'] = $client_name['name']; //姓名
                        $response[$key]['agent'][$ke]['client'] = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->order('create_time desc')->select();     //查询下线订单

                        foreach($response[$key]['agent'][$ke]['client'] as $k=>$v) {
                            //封装普通用户数据
                            $client_name = $info_client->field('name')->where('uid',$v['possessor_uid'])->find();
                            $response[$key]['agent'][$ke]['client'][$k]['name'] = $client_name['name']; //姓名
                            $response[$key]['agent'][$ke]['client'][$k]['principal'] = $v['principal']/10000; //名义本金(万)
                            $response[$key]['agent'][$ke]['client'][$k]['time'] = date('Y-m-d',$v['begin_time']).'至'.date('Y-m-d',$v['end_time']); //期限
                        }
                        //增加机构总单数
                        $response[$key]['order_count'] += $response[$key]['agent'][$ke]['order_count'];
                        //增加机构总名义本金
                        $response[$key]['principal_count'] += $response[$key]['agent'][$ke]['principal_count'];
                    }
                    //增加总单数
                    $order_count += $response[$key]['order_count'];
                }
                break;
            case '机构':
                //查询状态启用的下线代理人用户
                $response = $user->field('uid')->where(['group_id'=>3,'status'=>1,'belong_id'=>$user_info['uid']])->select();

                foreach($response as $key=>$value) {
                    //封装搜索条件
                    $where['superior_uid'] = $value['uid'];        //订单对接人uid
                    //封装代理人用户数据
                    $response[$key]['order_count'] = $order->where($where)->count();         //总单数
                    $response[$key]['principal_count'] = $order->where($where)->sum('principal')/10000;     //总名义本金(万)
                    $client_name = $info_client->field('name')->where('uid',$value['uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    $response[$key]['client'] = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->order('create_time desc')->select();       //查询下线订单

                    foreach($response[$key]['client'] as $k=>$v) {
                        //封装普通用户数据
                        $client_name = $info_client->field('name')->where('uid',$v['possessor_uid'])->find();
                        $response[$key]['client'][$k]['name'] = $client_name['name']; //姓名
                        $response[$key]['client'][$k]['principal'] = $v['principal']/10000; //名义本金(万)
                        $response[$key]['client'][$k]['time'] = date('Y-m-d',$v['begin_time']).'至'.date('Y-m-d',$v['end_time']); //期限
                    }
                    //增加总单数
                    $order_count += $response[$key]['order_count'];
                }
                break;
            case '代理人':
                //封装搜索条件
                $where['superior_uid'] = $user_info['uid'];        //订单对接人uid
                //总单数
                $order_count = $order->where($where)->count();         //总单数
                //查询下线订单
                $response = $order->field('possessor_uid,principal,begin_time,end_time')->where($where)->order('create_time desc')->select();
                foreach($response as $key=>$value) {
                    //封装普通用户数据
                    $client_name = $info_client->field('name')->where('uid',$value['possessor_uid'])->find();
                    $response[$key]['name'] = $client_name['name']; //姓名
                    $response[$key]['principal'] = $value['principal']/10000; //名义本金(万)
                    $response[$key]['time'] = date('Y-m-d',$value['begin_time']).'至'.date('Y-m-d',$value['end_time']); //期限
                }
                break;
            default:
                $response = array();
        }

        //渲染数据
        $this->assign('token', $user_info['token']);
        $this->assign('response', $response);
        $this->assign('order_count', $order_count);
        $this->assign('group_id', $user_info['group_id']);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 经济注册
     */
    public function registration() {
        //判断是否登录
        $user_info = parent::check_login();
        //验证码的配置参数
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 设置验证码字符为纯数字
            //'codeSet '    =>    '0123456789',
            // 验证码图片高度
            //'imageH'      =>    '500',
            // 验证码图片宽度
            //'imageW'      =>    '500',
        ];

        //实例化验证码类
        $captcha = new Captcha($config);
        //渲染数据
        $this->assign('img_code', $captcha->base64_entry());
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口申请页面
     */
    public function port() {
        //幻灯片表 模型
        $slide = model('Slide');
        //查询幻灯片数据
        $list_slide = $slide->where("show",1)->where('type',2)->order("sort desc")->select();

        //渲染数据
        $this->assign('list_slide', $list_slide);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口注册页面
     */
    public function port_application() {
        //判断是否登录
        $user_info = parent::check_login();
        //验证码的配置参数
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 设置验证码字符为纯数字
            //'codeSet '    =>    '0123456789',
            // 验证码图片高度
            //'imageH'      =>    '500',
            // 验证码图片宽度
            //'imageW'      =>    '500',
        ];

        //实例化验证码类
        $captcha = new Captcha($config);
        //渲染数据
        $this->assign('img_code', $captcha->base64_entry());
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口管理页面
     */
    public function port_essential() {
        //判断是否登录
        $user_info = parent::check_login();
        //数据接口表 模型
        $port_data = model('PortData');
        //查询数据接口表数据
        $list_port = $port_data->where("uid",$user_info['uid'])->find();
        //判断用户状态
        if($list_port['status'] != '启用') {
            echo '用户状态未启用,请联系管理员!';exit;
        }
        //处理时间戳
        $list_port['end_time'] = date('Y-m-d H:i:s',$list_port['end_time']);

        //渲染数据
        $this->assign('list_port', $list_port);
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口续费页面
     */
    public function port_renew() {
        //判断是否登录
        $user_info = parent::check_login();
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //获取  续费费用(元)
        $renew = $system_deploy->field('renew')->find();
        //幻灯片 模型
        $slide = model('Slide');
        //获取续费界面的幻灯片
        $list_slide = $slide->where(['show'=>1,'type'=>3])->order('sort desc')->find();

        //渲染数据
        $this->assign('renew', $renew);
        $this->assign('user_info', $user_info);
        $this->assign('list_slide', $list_slide);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口 调用说明
     */
    public function port_explain() {
        //判断是否登录
        $user_info = parent::check_login();
        //接口调用表 模型
        $port_explain = model('PortExplain');
        //获取 调用说明列表数据
        $data_explain = $port_explain->order('sort desc')->select();

        //渲染数据
        $this->assign('data_explain', $data_explain);
        $this->assign('user_info', $user_info);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口 调用说明详情
     */
    public function detail() {
        //获取表id数据
        $param =  input('param.');
        //验证数据
        if(empty($param['port_id'])) {
            echo  '非法错误,接口说明表id不存在!';exit;
        }
        //分类id
        $where['port_id'] = ['=',$param['port_id']];
        //接口调用表 模型
        $port_explain = model('PortExplain');
        //查询接口调用表数据
        $list_explain = $port_explain->where($where)->find();
        //判断是否查询到接口调用表数据
        if(empty($list_explain)) {
            echo  '非法错误,接口调用内容不存在!';exit;
        }

        //渲染数据
        $this->assign('list_explain', $list_explain);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 数据接口 测试信息
     */
    public function port_test() {
        //判断是否登录
        $user_info = parent::check_login();
        //数据接口表 模型
        $port_data = model('PortData');
        //查询数据接口数据
        $data_port = $port_data->where(['uid'=>$user_info['uid']])->find();
        //股票数据表 模型
        $stock = model('Stock');
        //查询股票数据
        $data_stock = $stock->where('symbol','000001')->find();
        //股票汇率表 模型
        $rate = model('Rate');
        //查询股票汇率数据
        $data_rate = $rate->where('code','000001')->find();
        //期权汇率
        $rate = $data_rate['month1'] + $data_port['float'];
        //计算权利金
        $royalty = number_format(1000000*$rate,2);//权利金
        //开始时间
        $begin_time = date('Y-m-d');
        //结束时间
        $end_time = date('Y-m-d',strtotime('+1 month') - 86400);

        //渲染数据
        $this->assign('rate', $rate);
        $this->assign('royalty', $royalty);
        $this->assign('begin_time', $begin_time);
        $this->assign('end_time', $end_time);
        //模板渲染
        return $this->fetch();
    }



}