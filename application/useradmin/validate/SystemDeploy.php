<?php
namespace app\useradmin\validate;
use think\Validate;
/**
 * 系统配置参数表 验证器
 */
class SystemDeploy extends Validate{
	//验证规则
    protected $rule =   [
        '__token__'  => ['require','token']
	];

    //错误提示
	protected $message  =   [
		'__token__'			                =>  '表单令牌规则错误'
    ];
	
}