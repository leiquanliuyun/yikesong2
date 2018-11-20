<?php
namespace app\common\validate;
use think\Validate;

/**
 * 资讯表 验证器
 */
class Pact extends Validate
{
    //验证规则
    protected $rule =   [
        //'temp_id'      => ['require','number'],
        'detail'       => ['require'],
        //'description'    => ['require'],
        '__token__'  => ['require','token']
    ];

    //错误提示
    protected $message  =   [
        //'temp_id.require'              => '合同模板必需',
       // 'temp_id.number'               => '合同模板不为数字',
        'detail'            => '合同详细内容必需',
        '__token__'			 => '表单令牌规则错误'
    ];

}