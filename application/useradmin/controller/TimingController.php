<?php
namespace app\useradmin\controller;
use think\Controller;

/**
 * liunx 定时任务(抓取股票数据、发送短信等任务)  控制器
 */
class  TimingController extends Controller
{
    //配置您申请的appkey
    private $appkey = "f08d938eaae97306c2cf74782ac3e855";

    //获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
    public function getfirstchar($s0){
        $fchar = ord($s0{0});
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
        //$s1 = iconv("UTF-8","gb2312", $s0);
        //$s2 = iconv("gb2312","UTF-8", $s1);
        //if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $s = $s0;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "H";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return NULL;
        //return $s0;
    }
    public function pinyin_long($zh){  //获取整条字符串汉字拼音首字母
        $ret = "";
        $s2 = iconv("UTF-8","gb2312//TRANSLIT//IGNORE", $zh);
        $zh = $s2;
        //$s2 = iconv("gb2312","UTF-8", $s1);
        //sif($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= self::getfirstchar($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }

    /**
     * 从聚合数据接口抓取深圳股市 列表数据
     */
    public function sz_stock()
    {
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数数据
        $system_data = $system_deploy->field('remind_stock')->find();
        //判断获取股票数据是否开启
        if($system_data['remind_stock'] == 1) {
            //设置运行时间
            set_time_limit(0);
            //股票数据表
            $stock = db('Stock');

            //==================深圳股市列表 接口地址========================================
            $url = "http://web.juhe.cn:8080/finance/stock/szall";
            //封装接口参数
            $params = array(
                "key" => $this->appkey,//您申请的APPKEY
                "page" => "1",//第几页(每页20条数据),默认第1页
                "type" => "4"//每页返回条数,4(80条)
            );
            //将数组生成 URL-encode 之后的请求字符串
            $paramstring = http_build_query($params);
            //请求接口返回内容
            $content = $this->juhecurl($url,$paramstring);
            //转为数组格式
            $result = json_decode($content,true);
            //判断返回状态码是否为0
            if($result['error_code']=='0'){
                //计算数据总页数
                $page = round($result['result']['totalCount'] / 80);
                //循环数据并更新数据库
                foreach ($result['result']['data'] as $key=>$value) {
                    //封装股票数据表数据
                    $param_stock['symbol'] = $value['code'];        //股票代码
                    $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                    $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                    $param_stock['trade'] = $value['trade'];        //股票最新价格
                    $param_stock['type'] = 1;                       //股市类型 1:sz
                    $param_stock['update_time'] = date("Y-m-d H:i:s");
                    //更新股票数据表数据
                    $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                    //判断是否更新成功
                    if(!$result_stock) {
                        $param_stock['create_time'] = date("Y-m-d H:i:s");
                        //添加股票汇率表数据
                        $stock->insert($param_stock);
                    }
                }
            }else{
                //输出错误状态码
                echo $result['error_code'].":".$result['reason'];
                exit;
            }
            $i = 1;     //标识起始数
            do {
                //标识数递增
                $i++;
                //修改接口参数第几页
                $params['page'] = $i;
                //将数组生成 URL-encode 之后的请求字符串
                $paramstring = http_build_query($params);
                //请求接口返回内容
                $content = $this->juhecurl($url,$paramstring);
                //转为数组格式
                $result = json_decode($content,true);
                //判断返回状态码是否为0
                if($result['error_code']=='0'){
                    //循环数据并更新数据库
                    foreach ($result['result']['data'] as $key=>$value) {
                        $param_stock = array();
                        //封装股票数据表数据
                        $param_stock['symbol'] = $value['code'];        //股票代码
                        $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                        $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                        $param_stock['trade'] = $value['trade'];        //股票最新价格
                        $param_stock['type'] = 1;                       //股市类型 1:sz
                        $param_stock['update_time'] = date("Y-m-d H:i:s");
                        //更新股票数据表数据
                        $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                        //判断是否更新成功
                        if(!$result_stock) {
                            $param_stock['create_time'] = date("Y-m-d H:i:s");
                            //添加股票汇率表数据
                            $stock->insert($param_stock);
                        }
                    }
                }
            } while ($i<=$page);

            echo 'oksz';
        }
    }

    /**
     * 从聚合数据接口抓取沪股 列表数据
     */
    public function sh_stock()
    {
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数数据
        $system_data = $system_deploy->field('remind_stock')->find();
        //判断获取股票数据是否开启
        if($system_data['remind_stock'] == 1) {
            //设置运行时间
            set_time_limit(0);
            //股票数据表
            $stock = db('Stock');

            //==================沪股列表 接口地址========================================
            $url = "http://web.juhe.cn:8080/finance/stock/shall";
            //封装接口参数
            $params = array(
                "key" => $this->appkey,//您申请的APPKEY
                "page" => "1",//第几页(每页20条数据),默认第1页
                "type" => "4"//每页返回条数,4(80条)
            );
            //将数组生成 URL-encode 之后的请求字符串
            $paramstring = http_build_query($params);
            //请求接口返回内容
            $content = $this->juhecurl($url,$paramstring);
            //转为数组格式
            $result = json_decode($content,true);
            //判断返回状态码是否为0
            if($result['error_code']=='0'){
                //计算数据总页数
                $page = round($result['result']['totalCount'] / 80);

                //循环数据并更新数据库
                foreach ($result['result']['data'] as $key=>$value) {
                    //封装股票数据表数据
                    $param_stock['symbol'] = $value['code'];        //股票代码
                    $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                    $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                    $param_stock['trade'] = $value['trade'];        //股票最新价格
                    $param_stock['type'] = 2;                       //股市类型 2:sh
                    $param_stock['update_time'] = date("Y-m-d H:i:s");
                    //更新股票数据表数据
                    $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                    //判断是否更新成功
                    if(!$result_stock) {
                        $param_stock['create_time'] = date("Y-m-d H:i:s");
                        //添加股票汇率表数据
                        $stock->insert($param_stock);
                    }
                }
            }else{
                //输出错误状态码
                echo $result['error_code'].":".$result['reason'];
                exit;
            }
            $i = 1;     //标识起始数
            do {
                //标识数递增
                $i++;
                //修改接口参数第几页
                $params['page'] = $i;
                //将数组生成 URL-encode 之后的请求字符串
                $paramstring = http_build_query($params);
                //请求接口返回内容
                $content = $this->juhecurl($url,$paramstring);
                //转为数组格式
                $result = json_decode($content,true);
                //判断返回状态码是否为0
                if($result['error_code']=='0'){
                    //循环数据并更新数据库
                    foreach ($result['result']['data'] as $key=>$value) {
                        $param_stock = array();
                        //封装股票数据表数据
                        $param_stock['symbol'] = $value['code'];        //股票代码
                        $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                        $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                        $param_stock['trade'] = $value['trade'];        //股票最新价格
                        $param_stock['type'] = 2;                       //股市类型 2:sh
                        $param_stock['update_time'] = date("Y-m-d H:i:s");
                        //更新股票数据表数据
                        $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                        //判断是否更新成功
                        if(!$result_stock) {
                            $param_stock['create_time'] = date("Y-m-d H:i:s");
                            //添加股票汇率表数据
                            $stock->insert($param_stock);
                        }
                    }
                }
            } while ($i<=$page);

            echo 'oksh';
        }
    }



    /**
     * 从聚合数据接口抓取深圳股市 列表数据
     */
    public function refresh_sz_stock()
    {
        //设置运行时间
        set_time_limit(0);
        //股票数据表
        $stock = db('Stock');

        //==================深圳股市列表 接口地址========================================
        $url = "http://web.juhe.cn:8080/finance/stock/szall";
        //封装接口参数
        $params = array(
            "key" => $this->appkey,//您申请的APPKEY
            "page" => "1",//第几页(每页20条数据),默认第1页
            "type" => "4"//每页返回条数,4(80条)
        );
        //将数组生成 URL-encode 之后的请求字符串
        $paramstring = http_build_query($params);
        //请求接口返回内容
        $content = $this->juhecurl($url,$paramstring);
        //转为数组格式
        $result = json_decode($content,true);
        //判断返回状态码是否为0
        if($result['error_code']=='0'){
            //计算数据总页数
            $page = round($result['result']['totalCount'] / 80);
            //循环数据并更新数据库
            foreach ($result['result']['data'] as $key=>$value) {
                //封装股票数据表数据
                $param_stock['symbol'] = $value['code'];        //股票代码
                $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                $param_stock['trade'] = $value['trade'];        //股票最新价格
                $param_stock['type'] = 1;                       //股市类型 1:sz
                $param_stock['update_time'] = date("Y-m-d H:i:s");
                //更新股票数据表数据
                $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                //判断是否更新成功
                if(!$result_stock) {
                    $param_stock['create_time'] = date("Y-m-d H:i:s");
                    //添加股票汇率表数据
                    $stock->insert($param_stock);
                }
            }
        }else{
            //输出错误状态码
            echo $result['error_code'].":".$result['reason'];
            exit;
        }
        $i = 1;     //标识起始数
        do {
            //标识数递增
            $i++;
            //修改接口参数第几页
            $params['page'] = $i;
            //将数组生成 URL-encode 之后的请求字符串
            $paramstring = http_build_query($params);
            //请求接口返回内容
            $content = $this->juhecurl($url,$paramstring);
            //转为数组格式
            $result = json_decode($content,true);
            //判断返回状态码是否为0
            if($result['error_code']=='0'){
                //循环数据并更新数据库
                foreach ($result['result']['data'] as $key=>$value) {
                    $param_stock = array();
                    //封装股票数据表数据
                    $param_stock['symbol'] = $value['code'];        //股票代码
                    $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                    $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                    $param_stock['trade'] = $value['trade'];        //股票最新价格
                    $param_stock['type'] = 1;                       //股市类型 1:sz
                    $param_stock['update_time'] = date("Y-m-d H:i:s");
                    //更新股票数据表数据
                    $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                    //判断是否更新成功
                    if(!$result_stock) {
                        $param_stock['create_time'] = date("Y-m-d H:i:s");
                        //添加股票汇率表数据
                        $stock->insert($param_stock);
                    }
                }
            }
        } while ($i<=$page);

        $this->success('更新sz数据成功', 'stock/stock_data','',1);
    }

    /**
     * 从聚合数据接口抓取沪股 列表数据
     */
    public function refresh_sh_stock()
    {
        //设置运行时间
        set_time_limit(0);
        //股票数据表
        $stock = db('Stock');

        //==================沪股列表 接口地址========================================
        $url = "http://web.juhe.cn:8080/finance/stock/shall";
        //封装接口参数
        $params = array(
            "key" => $this->appkey,//您申请的APPKEY
            "page" => "1",//第几页(每页20条数据),默认第1页
            "type" => "4"//每页返回条数,4(80条)
        );
        //将数组生成 URL-encode 之后的请求字符串
        $paramstring = http_build_query($params);
        //请求接口返回内容
        $content = $this->juhecurl($url,$paramstring);
        //转为数组格式
        $result = json_decode($content,true);
        //判断返回状态码是否为0
        if($result['error_code']=='0'){
            //计算数据总页数
            $page = round($result['result']['totalCount'] / 80);

            //循环数据并更新数据库
            foreach ($result['result']['data'] as $key=>$value) {
                //封装股票数据表数据
                $param_stock['symbol'] = $value['code'];        //股票代码
                $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                $param_stock['trade'] = $value['trade'];        //股票最新价格
                $param_stock['type'] = 2;                       //股市类型 2:sh
                $param_stock['update_time'] = date("Y-m-d H:i:s");
                //更新股票数据表数据
                $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                //判断是否更新成功
                if(!$result_stock) {
                    $param_stock['create_time'] = date("Y-m-d H:i:s");
                    //添加股票汇率表数据
                    $stock->insert($param_stock);
                }
            }
        }else{
            //输出错误状态码
            echo $result['error_code'].":".$result['reason'];
            exit;
        }
        $i = 1;     //标识起始数
        do {
            //标识数递增
            $i++;
            //修改接口参数第几页
            $params['page'] = $i;
            //将数组生成 URL-encode 之后的请求字符串
            $paramstring = http_build_query($params);
            //请求接口返回内容
            $content = $this->juhecurl($url,$paramstring);
            //转为数组格式
            $result = json_decode($content,true);
            //判断返回状态码是否为0
            if($result['error_code']=='0'){
                //循环数据并更新数据库
                foreach ($result['result']['data'] as $key=>$value) {
                    $param_stock = array();
                    //封装股票数据表数据
                    $param_stock['symbol'] = $value['code'];        //股票代码
                    $param_stock['name'] = str_replace(' ', '',$value['name']);          //股票名称
                    $param_stock['letter'] = self::pinyin_long($param_stock['name']); //股票名称首字母(大写)
                    $param_stock['trade'] = $value['trade'];        //股票最新价格
                    $param_stock['type'] = 2;                       //股市类型 2:sh
                    $param_stock['update_time'] = date("Y-m-d H:i:s");
                    //更新股票数据表数据
                    $result_stock = $stock->where('symbol',$value['code'])->update($param_stock);
                    //判断是否更新成功
                    if(!$result_stock) {
                        $param_stock['create_time'] = date("Y-m-d H:i:s");
                        //添加股票汇率表数据
                        $stock->insert($param_stock);
                    }
                }
            }
        } while ($i<=$page);

        $this->success('更新sh数据成功', 'stock/stock_data','',1);
    }



    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }


