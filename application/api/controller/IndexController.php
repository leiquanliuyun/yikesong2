<?php
namespace app\api\controller;
use app\api\model\Notice;
use app\api\model\Slide;
use app\api\model\Collect;
use app\api\model\Csituation;
use app\api\model\Filter;
use app\api\model\Housecontent;
use app\api\model\Companycontent;
use app\api\model\News;
use app\api\model\Servicecontent;
use think\captcha\Captcha;      //验证码
use app\api\model\Member;
use app\common\lib\push\Getui;
use think\Cache;
use think\Exception;
use think\Loader;
use think\Log;
/**
 * 首页控制器
 */
class  IndexController extends CommonController
{
    /*首页*/
    public function  index(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        $slide=new Slide();
        $info=$slide->where(['status'=>1,'type'=>1])->field('slide_id,title,picture,link')
            ->order('sort')->select();
        $news=new News();
        $news_info=$news->where('status',1)->order('sort')
            ->field('news_id,title,picture')->page($page,$pagesize)->select();
        $notice=new Notice();
        $notice_info=$notice->order('create_time desc')->field('notice_id,content')->find();
        $data=['slide_info'=>$info,'news_info'=>$news_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //首页服务选项详情页,如果登录则需要判断该服务是否已收藏
    public function service_detail(){
        $service_name = data_isset($this->get_post('service_name'),'trim','');
        if (empty($service_name)){
            parent::put_post(array('status'=>1003,'info'=>'缺少必要参数'));
        }
        //获取该服务下的服务选项
        $csituation=new Csituation();
        $csInfo=$csituation->where('service_name',$service_name)->field('cs_id,name,pid')->select()->toArray();
        $parentCs = array();//halt($csInfo);
        $parentCs=list_to_tree( $csInfo ,'cs_id', 'pid',  '_child',  0 );

        //获取服务内容信息
        $servicecontent=new Servicecontent();
        $service_info=$servicecontent->where('service',$service_name)->field('content_id,service,picture,phone,price,introduce_mess,trade_type')->find();
        if (!$service_info){
            parent::put_post(array('status'=>1004,'info'=>'服务不存在'));
        }
        //判断是否收藏
        $uid=$this->get_userinfo();
        $is_collect=0;
        if ($uid){
            $collect=new Collect();
            $collect_info=$collect->where(['mod'=>'Servicecontent','uid'=>$uid,'service_id'=>$service_info['content_id']])->find();
            if ($collect_info){
                $is_collect=1;
            }
        }
        $data=['csituation_info'=>$parentCs,'service_info'=>$service_info,'is_collect'=>$is_collect];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //财务记账
    public function finance(){
        $uid=parent::check_token();
        //用户表 模型
        $member = model('Member');
        $member_info=$member->where('id',$uid)->field('finance_detail')->find();
        //财务相关信息
        if ($member_info['finance_detail']){
            $finance_info=json_decode($member_info['finance_detail'],true);
            foreach ($finance_info as $k=>$v){
                $finance_info[$k]=(int)$v;
            }
        }else{
            /* $finance_info=[u
                 ['name'=>'自动开票功能','is_open'=>1,'is_consign'=>1],
                 ['name'=>'社保','is_open'=>1,'is_consign'=>1],
                 ['name'=>'公积金','is_open'=>1,'is_consign'=>1],
                 ['name'=>'银行回单','is_open'=>1,'is_consign'=>1]
             ];*/
            $finance_info=['is_open_billing'=>3,'is_consign_billing'=>3,'is_open_shebao'=>3,'is_consign_shebao'=>3,
                'is_open_pfund'=>3,'is_consign_pfund'=>3,'is_open_receipt'=>3,'is_consign_receipt'=>3];
        }
        $data=['finance_info'=>$finance_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //租房找房展示列表显示
    public function house_list(){
        //$housetype = data_isset($this->get_post('housetype'),'trim','');//房屋类型如办公楼、住宅楼
        $sort_price = data_isset($this->get_post('sort_price'),'intval',1);//价格排序1升序2降序
        $filter_id = data_isset($this->get_post('filter_id'),'intval',0);//筛选条件id
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);
        //获取房屋列表数据
        $map=[];
        /*if ($housetype){
            $map['housetype']=$housetype;
        }*/
        if ($filter_id){
            $map['filter_id']=['like',"%{$filter_id}%"];
        }
        $order='';
        if ($sort_price==1){
            $order.='price asc';

        }else{
            $order.='price desc';
            //$order.="'price' 'desc'";//这样不行
        }
        $housecontent=new Housecontent();
        $house_list=$housecontent->where($map)->order($order)->field('content_id,picture,location,filter_id,name,type,size,orientation,fixture,price,location')->page($page,$pagesize)->select();
        $house_info=[];
        if ($house_list){
            $i = 0;
            foreach ($house_list as $key => $value) {
                $tag=json_decode($value['filter_id'],true);
                if(in_array($filter_id, $tag) || $filter_id==0){
                    $house_list[$key]['true_picture']=$value['picture_show'][0];
                    $house_list[$key]['filter_list']=db('filter')->where('id','in',$tag)
                        ->limit(3)->column('condition');
                    $house_info[$i] = $house_list[$key];
                    $i++;
                }
            }
        }
        //echo $housecontent->getLastSql();exit;
        //获取筛选条件
        $filter=new Filter();
        $filter_info=$filter->where('service_name','园区服务')->field('id as filter_id,condition')->select();

        $data=['filter_info'=>$filter_info,'house_info'=>$house_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //租房找房详情页
    public function house_detail(){
        $content_id = data_isset($this->get_post('content_id'),'intval','');//
        if (!is_numeric($content_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }

        $housecontent=new Housecontent();
        $house_info=$housecontent->where('content_id',$content_id)
            ->field('content_id,picture,name,type,size,orientation,fixture,price,location,rental_method,floor,introduce_mess,trade_type')->find();
        if (!$house_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的信息已不存在！']);
        }
        //判断是否收藏
        $uid=$this->get_userinfo(); //echo $uid; halt($house_info);
        $is_collect=0;
        if ($uid){
            $collect=new Collect();
            $collect_info=$collect->where(['mod'=>'Housecontent','uid'=>$uid,'service_id'=>$house_info['content_id']])->find();
            if ($collect_info){
                $is_collect=1;
            }
        }
        $data=['service_info'=>$house_info,'is_collect'=>$is_collect];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //资质转让展示列表显示
    public function company_list(){
        $sort_price = data_isset($this->get_post('sort_price'),'intval',1);//价格排序1升序2降序
        $filter_id = data_isset($this->get_post('filter_id'),'intval',0);//筛选条件id
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);

        //获取房屋列表数据
        $map=[];
        if ($filter_id){
            $map['filter_id']=['like',"%{$filter_id}%"];
        }
        $order='';
        if ($sort_price==1){
            $order.='price asc';
        }else{
            $order.='price desc';
        }
        $companycontent=new Companycontent();
        $company_list=$companycontent->where($map)->field('content_id,picture,location,filter_id,name,found_time,price')->order($order)->page($page,$pagesize)->select();
        //echo $companycontent->getLastSql();
        //halt($company_list);
        $company_info=[];
        if ($company_list){
            $i = 0;
            foreach ($company_list as $key => $value) {
                $tag=json_decode($value['filter_id'],true);
                if(in_array($filter_id, $tag) || $filter_id==0){
                    $company_list[$key]['true_picture']=$value['picture_show'][0];
                    $company_list[$key]['filter_list']=db('filter')->where('id','in',$tag)
                        ->limit(3)->column('condition');
                    $company_info[$i] = $company_list[$key];
                    $i++;
                }
            }
        }
        //获取筛选条件
        $filter=new Filter();
        $filter_info=$filter->where('service_name','资质转让')->field('id as filter_id,condition')->select();

        $data=['filter_info'=>$filter_info,'company_info'=>$company_info];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //资质转让详情页
    public function company_detail(){
        $content_id = data_isset($this->get_post('content_id'),'intval','');//筛选条件id
        if (!is_numeric($content_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }

        $companycontent=new Companycontent();
        $company_info=$companycontent->where('content_id',$content_id)
            ->field('content_id,picture,name,found_time,aptitude,tally,solid_assets,regmoney,remark,price,introduce_mess,trade_type')->find();
        if (!$company_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的信息已不存在！']);
        }
        //判断是否收藏
        $uid=$this->get_userinfo();
        $is_collect=0;
        if ($uid){
            $collect=new Collect();
            $collect_info=$collect->where(['mod'=>'Companycontent','uid'=>$uid,'service_id'=>$company_info['content_id']])->find();
            if ($collect_info){
                $is_collect=1;
            }
        }
        $data=['company_info'=>$company_info,'is_collect'=>$is_collect];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$data]);
    }
    //资讯列表显示
    public function news_list(){
        $page = data_isset($this->get_post('page'),'intval',1);
        $pagesize = data_isset($this->get_post('pagesize'),'intval',10);

        //获取资讯列表数据
        $news=new News();
        $news_info=$news->where('status',1)->order('create_time','desc')
            ->field('news_id,title,picture')->page($page,$pagesize)->select();
        $news_info=$news_info?$news_info:[];
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$news_info]);
    }
    //资讯详情
    public function news_detail(){
        $news_id = data_isset($this->get_post('news_id'),'intval','');
        if (!is_numeric($news_id)) {
            parent::put_post(['status' => 1003, 'info' => '参数有误！']);
        }
        $news=new News();
        $news_info=$news->where('news_id',$news_id)->field('news_id,title,picture,content')->find();
        if (!$news_info){
            parent::put_post(['status' => 1004, 'info' => '您查看的信息已不存在！']);
        }
        $news->where('news_id',$news_id)->setInc('click');
        /*$news_info['next']=$news->where('news_id','>',$news_id)
            ->order('create_time','desc')->field("news_id,title")->find();//下一篇
        $news_info['prev']=$news->where('news_id','<',$news_id)
            ->order('create_time','desc')->field("news_id,title")->find();*/
        parent::put_post(['status' => 1000, 'info' => 'OK', 'data' =>$news_info]);
    }

}
