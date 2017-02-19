<?php
return array(
    'DEFAULT_THEME'     =>    'Default', //后台模板主题
    //'TMPL_ENGINE_TYPE'  =>    'Smarty',
    'TMPL_ACTION_ERROR' => 'Public:error',    //错误页面
    'TMPL_ACTION_SUCCESS' => 'Public:success',      //成功页面

    //模板静态文件路径解析
    'TMPL_PARSE_STRING' => array (
        '__IMG__'    => __ROOT__ . '/tuan/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/tuan/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/tuan/Public/' . MODULE_NAME . '/js',
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

//    'SESSION_AUTO_START'    =>  true,       // 设置session自动开启
//    'SESSION_OPTIONS'       =>  array(      // 设置session的初始化参数
//       'expire' => 60,
//    ),
);