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

    //日志设置
    'LOG_RECORD'            =>  false,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志

    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'PUBLIC_UPLOAD_PATH'    => './Public/',    //公共的文件上传路径
    'SHOW_PAGE_TRACE'       => false,
    'AU_KEY'                => '2347adfas……&*(',
    'MODULE_ALLOW_LIST'     =>    array('Admin','Api','Home'),

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
    'URL_MODEL'             => 2,

    //数据库配置1
    'DB_MAIN' => array(
        'DB_TYPE' => 'mysqli', // 数据库类型
        'DB_HOST' => '116.62.49.243', // 服务器地址
        'DB_NAME' => 'health', // 数据库名
        'DB_USER' => 'root', // 用户名
        'DB_PWD' => 'root', // 密码Rm97rdZGJz/w
        'DB_PORT' => '3306', // 端口
    ),

    //邮件配置
    'THINK_EMAIL' => array(
        'SMTP_HOST'   => 'smtp.lierda.com', //SMTP服务器
        'SMTP_PORT'   => '25', //SMTP服务器端口
        'SMTP_USER'   => 'xxx@lierda.com', //SMTP服务器用户名
        'SMTP_PASS'   => 'xxx', //SMTP服务器密码
        'FROM_EMAIL'  => 'xxx@lierda.com', //发件人EMAIL
        'FROM_NAME'   => 'ThinkPHP', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
    ),

    'ERROR_PAGE' =>'/Home/Public/404.html',
);