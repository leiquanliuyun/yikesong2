<?php
namespace app\index\model;
use think\Model;

/**
 * 用户表 模型
 */
class User extends Model
{
    //数据自动完成指在不需要手动赋值的情况下对字段的值进行处理后写入数据库。
    protected $auto = [];
    protected $insert = [];
    protected $update = ['update_time'];

    //在数据赋值的时候自动进行转换处理
    protected function setUpdateTimeAttr()
    {
        //修改时间
        return date("Y-m-d H:i:s");
    }

    //在获取数据的字段值后自动进行处理
    public function getGroupIdAttr($value)
    {
        //用户角色
        switch ($value) {
            case 1 :
                return '超级管理员';
            case 2 :
                return '客服';
            case 3 :
                return '代理人';
            case 4 :
                return '普通用户';
            case 5 :
                return '机构';
            case 6 :
                return '前端管理员';
            default :
                return '';
        }
    }

    public function getStatusAttr($value)
    {
        //用户状态
        switch ($value) {
            case '1' :
                return '启用';
            case '2' :
                return '停用';
            case '3' :
                return '已删除';
            default :
                return '';
        }
    }

    //定义一对一关联方法  管理员表
    public function admin()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('InfoAdmin','uid','uid')->field('uid,admin_id,name,sex');
    }
    //定义一对一关联方法  客服信息表
    public function service()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('InfoService','uid','uid')->field('uid,service_id,name,sex');
    }
    //定义一对一关联方法  前端管理员/机构/代理人/顾客信息表
    public function client()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('InfoClient','uid','uid');
    }
    //定义一对一关联方法  上级代理商/顾客信息表
    public function belong()
    {
        //hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
        return $this->hasOne('InfoClient','uid','belong_id');
    }

}