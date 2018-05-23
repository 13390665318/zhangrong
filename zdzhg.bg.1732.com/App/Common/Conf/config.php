<?php
return array(
	//'配置项'=>'配置值'
	'SHOW_PAGE_TRACE'       => true,        //调试配置
	'LOAD_EXT_CONFIG'       => 'file',
	'AUTOLOAD_NAMESPACE'    => array('Addons'=> SITE_DIR.'/Addons'),

	'FILE_UPLOAD_TYPE'      => 'Local',      //上传驱动
    
    'GM_TOOL_API'           => 'http://192.168.7.85:8860/gm_center.php', //游戏APID地址
    'GM_TOOL_API_KEY'       => 'ca8bf673063b36f5d18e25fd557de37b', //秘钥

//    /* 数据库设置 */
//    'DB_TYPE'               => 'mysql',      // 数据库类型
//    'DB_HOST'               => '192.168.6.201',  // 服务器地址
//    'DB_NAME'               => 'easytp',     // 数据库名
//    'DB_USER'               => 'db_writer',       // 用户名
//    'DB_PWD'                => '1732#write',           // 密码
//    'DB_PORT'               => '3306',       // 端口
//    'DB_PREFIX'             => 'et_',        // 数据库表前缀

    /* 数据库设置 */
    'DB_TYPE'               => 'mysql',      // 数据库类型
    'DB_HOST'               => 'localhost',  // 服务器地址
    'DB_NAME'               => 'easytp',     // 数据库名
    'DB_USER'               => 'root',       // 用户名
    'DB_PWD'                => '666666',           // 密码
    'DB_PORT'               => '3306',       // 端口
    'DB_PREFIX'             => 'et_',        // 数据库表前缀

    ///* 数据库设置 */
    //'DB_TYPE'               => 'mysql',      // 数据库类型
    //'DB_HOST'               => '47.52.33.65',  // 服务器地址
    //'DB_NAME'               => 'easytp',     // 数据库名
    //'DB_USER'               => 'db_writer',       // 用户名
    //'DB_PWD'                => 'gepFQiVYTK3zsEZI',           // 密码
    //'DB_PORT'               => '3306',       // 端口
    //'DB_PREFIX'             => 'et_',        // 数据库表前缀

    'ZDZHG_DB_TYPE'         => 'mysql',      // 数据库类型
    'ZDZHG_DB_HOST'         => '192.168.7.85',  // 服务器地址
    'ZDZHG_DB_NAME'         => 'newstatisticsdb',     // 数据库名
    'ZDZHG_DB_USER'         => 'root',  // 用户名
    'ZDZHG_DB_PWD'          => 'root', // 密码
    'ZDZHG_DB_PORT'         => '3306',       // 端口
    'ZDZHG_DB_PREFIX'       => '',        // 数据库表前缀

	/* URL设置 */
	'MODULE_ALLOW_LIST'     => array('Home', 'Admin', 'Install'),
	'DEFAULT_MODULE'        => 'Admin',       // 默认模块
    'URL_MODEL_PARAS'		    => '&',			// URL参数连接字符
	'URL_CASE_INSENSITIVE'  => true,         // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 0,            // URL模式

	/* 模板标签设置 */
	'TMPL_L_DELIM'          => '<{',         // 模板引擎普通标签开始标记
	'TMPL_R_DELIM'          => '}>',         // 模板引擎普通标签结束标记

	/* 模板解析设置 */
	'TMPL_PARSE_STRING'     => array(
		'./Public/upload/'  => SCRIPT_DIR . '/Public/upload/',
		'__PUBLIC__'        => SCRIPT_DIR . '/Public',
		'__STATIC__'        => SCRIPT_DIR . '/Public/static',
		'__VERSION__'       => date('YmdHi'),
	),

	/* 邮箱配置 */
	'EMAIL_CONFIG'          => array(
		'smtp'     => 'smtp.qq.com',
		'port'     => 25,
		'from'     => '@qq.com',
		'user'     => '@qq.com',
		'password' => '',
		'report'   => 'admin@admin.com', //报警接收邮箱
	),

     /*多语言配置*/
    'LANG_SWITCH_ON'     =>     true,    //开启语言包功能
    'LANG_AUTO_DETECT'     =>     true, // 自动侦测语言
    'DEFAULT_LANG'         =>     cookie('think_language')==''?'ko-kr':cookie('think_language'), // 默认语言
    'LANG_LIST'            =>    'ko-kr,zh-cn,en-us', //必须写可允许的语言列表
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

	/* 水印配置 */
	'IMAGE_WATER_CONFIG'    => array(
		'status'   => 0,         //状态
		'type'     => 0,         //模式 1为图片 0为文字
		'text'     => 'EASYTP',  //水印文字
		'image'    => './Public/static/img/logo.png',  //水印图片
		'position' => 9,         //九宫格位置
		'x'        => -5,        //x轴偏移
		'y'        => -5,        //y轴偏移
		'size'     => 30,        //水印文字大小
		'color'    => '#305697', //水印文字颜色
	),
 	/* 接口设置 */
	'API_SIGN'              => '04B29480233F4DEF5C875875B6BDC3B1', //接口签名
    
    /*消息协议号*/
    'MESSAGE_PROTOCOL_NUMBER' => array(
            'IDIP_QUERY_ROLE_BASE_INFO' => '130010',//查询角色基本信息
            'IDIP_QUERY_ROLE_GAME_INFO' => '130011',//查询角色游戏数据
            'IDIP_QUERY_ROLE_MAIL_INFO' => '130012',//查询角色邮件列表
            'IDIP_MODIFY_ROLE_GAME_INFO' => '130013',//修改角色游戏数据
            'IDIP_KICK_PLAYER_OFFLINE' => '130020',//踢玩家角色下线
            'IDIP_RELINK_OLD_USER'=>'130021',//重新关联旧的用户(覆盖新用户新息)
            'IDIP_SEND_MAIL_BY_CONDITION' => '130030',//按条件发送邮件
            'IDIP_SEND_MAIL_BY_ROLES_NUMBER' => '130031',//按角色编号发送邮件
            'IDIP_DEL_MAIL_BY_ID' => '130032',//删除角色邮件
            'IDIP_REQUEST_ADD_PUBLISH_ANNOUNCEMENT' => '130040',//添加系统滚动公告
            'IDIP_REQUEST_UPDATE_PUBLISH_ANNOUNCEMENT' => '130041',//更新系统滚动公告
            'IDIP_REQUEST_DEL_PUBLISH_ANNOUNCEMENT' => '130042',//删除系统滚动公告
            'IDIP_REQUEST_LOCK_ROLE' => '130050',//封号
            'IDIP_REQUEST_UNLOCK_ROLE' => '130051',//解除封号
            'IDIP_REQUEST_DELETE_USER' => '130060',//注销账号
            'IDIP_REQUEST_RECOVERY_USER' => '130061',//恢复账号
            'IDIP_REQUEST_QUERY_USER_DEL_RECORD' => '130062',//查询角色注销历史
            'IDIP_BATCH_QUERY_ROLE_INFO' => '130070',//批量查询玩家名字和id
            'IDIP_QUERY_LOCK_ROLES' => '130080',//查询已封的角色
            'IDIP_ADD_LANGUAGE_TEXT' => '130090',//添加语言文本
            'IDIP_DEL_LANGUAGE_TEXT' => '130091',//删除文本
            'IDIP_RELOAD_LANGUAGE_TEXT' => '130092',//重新加载文本
            'IDIP_QUERY_SHOP_AWARD' => '130100',//查询商店奖励配置
            'IDIP_QUERY_SHOP_GOODS' => '130101',//查询商店商品配置
            'IDIP_ADD_SHOP_AWARD' => '130102',//添加商店奖励配置
            'IDIP_ADD_SHOP_GOODS' => '130103',//添加商店商品配置
            'IDIP_DEL_SHOP_AWARD' => '130104',//删除商店奖励配置
            'IDIP_DEL_SHOP_GOODS' => '130105',//删除商店商品配置
            'IDIP_MODIFY_SHOP_AWARD' => '130106',//修改商店奖励配置
            'IDIP_MODIFY_SHOP_GOODS' => '130107',//修改商店商品配置
            'IDIP_RELOAD_SHOP_CONFIG' => '130108',//重加加载配置
            'IDIP_QUERY_PAY_ORDER' => '130110',//查询订单
            'IDIP_MODIFY_PAY_ORDER_STATUS' => '130111',//修改订单状态
            'IDIP_QUERY_EXCHANGE_AWARD' => '130120',//查询兑换奖励
            'IDIP_QUERY_EXCHANGE_ACTIVITY' => '130121',//查询兑换活动
            'IDIP_CREATE_EXCHANGE_AWARD' => '130122',//创建兑换奖励
            'IDIP_CREATE_EXCHANGE_ACTIVITY' => '130123',//创建兑换活动
            'IDIP_DEL_EXCHANGE_AWARD' => '130124',//删除兑换奖励
            'IDIP_DEL_EXCHANGE_ACTIVITY' => '130125',//删除兑换活动
            'IDIP_UPDATE_EXCHANGE_AWARD' => '130126',//修改兑换奖励
            'IDIP_UPDATE_EXCHANGE_ACTIVITY' => '130127',//修改兑换活动
            'IDIP_RELOAD_ACTIVITY_CONFIG' => '130128',//重新加载活动配置
            'IDIP_RELOAD_CLEAR_PLAYER_RANK'=>'130129',//清除指定玩家排行

        ),
);