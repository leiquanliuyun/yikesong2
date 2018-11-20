<?php
namespace app\useradmin\controller;

/**
 * 上传控制器
 * 用来上传附件、图片等
 */
class  UploadController extends CommonController
{

    /*
     * 图片上传接口
     * */
    public function upload_image(){
        // 获取上传图片
        $files = request()->file();//halt($files);
        foreach($files as $file){
            // 验证图片并以date规则命名，移动到框架应用根目录/public/uploads/images 目录下
            $info = $file->rule('date')->validate(['ext'=>'gif,jpg,jpeg,bmp,png'])->move(ROOT_PATH . 'public' . DS . 'uploads/images');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }


    /*
     * 多图上传接口
     * */
    public function upload_multi_image(){
        //封装数组
        $response = array();
        // 获取上传图片
        $files = request()->file();
        foreach($files as $file){
            // 验证图片并以date规则命名，移动到框架应用根目录/public/uploads/images 目录下
            $info = $file->rule('date')->validate(['ext'=>'gif,jpg,jpeg,bmp,png'])->move(ROOT_PATH . 'public' . DS . 'uploads/images');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $response[] = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

        //返回数据
        echo implode('##',$response);
    }

    /*
     * 附件上传接口
     * */
    public function upload_attachment(){

        if (!empty($_FILES)) {
            $config = array(
                'maxSize'    =>    100000000,
                'rootPath'	 =>    './',
                'savePath'   =>    '/upload/attachment/',
                'saveName'   =>    array('uniqid',''),
//                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,//自动使用子目录保存上传文件 默认为true
                'subName'    =>    array('date','Ym'),
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $attachment = $upload->upload();
            if($attachment){
                $data['file_name'] = $attachment['Filedata']['name'];
                $data['file_size'] = $attachment['Filedata']['size'];
                $data['file'] = $attachment['Filedata']['savepath'].$attachment['Filedata']['savename'];
                //返回文件地址和名给JS作回调用
                $this->ajaxReturn($data);
            }else{
//                $this->ajaxReturn($upload->getError(),'JSON');
            }
        }
    }


}