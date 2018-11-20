<?php
namespace app\index\model;
use think\Model;

/**
 * 系统配置参数表 模型
 */
class SystemDeploy extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = [];

    // 关闭自动写入时间戳
    protected $autoWriteTimestamp = false;



}