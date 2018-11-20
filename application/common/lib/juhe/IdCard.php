<?php
namespace app\common\lib\juhe;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/8
 * Time: 19:46
 * 身份证实名认证类
 */
class IdCard{

    /**
     * 身份证号码和姓名的匹配校验
     * @param string $name 真实姓名
     * @param string $id_card 身份证号码
     * @return bool; 验证通过并且匹配成功，返回true
    */
    public static function idCardVerify( $name , $id_card ){
        $url = 'http://op.juhe.cn/idcard/query?';

        if( empty($name) || empty($id_card) ){
            return false;
        }
        $key = md5($name.$id_card);
        $remote = cache($key);
        if( empty($remote) ){
            // 不存在缓存数据将请求接口
            $param = [
                'idcard' => $id_card,
                'realname' => $name,
                'key' => config('juhe.idcard_appkey')
            ];
            $remote = curl_request( $url.http_build_query( $param ) );
            if( !empty($remote) ){
                cache( $key , $remote  ,60*5 );
            }
        }
        $data = json_decode( $remote , true );
        if( isset( $data['error_code'] ) && $data['error_code'] == 0 && $data['result']['res'] == 1 ){
            return true;
        }
        return false;
    }
    //商标查询
    public function trademark_inquiry(){
        $uid = $this->user_id;
        $trademark = data_isset($this->get_post('trademark'),'trim','');//查询的商标
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $url = "http://japi.juhe.cn/trademark/search";
        $params = array(
            "key" => config('juhe.trademark_appkey'),//您申请的appKey
            "keyword" => $trademark,//待搜索的关键词
            "pageSize" => $pagesize,//    页面大小，即一次api调用最大获取多少条记录，取值范围：[1-50]
            "pageNo" => $page,//当前页码数，即本次api调用是获得结果的第几页，从1开始计数
            "searchType" => 4,//默认=4,按什么来查，1: 商标名， 2：注册号， 3：申请人 4：商标名/注册号/申请人只要模糊匹配
            "intCls   " => 0,//默认 =0,0：全部国际分类,非0：限定在指定类别，类别间用分号分割。如：4;12;34  表示在第4、12、34类内查询
        );
        $paramstring = http_build_query($params);
        $content = juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                $data=$result['result'];
            }else{
                $data= $result['error_code'].":".$result['reason'];
                parent::put_post(['status' => 1004, 'info' => $data]);
            }
        }else{
            parent::put_post(['status' => 1003, 'info' => '请求失败']);
        }
        parent::put_post(['status' => 1000, 'info' => 'OK','data'=>$data]);
    }
}