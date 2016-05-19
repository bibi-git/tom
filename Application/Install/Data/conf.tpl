<?php

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common','User','Admin','Install'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => '[AUTH_KEY]', //默认数据加密KEY

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2, //URL模式  重写模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
    /*
    *   服务器nginx重写
    *   location / {
    *       if (!-e $request_filename){
    *           rewrite  ^(.*)$  /index.php?s=$1  last;
    *       }
    *   }
    */

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => '[DB_TYPE]', // 数据库类型
    'DB_HOST'   => '[DB_HOST]', // 服务器地址
    'DB_NAME'   => '[DB_NAME]', // 数据库名
    'DB_USER'   => '[DB_USER]', // 用户名
    'DB_PWD'    => '[DB_PWD]',  // 密码
    'DB_PORT'   => '[DB_PORT]', // 端口
    'DB_PREFIX' => '[DB_PREFIX]', // 数据库表前缀
);