    public function message() {
        /*//封装接口参数
        $params = array(
            "action" => 'send',//发送任务命令
            "userid" => '',//企业id
            "account" => 'wd154',//用户帐号
            "password" => 'wd15433',//发送帐号密码
            "mobile" => '15757878577',//发送帐号密码
            "content" => "【一棵松】您有一笔待行权产品将在3天内到期，请密切关注产品动态以便联系客服行权。客服电话：0571-86498100",//发送内容
            "sendTime" => '',//定时发送时间
            "extno" => ''//扩展子号
        );
        $url = "https://sh2.ipyy.com/smsJson.aspx";     //短信发送接口对应UTF-8(返回值为json格式)
        //将数组生成 URL-encode 之后的请求字符串
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring,0);*/
        $url = "https://sh2.ipyy.com/smsJson.aspx?action=send&userid=&account=wd154&password=wd15433&mobile=15757878577&content=【一棵松】您有一笔待行权产品将在3天内到期，请密切关注产品动态以便联系客服行权。客服电话：0571-86498100&sendTime=&extno=";     //短信发送接口对应UTF-8(返回值为json格式)
        $content = $this->curl_get_https($url);
        //转为数组格式
        $result = json_decode($content,true);
        //echo "<pre>";
        //var_dump($result);exit;
    }


