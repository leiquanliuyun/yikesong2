<?php
namespace app\useradmin\model;

use think\Model;

/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/4/26
 * Time: 9:53
 */
class Common extends Model{



    private $info  =  '未知错误！';


    protected function setError($info){
        $this->info = $info;
    }

    public function getInfo(){
        return $this->info;
    }




    public function timeToString($value){
        if( $value > 0 ){
            $value = date('Y-m-d' , $value);
        }else{
            $value = 0;
        }
        return $value;
    }

    public function stringToTime($value){
        if( $value != 0 ){
            $value = strtotime( $value );
        }else{
            $value = 0;
        }
        return $value;
    }



    // 自动关联admin模型里面的数据
    public function getJoinAdmin(){
        return $this->hasOne( 'app\admin\model\Admin' , 'id' , 'admin_id' );
    }

}