<?php
namespace app\useradmin\controller;

/*
 *系统配置参数表 控制器
 */
class  SystemController extends CommonController{
	
	//系统参数设置显示页面
	public function parameter(){
        //判断是否是post提交方式
        if(request()->isPost()) {
            //系统配置参数表 验证器
            $validate = validate('SystemDeploy');
            //表单提交数据
            $param = input('post.');
            //处理邮件内容数据
            if(!empty($param['editorValue'])) {
                $param['email_content']=htmlspecialchars_decode($param['editorValue']);
            }
            //验证表单数据
            if(!$validate->check($param)){
                $this->error($validate->getError());
            }
            //系统配置参数表 模型
            $system_deploy = model('SystemDeploy');
            //更新,过滤非数据表字段的数据
            $result = $system_deploy->allowField(true)->save($param,['deploy_id'=>1]);
            //判断是否操作成功并跳转
            if($result || $result ==0) {
                $this->success('操作成功', 'system/parameter','',1);
            } else {
                $this->error('操作失败！');
            }
        }
        //系统配置参数表 模型
        $system_deploy = model('SystemDeploy');
        //查询艺术品数据
        $list = $system_deploy->get(1);
        //渲染数据
        $this->assign('list', $list);
        //模板渲染
        return $this->fetch();
	}



    /**
     * 清除缓存
     */
    public function clear_cache(){
        //系统缓存地址
        $R = RUNTIME_PATH;
        //调用清除缓存方法
        if($this->_deleteDir($R)) {
            //渲染数据
            $this->assign('data', '清除缓存成功!');
            //模板渲染
            return $this->fetch();
        } else {
            //渲染数据
            $this->assign('data', '清除缓存失败!');
            //模板渲染
            return $this->fetch();
        }
    }



    /**
     * 清除缓存方法
     */
    private function _deleteDir($R){
        $handle = opendir($R);
        while(($item = readdir($handle)) !== false){
            if($item != '.' and $item != '..'){
                if(is_dir($R.'/'.$item)){
                    $this->_deleteDir($R.'/'.$item);
                }else{
                    if(!unlink($R.'/'.$item)) {
                        //渲染数据
                        $this->assign('data', '清除缓存失败!');
                        //模板渲染
                        return $this->fetch();
                    }
                }
            }
        }
        closedir( $handle );
        return rmdir($R);
    }

}