<?php
namespace app\useradmin\controller;

use think\Controller;
/**
 * 资讯控制器
 */
class  NewsController extends Controller{
    public function index(){
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
                $this->success('操作成功', 'news/index','',1);
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