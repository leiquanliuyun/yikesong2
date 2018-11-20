<?php
namespace app\useradmin\controller;
use think\Paginator;
/**
 * 内容管理控制器
 * 如幻灯片、需求管理等
 */
class  ContentController extends CommonController
{
    /**
     * 幻灯片管理页
     */
    public function slide()
    {
        //用户表 模型
        $slide = model('Slide');
        //查询幻灯片数据
        $list = $slide->order('sort desc')->select();

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }


    /**
     * 幻灯片添加、修改操作
     */
    public function slide_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //幻灯片表 验证器
            $validate = validate('Slide');
            //表单提交数据
            $param = input('post.');
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //幻灯片表 模型
            $slide = model('Slide');//halt($param);
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $slide->allowField(true)->save($param);
                //获取表自增id
                $id = $slide->slide_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $slide->allowField(true)->save($param,['slide_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'content/slide','',1);
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
            //幻灯片表 模型
            $slide = model('Slide');
            //查询幻灯片信息
            $list = $slide->get($id);
        } else {
            $list['status'] = '';
            $list['type'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 幻灯片 删除
     */
    public function slide_delete()
    {
        //获取id数据
        $id = input('post.id');
        //幻灯片表 模型
        $slide = model('Slide');
        //删除数据
        $list = $slide::get($id);
        $result = $list->delete();

        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 幻灯片 批量删除
     */
    public function slide_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //幻灯片表 模型
        $slide = model('Slide');
        //删除数据
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $slide::get($value);
            $result = $list->delete();
        }
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }



    /**
     * 幻灯片 发布/下架
     */
    public function slide_show()
    {
        //获取id数据
        $id = input('post.id');
        //获取幻灯片状态值
        $show = input('post.show');
        //幻灯片表 模型
        $slide = model('Slide');
        //更新数据
        $result = $slide->save([
            'status'  => $show
        ],['slide_id' => $id]);
        $info=$slide->where('slide_id',$id)->field('type')->find();//halt($info);
        //判断是否修改成功
        if($result) {
            $data['msg'] = 'success';
            $data['type']=$info['type'];
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 幻灯片 排序
     */
    public function slide_sort()
    {
        //获取id和排序数组数据
        $param = input('post.');
        //幻灯片表 模型
        $slide = model('Slide');
        //更新数据
        if(!empty($param)) {
            foreach($param['sort'] as $key=>$value) {
                $slide->save([
                    'sort'  => $value
                ],['slide_id' => $param['id_array'][$key]]);
            }
        } else {
            $this->error("没有可排序的数据");
        }
        $this->success("排序成功！",'content/slide','',1);
    }
    /*需求管理*/
    public function demand(){
        $demand=model('Demand');
        $list = $demand->order('create_time desc')->select();//echo $demand->getLastSql();exit;
        //$list=$demand->all([],'member');
        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 需求添加、修改操作，暂不用
     */
    public function demand_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //需求表 验证器
            $validate = validate('Demand');
            //表单提交数据
            $param = input('post.');//var_dump($param);echo '<hr>';
            //处理需求内容数据
            if(!empty($param['editorValue'])) {
                $param['content']=htmlspecialchars_decode($param['editorValue']);
                //$param['content']=$param['editorValue'];
            }//halt($param);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //需求表 模型
            $demand = model('Demand');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $demand->allowField(true)->save($param);
                //获取表自增id
                $id = $demand->demand_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $demand->allowField(true)->save($param,['demand_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'demand/index','',1);
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
            //需求表 模型
            $slide = model('Demand');
            //查询需求信息
            $list = $slide->get($id);
        } else {
            $list['status'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 需求 删除
     */
    public function demand_delete()
    {
        //获取id数据
        $id = input('post.id');
        //需求表 模型
        $demand = model('Demand');
        //删除数据
        $list = $demand::get($id);
        $result = $list->delete();

        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 需求 批量删除
     */
    public function demand_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //需求表 模型
        $demand = model('Demand');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $demand::get($value);
            $result = $list->delete();
        }
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }

    /**
     * 优惠券管理页
     */
    public function coupon()
    {

        //用户表 模型
        $coupon = model('Coupon');
        //查询优惠券数据
        $list = $coupon->order('create_time desc')->select();

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }


    /**
     * 优惠券添加、修改操作
     */
    public function coupon_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //优惠券表 验证器
            $validate = validate('Coupon');
            //表单提交数据
            $param = input('post.');//halt($param);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //优惠券表 模型
            $coupon = model('Coupon');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $coupon->allowField(true)->save($param);
                //获取表自增id
                $id = $coupon->coupon_id;
                if ($param['is_assign']==1){//指定则相当于自动领取
                    $member_coupon=model('MemberCoupon');
                    //$end_time = strtotime("+".$param['useday']."days");
                    //$end_time=get_time($end_time,0);
                    $assign=$param['assign_obj'];
                    $param['assign_obj']=json_encode($param['assign_obj']);
                    $list=[];
                    foreach ($assign as $k=>$v){
                        $list[$k]['coupon_id']=$id;
                        $list[$k]['uid']=$v;
                        $list[$k]['end_time']=$param['end_time'];
                        $list[$k]['start_time']=$param['start_time'];
                    }
                    $member_coupon->saveAll($list);
                }
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $coupon->allowField(true)->save($param,['coupon_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'content/coupon','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //获取页面标签数据
        $option = input('param.option');
        $option = empty($option) ? 'add':$option;
        //获取表id数据
        $id =  input('param.id');
        $member_list=db('member')->field('id,nickname,realname')->select();
        $list = array();
        if($option == 'update') {
            //优惠券表 模型
            $coupon = model('Coupon');
            //查询优惠券信息
            $list = $coupon->get($id);
            $list['assign_obj']=json_decode($list['assign_obj'],true);
        } else {
            $list['status_text'] = '';
            $list['service_name'] = '';
            $list['is_assign']=0;
            $list['assign_obj']=[];
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        $this->assign('member_list', $member_list);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 优惠券 删除
     */
    public function coupon_delete()
    {
        //获取id数据
        $id = input('post.id');
        //优惠券表 模型
        $coupon = model('Coupon');
        //删除数据
        $list = $coupon::get($id);
        $result = $list->delete();

        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 优惠券 批量删除
     */
    public function coupon_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //优惠券表 模型
        $coupon = model('Coupon');
        //删除数据
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $coupon::get($value);
            $result = $list->delete();
        }
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }



    /**
     * 优惠券 发布/下架
     */
    public function coupon_show()
    {
        //获取id数据
        $id = input('post.id');
        //获取优惠券状态值
        $show = input('post.show');
        //优惠券表 模型
        $coupon = model('Coupon');
        //更新数据
        $result = $coupon->save([
            'status'  => $show
        ],['coupon_id' => $id]);
        //判断是否修改成功
        if($result) {
            $data['msg'] = 'success';

        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }

    /*公告管理*/
    public function notice(){
        $notice=model('Notice');
        $list = $notice->order('create_time desc')->select();//echo $notice->getLastSql();exit;
        //$list=$notice->all([],'member');
        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 公告添加、修改操作，暂不用
     */
    public function notice_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //公告表 验证器
            $validate = validate('Notice');
            //表单提交数据
            $param = input('post.');//var_dump($param);echo '<hr>';
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //公告表 模型
            $notice = model('Notice');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $notice->allowField(true)->save($param);
                //获取表自增id
                $id = $notice->notice_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $notice->allowField(true)->save($param,['notice_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'content/notice','',1);
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
            //公告表 模型
            $notice = model('Notice');
            //查询公告信息
            $list = $notice->get($id);
        } else {
            $list['status'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 公告 删除
     */
    public function notice_delete()
    {
        //获取id数据
        $id = input('post.id');
        //公告表 模型
        $notice = model('Notice');
        //删除数据
        $list = $notice::get($id);
        $result = $list->delete();

        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }
    public function news(){
        $news=model('News');
        $list = $news->select();

        //渲染数据
        $this->assign('list', $list);

        //模板渲染
        return $this->fetch();
    }
    /**
     * 资讯添加、修改操作
     */
    public function news_edit()
    {
        //判断是否是post提交方式
        if(request()->isPost()) {
            //资讯表 验证器
            $validate = validate('News');
            //表单提交数据
            $param = input('post.');//var_dump($param);echo '<hr>';
            //处理资讯内容数据
            if(!empty($param['editorValue'])) {
                $param['content']=htmlspecialchars_decode($param['editorValue']);
                //$param['content']=$param['editorValue'];
            }//halt($param);
            //验证 表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //获取数据标签和表id
            $option = $param['option'];
            $id = $param['id'];
            //资讯表 模型
            $news = model('News');
            if($option == 'add'){
                //添加,过滤非数据表字段的数据
                //halt($param);
                $result = $news->allowField(true)->save($param);
                //获取表自增id
                $id = $news->news_id;
            }elseif($option == 'update'){
                //更新,过滤非数据表字段的数据
                $result = $news->allowField(true)->save($param,['news_id'=>$id]);
            }
            //判断是否操作成功并跳转
            if($result) {
                //添加用户操作记录
                $this->success('操作成功', 'content/news','',1);
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
            //资讯表 模型
            $slide = model('News');
            //查询资讯信息
            $list = $slide->get($id);
        } else {
            $list['status'] = '';
        }

        //渲染数据
        $this->assign('option', $option);
        $this->assign('id', $id);
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
    }

    /**
     * 资讯 删除
     */
    public function news_delete()
    {
        //获取id数据
        $id = input('post.id');
        //资讯表 模型
        $news = model('News');
        //删除数据
        $list = $news::get($id);
        $result = $list->delete();

        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }


    /**
     * 资讯 批量删除
     */
    public function news_batch_delete()
    {
        //获取id数组数据
        $id_array = input('post.');
        //资讯表 模型
        $news = model('News');
        //删除数
        foreach($id_array['id_array'] as $key=>$value) {
            $list = $news::get($value);
            $result = $list->delete();
        }
        //判断是否删除成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }



    /**
     * 资讯 发布/草稿
     */
    public function news_show()
    {
        //获取id数据
        $id = input('post.id');
        //获取资讯状态值
        $show = input('post.show');
        //资讯表 模型
        $news = model('News');
        //更新数据
        $result = $news->save([
            'status'  => $show
        ],['news_id' => $id]);
        //判断是否修改成功
        if($result) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'error';
        }
        //返回json数据
        return json($data);
    }

    /**
     * 资讯 排序
     */
    public function news_sort()
    {
        //获取id和排序数组数据
        $param = input('post.');//halt($param);
        //资讯表 模型
        $news = model('News');
        //更新数据
        if(!empty($param)) {
            foreach($param['sort'] as $key=>$value) {
                $news->save([
                    'sort'  => $value
                ],['news_id' => $param['id_array'][$key]]);
            }
        } else {
            $this->error("没有可排序的数据");
        }
        $this->success("排序成功！",'news/index','',1);
    }
}