    /**
     * 八点是否发送期权持仓订单到期前第三天短信
     */
    public function order_expire_three()
    {
        //脚本执行时间无限制
        set_time_limit(0);
        //系统配置参数表
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数数据
        $data_system = $system_deploy->field('three_note,mobile,email')->find();
        //判断是否有客服邮箱数据
        if(!empty($data_system['email'])) {
            //获取客服email数组
            $service_email = explode(';',$data_system['email']);
        } else {
            $service_email = array();
        }

        //邮件表 模型
        $email = model('Email');
        //查询邮件表持仓到期前三天提醒数据
        $data_email = $email->get(7);

        //期权持仓订单表
        $order = model('Order');
        //====================发送三天内天短信==================
        //封装搜索条件
        $where['status'] = 1;       //1:未结算 2:已结算
        $where['audit'] = 1;        //1:成功 2:失败 3:待审核
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));    //获取当天零点时间戳
        $endToday=mktime(0,0,0,date('m'),date('d')+3,date('Y'))-1;  //获取后天23点59时间戳
        $where['end_time'] = ['between',[$beginToday,$endToday]];        //三天内到期
        //查询成功申请,状态为结算,结束时间三天内的订单
        $data_order = $order->where($where)->select();

        //后天日期
        $after_time = date('Y-m-d',$endToday);
        //短信发送接口对应UTF-8(返回值为json格式)
        $url = "https://sh2.ipyy.com/smsJson.aspx?action=send&userid=&account=wd154&password=418C485E8641D718C06F8C00832EEB01&sendTime=&extno=&content=【一棵松】您有一笔待行权产品将在3天内到期，请密切关注产品动态以便联系客服行权。客服电话:".$data_system['mobile'];
        //循环处理并发送短信、邮件
        foreach($data_order as $key=>$value) {
            //判断是否是第三天的日期
            switch(date('Y-m-d',$value['end_time'])) {
                case $after_time:
                    //判断是否要发送期权到期短信
                    switch ($data_system['three_note']) {
                        case 1:
                            //发送对象手机号码
                            $send_url = $url."&mobile=".$value['possessor']['mobile'];
                            //对目标发送短信
                            $result = $this->curl_get_https($send_url);
                            //json转为数组
                            $result = json_decode($result,true);
                            $remainpoint = $result['remainpoint'];
                            break;
                    }
                    //=================发送邮件
                    //判断客户或客服是否需要发送邮件
                    if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                        //获取邮件模板内容
                        $email_content = $data_email['content'];
                        //替换股票信息
                        $email_content = str_replace('{$title}',$value['title'].'('.$value['code'].')',$email_content);
                        //替换到期时间
                        $email_content = str_replace('{$time}',date('Y年m月d日',$value['end_time']),$email_content);
                        //替换用户手机号
                        $email_content = str_replace('{$mobile}',$data_system['mobile'],$email_content);
                        //替换当前时间
                        $email_content = str_replace('{$date}',date('Y-m-d H:i:s'),$email_content);
                    }
                    //判断是否需要发送给客户
                    switch ($data_email['client_status']) {
                        case 1:
                            //给客户发送smtp163邮件
                            send_email($data_email['title'],$email_content,$value['possessor']['client']['email']);
                            break;
                    }
                    //判断是否需要发送给客服
                    switch ($data_email['service_status']) {
                        case 1:
                            //给每个客服发送邮件
                            foreach($service_email as $key=>$value) {
                                send_email($data_email['title'],$email_content,$value);
                            }
                            break;
                    }
                    //暂停 60 秒
                    sleep(60);
                    break;
            }
        }
        if(!empty($remainpoint)) {
            //更新短信剩余条数
            $system_deploy->save(['note_number'=>$remainpoint],['deploy_id'=>1]);
        }

    }



    /**
     * 八点是否发送期权持仓订单到期当天短信
     */
    public function order_expire_one()
    {
        //脚本执行时间无限制
        set_time_limit(0);
        //系统配置参数表
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数数据
        $data_system = $system_deploy->field('one_note,mobile,email')->find();
        //判断是否有客服邮箱数据
        if(!empty($data_system['email'])) {
            //获取客服email数组
            $service_email = explode(';',$data_system['email']);
        } else {
            $service_email = array();
        }

        //邮件表 模型
        $email = model('Email');
        //查询邮件表持仓到期当天提醒数据
        $data_email = $email->get(8);

        //期权持仓订单表
        $order = model('Order');
        //====================发送当天短信=========================
        //封装搜索条件
        $where['status'] = 1;       //1:未结算 2:已结算
        $where['audit'] = 1;        //1:成功 2:失败 3:待审核
        //php获取今日开始时间戳和结束时间戳
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $where['end_time'] = ['between',[$beginToday,$endToday]];        //当天到期
        //查询成功申请,状态为结算,结束时间当天内的订单
        $data_order = $order->where($where)->select();

        //短信发送接口对应UTF-8(返回值为json格式)
        $url = "https://sh2.ipyy.com/smsJson.aspx?action=send&userid=&account=wd154&password=418C485E8641D718C06F8C00832EEB01&sendTime=&extno=&content=【一棵松】您有一笔待行权产品将于今天到期，请密切关注产品动态以便联系客服行权。若您未主动行权，将于今天强制交割。客服电话:".$data_system['mobile'];

        //循环处理并发送短信
        foreach($data_order as $key=>$value) {
            //判断是否要发送期权到期短信
            switch ($data_system['one_note']) {
                case 1:
                    //发送对象手机号码
                    $send_url = $url."&mobile=".$value['possessor']['mobile'];
                    //对目标发送短信
                    $result = $this->curl_get_https($send_url);
                    //json转为数组
                    $result = json_decode($result,true);
                    $remainpoint = $result['remainpoint'];
                    break;
            }
            //=================发送邮件
            //判断客户或客服是否需要发送邮件
            if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                //获取邮件模板内容
                $email_content = $data_email['content'];
                //替换股票信息
                $email_content = str_replace('{$title}',$value['title'].'('.$value['code'].')',$email_content);
                //替换到期时间
                $email_content = str_replace('{$time}',date('Y年m月d日',$value['end_time']),$email_content);
                //替换用户手机号
                $email_content = str_replace('{$mobile}',$data_system['mobile'],$email_content);
                //替换当前时间
                $email_content = str_replace('{$date}',date('Y-m-d H:i:s'),$email_content);
            }
            //判断是否需要发送给客户
            switch ($data_email['client_status']) {
                case 1:
                    //给客户发送smtp163邮件
                    send_email($data_email['title'],$email_content,$value['possessor']['client']['email']);
                    break;
            }
            //判断是否需要发送给客服
            switch ($data_email['service_status']) {
                case 1:
                    //给每个客服发送邮件
                    foreach($service_email as $key=>$value) {
                        send_email($data_email['title'],$email_content,$value);
                    }
                    break;
            }
            //暂停 60 秒
            sleep(60);

        }

        if(!empty($remainpoint)) {
            //更新短信剩余条数
            $system_deploy->save(['note_number'=>$remainpoint],['deploy_id'=>1]);
        }

    }



    /**
     * 八点是否发送创收是否大于权利金短信
     */
    public function order_income_note()
    {
        //系统配置参数表
        $system_deploy = model('SystemDeploy');
        //查询系统配置参数数据
        $data_system = $system_deploy->field('income_note,mobile,email')->find();
        //判断是否有客服邮箱数据
        if(!empty($data_system['email'])) {
            //获取客服email数组
            $service_email = explode(';',$data_system['email']);
        } else {
            $service_email = array();
        }
        //邮件表 模型
        $email = model('Email');
        //查询邮件表创收大于权利金数据
        $data_email = $email->get(9);

        //期权持仓订单表
        $order = model('Order');
        //====================发送当天短信=========================
        //封装搜索条件
        $where['status'] = 1;       //1:未结算 2:已结算
        $where['audit'] = 1;        //1:成功 2:失败 3:待审核
        //查询成功申请,状态为结算,结束时间当天内的订单
        $data_order = $order->where($where)->select();

        //短信发送接口对应UTF-8(返回值为json格式)
        $url = "https://sh2.ipyy.com/smsJson.aspx?action=send&userid=&account=wd154&password=418C485E8641D718C06F8C00832EEB01&sendTime=&extno=&content=【一棵松】恭喜！您有一笔期权产品走向偏好，请保持关注。客服电话:".$data_system['mobile'];
        //循环处理并发送短信
        foreach($data_order as $key=>$value) {
            //计算创收 (当前市场价-期初价格)*(名义本金/期初价格)
            $income = ($value['stock']['trade'] - $value['initial_price'])*($value['principal']/$value['initial_price']);
            //判断创收是否大于权利金
            if($income > $value['royalty']) {
                //判断此持有人今天是否已收到短信
                if($value['possessor']['income_date'] != date('Ymd')) {
                    //判断是否要发送期权到期短信
                    switch($data_system['income_note']) {
                        case 1:
                            //发送对象手机号码
                            $send_url = $url."&mobile=".$value['possessor']['mobile'];
                            //对目标发送短信
                            $result = $this->curl_get_https($send_url);
                            //json转为数组
                            $result = json_decode($result,true);
                            $remainpoint = $result['remainpoint'];
                            //期权持仓订单表
                            $user = model('User');
                            //修改用户创收大于权利金短信日期
                            $user->save(['income_date'=>date('Ymd')],['uid'=>$value['possessor_uid']]);
                            break;
                    }

                    //=================发送邮件
                    //判断客户或客服是否需要发送邮件
                    if($data_email['client_status'] == 1 || $data_email['service_status'] == 1) {
                        //获取邮件模板内容
                        $email_content = $data_email['content'];
                        //替换股票信息
                        $email_content = str_replace('{$title}',$value['title'].'('.$value['code'].')',$email_content);
                        //替换用户手机号
                        $email_content = str_replace('{$mobile}',$data_system['mobile'],$email_content);

                        //判断是否需要发送给客户
                        switch ($data_email['client_status']) {
                            case 1:
                                //给客户发送smtp163邮件
                                send_email($data_email['title'],$email_content,$value['possessor']['client']['email']);
                                break;
                        }
                        //判断是否需要发送给客服
                        switch ($data_email['service_status']) {
                            case 1:
                                //给每个客服发送邮件
                                foreach($service_email as $key=>$value) {
                                    send_email($data_email['title'],$email_content,$value);
                                }
                                break;
                        }
                    }
                }

            }
        }
        if(!empty($remainpoint)) {
            //更新短信剩余条数
            $system_deploy->save(['note_number'=>$remainpoint],['deploy_id'=>1]);
        }

    }



    /**
     * get方式发送curl
     * @param  string $url [请求的URL地址]
     * @return array json
     */
    function curl_get_https($url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }


}