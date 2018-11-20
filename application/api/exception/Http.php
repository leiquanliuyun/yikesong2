<?php
namespace app\api\exception;

use think\exception\Handle;
use think\exception\HttpException;

/**
 * 异常处理类
 */
class Http extends Handle
{
    /**
     * API后台处理异常信息输出json错误信息
     */
    public function render(\Exception $e)
    {
        if($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        if(!isset($statusCode)) {
            $statusCode = 500;
        }

        $result = [
            'status' => $statusCode,
            'message'  => $e->getMessage(),
            'time' => date("Y-m-d H:s:i")
        ];
        //return json($result,$statusCode);
        return json($result);
    }
}