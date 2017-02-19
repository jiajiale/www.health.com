<?php
return array(
    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'PUBLIC_UPLOAD_PATH'    => './Public/',    //公共的文件上传路径
    'SHOW_PAGE_TRACE'       => false,
    'AU_KEY'                => '2347adfas……&*(',
    'MODULE_ALLOW_LIST'     =>    array('Admin','Home','Group','Api','Activities','Promotion'),

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
    'URL_MODEL'             => 2,

    //数据库配置1
//    'DB_MAIN' => array(
//        'DB_TYPE' => 'mysqli', // 数据库类型
//        'DB_HOST' => 'park_service.db.lierda.com', // 服务器地址
//        'DB_NAME' => 'park_service', // 数据库名
//        'DB_USER' => 'root', // 用户名
//        'DB_PWD' => '123456', // 密码Rm97rdZGJz/w
//        'DB_PORT' => '3306', // 端口
//    ),

    'DB_MAIN' => array(
        'DB_DEPLOY_TYPE'=> 1,   // 设置分布式数据库支持
        'DB_TYPE' => 'mysqli', // 数据库类型
        'DB_HOST' => '10.16.10.180,10.16.10.181', // 服务器地址
        'DB_NAME' => 'park_service', // 数据库名
        'DB_USER' => 'parkservice', // 用户名
        'DB_PWD' => 'qu+PitjGQ3kRqx', // 密码Rm97rdZGJz/w
        'DB_PORT' => '3306', // 端口
        'DB_RW_SEPARATE' => true,   // 设置读写分离
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
    //'URL_CONTROLLER_MAP'    =>    array('index'=>'route'),
);