<?php
namespace app\index\controller;

/**
 * 投教内容 控制器
 */
class ContentController extends CommonController {

    /**
     * 投教内容分类列表
     */
    public function investment_education() {
        //资讯分类表 模型
        $consult_classify = model('ConsultClassify');
        //查询资讯分类数据
        $list_classify = $consult_classify->order('create_time desc')->select();

        //视频分类表 模型
        $video_classify = model('VideoClassify');
        //查询视频分类数据
        $video_classify = $video_classify->order('create_time desc')->select();

        //渲染数据
        $this->assign('list_classify', $list_classify);
        $this->assign('video_classify', $video_classify);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 视频列表页面
     */
    public function teaching() {
        //获取表id数据
        $param =  input('param.');
        //验证数据
        if(empty($param['classify_id'])) {
            echo  '非法错误,分类id不存在!';exit;
        }
        //分类id
        $where['classify_id'] = ['=',$param['classify_id']];
        //视频分类列表 模型
        $video_list = model('VideoList');
        //查询视频分类列表数据
        $list_video = $video_list->where($where)->select();
        //视频表 模型
        $video = model('Video');
        //循环封装视频数据
        foreach($list_video as $key=>$value) {
            //查询视频数据
            $list_video[$key]['video'] = $video->where(['show'=>1,'list_id'=>$value['list_id']])->order('recommend asc')->select();
        }

        //渲染数据
        $this->assign('list_video', $list_video);
        //模板渲染
        return $this->fetch();
    }



    /**
     * 投教内容列表
     */
    public function basics() {
        //获取表id数据
        $param =  input('param.');
        //验证数据
        if(empty($param['classify_id'])) {
            echo  '非法错误,分类id不存在!';exit;
        }
        //分类id
        $where['classify_id'] = ['=',$param['classify_id']];
        $where['show'] = ['=',1];
        //资讯表 模型
        $consult = model('Consult');
        //查询资讯数据
        $list_consult = $consult->where($where)->field('title,consult_id')->order('recommend asc')->select();

        //渲染数据
        $this->assign('list_consult', $list_consult);
        //模板渲染
        return $this->fetch();
    }


    /**
     * 投教内容详情
     */
    public function detail() {
        //获取表id数据
        $param =  input('param.');
        //验证数据
        if(empty($param['consult_id'])) {
            echo  '非法错误,资讯表id不存在!';exit;
        }
        //分类id
        $where['consult_id'] = ['=',$param['consult_id']];
        $where['show'] = ['=',1];
        //资讯表 模型
        $consult = model('Consult');
        //查询资讯数据
        $list_consult = $consult->where($where)->find();
        //判断是否查询到资讯数据
        if(empty($list_consult)) {
            echo  '非法错误,投教详情内容不存在!';exit;
        } else {
            //增加点击次数
            $consult->where('consult_id', $param['consult_id'])->setInc('hits');
        }

        //渲染数据
        $this->assign('list_consult', $list_consult);
        //模板渲染
        return $this->fetch();
    }



}
