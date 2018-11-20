<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    
    // 应用命名空间
    //'app_env'                =>'dev',//运行环境为dev时支付价位0.01
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => true,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__PUBLIC__' => __PUBLIC__,//public目录的全局变量，在/public/index.php中定义
        '__ROOT__' => '/',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用   \think\exception\Handle \app\api\exception\Http
    'exception_handle'       => '\app\api\exception\Http',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    // +----------------------------------------------------------------------
    // | 验证码设置
    // +----------------------------------------------------------------------
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 25,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度
        'imageH'   => 80,
        // 验证码图片宽度
        'imageW'   => 200,
        // 验证码位数
        'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true
    ],
    // 聚合数据
    'juhe' => [
        'appkey' => '2b608525e26302284ae92caf823f7f57',
        'idcard_appkey' => '066540f8ea7deefb1c07b4c557ad69c4',
        'trademark_appkey'=>'c4bd7ec5b7c6e01ef8d302090125680b',
        'company_appkey'=>'add0d81327662ca04edba0982d2fa559',
        'sms_appkey'=>'10c489020926431b3a5a6e5970d4267f'
    ],
    'alipay'=>[
        'app_id'=>'2018042802606358',
        'app_screat'=>'vs50h9ojarolmzi8q66uqgei9jdctrvp',
        'custom_private_key'=>'MIIEowIBAAKCAQEAyPpeO6Du+yfbSGLgyVSBs0fRe1Eauqz3sBc5/kTxG5c+np1DfrP/yBWvn1bzn72+PEPDZGF3MZeSfTBxCSgfj3uMtYt54OTLMhaJ8W6k+Bn/ee0c0Ob+GmLAmf9yhTt3pgCNp5i8hvda9l9Sqd+u3qFtnuSc0uYpGx+h3Oc6MIWNjRj7DKUMUNe2Pcp1wGzNW9/XFAv0STJ3F426yGU20TnCofQ3JBFTXV95FrC2oCPJAsVMZPIiKh6F3ZGFJ8NgwA56auIcQmYMuYcCAhkAhJRABq5tuwEAlVvNVfhm+YYFIOebPzAHSVC02LNGDUj96HCMTWh2FChPiLe2UVTZAQIDAQABAoIBAG1q4aLVG0bhjCD8tCToPTS+BO5+WW8IfFECVDB6mEnNLeps9DInDTqBk/vL+xcc1lU5D0e3SI1XWAmQNfomPgh/2zt7k/88kOPUIYWwF2B7xvs6fFW/bNgwq2ssB81BcwKNipGUNg/E22EGxJp3jVznxiabkEXByN9beWQq/yMqm04AnkhAC+MuqbvGrBQnshPjgVkdNXx2uEBYK0UjepHidCf/KI8CFVgGumaQBVtYpYBacD7FMqzg0VKy4sCotjHpljQ8/uqPTXOOVHU8W5zz5Tdeo/0BveGM/Srqv+i8U65EXqoaqil5jeEDtgjTU+NzU++fVh+mLNmDP1gg6AECgYEA8X4K62LWOeH4+kY7rtZGhtsjfj9V+XP4HsM3G5bXJhHxURa//pp/NQ2vTAyMG53J5bT3QkuqCB/k8M7dS0RNNQDYic35r4qDXJNfG09spuhHR8yH30EWEsiKbCZUpQ+mS5wm7LatctE8qtxF0rkrsNgnhNOajGlnbRCAB2R5MrkCgYEA1Q0/VhX1tXsT6G8PS23LxQrMRDEE2zGq7aGYM/3KOMX2byw9+Ya9QFV0zs9KoguMn2TD0W/qTRw5Is7Yk/rMVc03t+sQAebZyog5SwGIpEcCVdJYaYrd4OtGtmyn0AI8927/f5d7USroP4Ve0g/bbjEQ1wKBanpVzZQc3yx0VIkCgYEA5EC2IXXx9hPYOPZZQw/U6XIHQjSrFnS+eJUbaUBRavJanMJcBlIRGhwtLDxP0Wp6glthuY+6zqMWyWU9VP7h2s9J3DaLYZgSQVYp9q5DjB7QANUZN7NVIbva2g4Aw2LfU2fsgqDHoFaUeQpqFJpg6W3lLG61DTIzR6ro2BPnWkECgYAiXWeo8BPY+QhGBpv187jZiJtYKHv7CzDh8Z3GTRpwO+Y7GmzIseYW2XHk5eTI7In7L9qyDpZBZ9sDdU/T1rFc6aQiI2VKmTzqAT0UjyPGM7n5s9sU8xRE8k5OoIoU8Dd7RckqgHmgpwYdMNsRkQM46+smcz4CucbbSRe7G8WL2QKBgGzCdgvhaZo307Wmdh4omjEUyEB2iNX6llKOp6kAWLH8c0YyZDKDLb/HbTaV1nZd3nesiyPBwTorEvj7HUSaofndF5XbZi3amImWw7LiCd23EwxNqTw21vpD+PAjUTUj2ItBDZRSvRTXumXOAOFdn8nn64IVIX7/WZWo4YP3Pg7i',
        'custom_public_key' =>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyPpeO6Du+yfbSGLgyVSBs0fRe1Eauqz3sBc5/kTxG5c+np1DfrP/yBWvn1bzn72+PEPDZGF3MZeSfTBxCSgfj3uMtYt54OTLMhaJ8W6k+Bn/ee0c0Ob+GmLAmf9yhTt3pgCNp5i8hvda9l9Sqd+u3qFtnuSc0uYpGx+h3Oc6MIWNjRj7DKUMUNe2Pcp1wGzNW9/XFAv0STJ3F426yGU20TnCofQ3JBFTXV95FrC2oCPJAsVMZPIiKh6F3ZGFJ8NgwA56auIcQmYMuYcCAhkAhJRABq5tuwEAlVvNVfhm+YYFIOebPzAHSVC02LNGDUj96HCMTWh2FChPiLe2UVTZAQIDAQAB',
        'alipay_public_key' =>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtqJFyhOlBOQt28j0UAo+hjpQKfoPUE45cePlwYbSxig8HawWatc4uZuyH0nR14adJU2BDN02bXo6O1QdCyplREEK+tHCVv/biqf9iHZcyr73K92HpwrpOUss835SP8hmzeYZSn1byhPZWEv5KmsU8bj3tDVmYIlM/P7YszfIYOcAUi8y4xKm3G55fGGkREHtMULpz928yzSy4Q5hkcCRKyBIywdc5QrlAmLi7AJMOkEI2V6Q3XwK+OHZ3Z1plU6t5cZOEoMnQIPjeFGdtTxQgBAZpWhXCB+QqhXQXZMcCHFUCSNanrhqhAtjUBo2SExK4785fmqTYZ3uiMPcXKxqgwIDAQAB'
    ],
    // 阿里大于 短信服务
    'alidayu' =>[
        'app_key'=>'LTAIpxAZz45pq0KO',
        'app_secret'=>'Ol8Il6UFpzpKQ4mU9xc9Ry7aIRBa4B',
        'signature'=>'家家爱',//你的短信签名
        // 'template_code' => 'SMS_134110410'
    ],
    'web_url'=>'http://app.yikesong66.com',
    'weixinpay'=>[
        'app_id' => 'wx962eb1b664b4629b',//wx7b2d66ec2099345f
        'mch_id' => '1518202791',//1504994381
        'app_secret' => '1f9122ec507337b748ec1ff4cddc21b2',//
        'pay_secret'=>' hangzhouyikesongqiyeguanliyouxia',
        'ot_app_id'=>'wxc5bb528ed1c3ce83',
        'ot_mch_id'=>'1502459281',
        'ot_app_secret'=>'b2477413d850aabf7d54b97fdae5b515'
    ],
   /* 'weixinpay'=>[
        'app_id' => 'wx6bbe956bc41e07d9',//wx6bbe956bc41e07d9
        'mch_id' => '1504994381',//1504994381
        'app_secret' => 'ad70f56b155394df49dc13906375fbe3',//
        'pay_secret'=>'ZK5n9c2IM7qD3FEtN8Xbg2kcIeEmP1X8',
        'ot_app_id'=>'wxc5bb528ed1c3ce83',
        'ot_mch_id'=>'1502459281',
        'ot_app_secret'=>'b2477413d850aabf7d54b97fdae5b515'
    ],*/
    'jpush'=>[
        'app_key' => '6b88b7e71178ee1bfaeb412e',
        'master_secret' => '8c1a732d7db20c7f91315585'
    ],
    // 个推推送平台，根据不同的用户类型，调用不同的推送配置；
    'getui' => [
        // 开发环境
        'dev' => [
            // 消费端
            'consumer' => [
                'app_id' => 'dp2aD3rLJL8oPs4tIgrLn9',
                'app_secret' => 'UwXuw3jPUx9P1iMrwgZci6',
                'app_key' => '49sifwTq3W6E38GVjsXt88',
                'master_secret' => 'z6x8dROmMW963Io9EFC8V7'
            ],
            // 商家端
            'merchant' => [
                'app_id' => 'FcWPB8t0qs9wUo9I85Mga',
                'app_secret' => 'WSbzqhv98D6vS1oveCJ3E4',
                'app_key' => 'CVVYpomtrU7FcrXTXcLLY3',
                'master_secret' => 'xjmmxrEdoo7CqTOQGuF8R9'
            ],
            // 服务端
            'server' => [
                'app_id' => 'O92vm5fyqf6nCUDI3qEUX3',
                'app_secret' => 'UlXwe7t9Nd5NlqWxRnhqe1',
                'app_key' => 'U2Vr5xvORe8yHD5ORXVIu',
                'master_secret' => 'qzXRHR9plW5V4o6ITCdox4'
            ]
        ],
        // 生产环境
        'product' => [
            // 消费端
            'consumer' => [
                'app_id' => 'lzL5ts8Kxf66gKVsNEygf3',
                'app_secret' => 'l8Bqhaf4ff9yf77GUvQOa2',
                'app_key' => 'kIRG34nEgfAjpCqdRfNmO5',
                'master_secret' => 'L0JnmkE1Mw5bRiA1lSTWi2'
            ],
            // 商家端
            'merchant' => [
                'app_id' => 'kN3MsU0IfX7NyvNhNg6kz',
                'app_secret' => 'AGKzsOJw468ybqyN5QXWc',
                'app_key' => 'ARfFZnNoZA7O2RG5vMbmR1',
                'master_secret' => 'Gdon47V5nYARrRWWjfKgZ3'
            ],
            // 服务端
            'server' => [
                'app_id' => 'O92vm5fyqf6nCUDI3qEUX3',
                'app_secret' => 'UlXwe7t9Nd5NlqWxRnhqe1',
                'app_key' => 'U2Vr5xvORe8yHD5ORXVIu',
                'master_secret' => 'qzXRHR9plW5V4o6ITCdox4'
            ]
        ]
    ],
];
