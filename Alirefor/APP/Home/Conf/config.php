<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 上午 11:06
 */
return array(
    'LANG_SWITCH_ON' => true,   // 开启语言包功能

    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效

    'LANG_LIST'        => 'zh-cn,zh-tw,en-us', // 允许切换的语言列表 用逗号分隔

    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

    'DEFAULT_LANG'     =>  'en-us', // 默认语言
  'URL_MODEL' => 0,
    'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               => 'localhost', // 服务器地址
    'DB_NAME'               => 'Alirefor_system',          // 数据库名
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => '666666',
  //  'DB_PWD'                => '',  // 密码
    'DB_PORT'               => '',        // 端口
    //  'DB_PREFIX'             => '',    // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    => false,       // 是否进行字段类型检查
    'DB_FIELDS_CACHE'       => false,        // 启用字段缓存
    'DB_CHARSET'            => 'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        => false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         => 1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           => '', // 指定从服务器序号
    'DB_SQL_BUILD_CACHE'    => false, // 数据库查询的SQL创建缓存
    'DB_SQL_BUILD_QUEUE'    => 'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc
    'DB_SQL_BUILD_LENGTH'   => 20, // SQL缓存的队列长度
    'DB_SQL_LOG'            => false, // SQL执行日志记录
    'DB_PARAMS'             => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),//不强制转换
    'DATA_CACHE_TYPE'		=> 'Redis',
    'REDIS_HOST'            =>  '127.0.0.1',
    'REDIS_PORT'            =>  6379,












);
