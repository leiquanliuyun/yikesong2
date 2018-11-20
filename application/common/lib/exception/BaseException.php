<?php
namespace app\common\lib\exception;
use think\Exception;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/26
 * Time: 10:49
 */
class BaseException extends Exception{

    public $code = 500;
    public $msg = '服务器内部异常';
    public $errcode = 1008;
}