<?php
namespace app\api\model;
use think\Model;

/**
 * Created by PhpStorm.
 * User: hejinke
 * Date: 2018/4/26
 * Time: 9:53
 */
class Common extends Model{



    private $info  =  '未知错误！';
    //private $web_url=config('web_url');
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

}