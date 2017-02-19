<?php
return array(
    'DEFAULT_THEME'     =>    'Default', //后台模板主题
    //'TMPL_ENGINE_TYPE'  =>    'Smarty',
    'TMPL_ACTION_ERROR' => 'Public:error',    //错误页面
    'TMPL_ACTION_SUCCESS' => 'Public:success',      //成功页面

    //模板静态文件路径解析
    'TMPL_PARSE_STRING' => array (
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),

    'PASSWORD_SALT_KEY' =>  'BFM!#@$^%',    //密码salt

    //'DB_PREFIX' => 'st_common_', // 数据库表前缀
    'DB_PREFIX_COMMON' => 'st_common_', // 公用信息数据表前缀
    'DB_PREFIX_PIN' => 'st_pin_', // 拼车信息数据表前缀
    'DB_PREFIX_TUAN' => 'st_tuan_', // 拼车信息数据表前缀

    //日志设置
    'LOG_RECORD'            =>  false,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志

    'URL_ROUTER_ON'         =>  true,   // 开启路由
    'URL_ROUTE_RULES'       =>  array(  // 路由规则
       array('/^v1\/(.*)/',':1','ver=1'),
       array('/^v2\/(.*)/',':1','ver=2'),
    ),

    //Redis配置
    'REDIS' => array(
        'HOST' => '10.16.10.30',
        'PORT' => '6379',
        'EXPIRE' => 3600
    ),

    'GETUI' => array(
        'APPKEY' => 'lamviHM4Bm9BXMi9IJZxp3',
        'APPSECRET' => 'IpOmxqsl5JA105vFA2OIP8',
        'APPID' => 'LhwjaxpYNa6LklKSJIdvQ1',
        'MASTERSECRET' => 'Pczl8wrfNC7gkByDod5Yp1',
        'HOST' => 'http://sdk.open.api.igexin.com/apiex.htm',
    ),

//    'URL_ACTION_MAP'=>array(
//        'route' => array(
//            'test' => 'get_route_list',
//        ),
//    )
);