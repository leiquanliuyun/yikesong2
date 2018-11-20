<?php
namespace app\common\lib\exception;
use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/26
 * Time: 11:07
 */
class Http extends Handle {

    public $codo;
    public $msg;
    public $errcode;

    public function render(Exception $e)
    {
        if( $e instanceof BaseException ){

        }else{
            $httpStatus = 200;
            if( config('app_env') != 'dev' ){
                $msg = "服务器异常，请重试！";
                return response( ['status'=>999,'info'=>$msg] , $httpStatus , [] , 'json' );
            }

//

        }
        // {"status":999,"info":"服务器异常，请重试！"}
        // $request = Request::instance();
        // 参数验证错误
//        if ($e instanceof ValidateException) {
//            return json($e->getError(), 422);
//        }
//
//        // 请求异常
//        if ($e instanceof HttpException && request()->isAjax()) {
//            return response($e->getMessage(), $e->getStatusCode());
//        }
//
//        //TODO::开发者对异常的操作
        //可以在此交由系统处理
        return parent::render($e);
    }
}