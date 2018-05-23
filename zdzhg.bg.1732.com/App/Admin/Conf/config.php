<?php
return array(
	//'配置项'=>'配置值'
	'SYSTEM_NAME'           => L("public_system_name"),
	'SYSTEM_VERSION'        => '2.0.0[dev]',

	'SHOW_PAGE_TRACE'       => false,

	'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Common/dispatch_jump.html', // 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Common/dispatch_jump.html', // 默认成功跳转对应的模板文件
	'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'View/Common/think_exception.html',// 异常页面的模板文件

	/* 后台自定义设置 */
	'SAVE_LOG_OPEN'         => 0,          //开启后台日志记录
	'MAX_LOGIN_TIMES'       => 9,          //最大登录失败次数，防止为0时不能登录，因此不包含第一次登录
	'LOGIN_WAIT_TIME'       => 60,         //登录次数达到后需要等待时间才能再次登录，单位：分钟
	'LOGIN_ONLY_ONE'        => 0,          //开启单设备登录
	'DATAGRID_PAGE_SIZE'    => 20,         //列表默认分页数
	'CATEGORY_LEVEL'        => 3,          //栏目级数，防止太多影响效率

    /*多语言配置*/
    'LANG_SWITCH_ON'     =>     true,    //开启语言包功能
    'LANG_AUTO_DETECT'     =>     true, // 自动侦测语言
    'DEFAULT_LANG'         =>     cookie('think_language')==''?'ko-kr':cookie('think_language'), // 默认语言
    'LANG_LIST'            =>    'ko-kr,zh-cn,en-us', //必须写可允许的语言列表
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

    /*获取平台列表 */
     'PLAT_LIST' => array(
            '-1' => L("public_use_player_all"),
            '0' => 'IOS',
            '1' => 'AOS',
        ),

     /*日志Log表配置*/
     'USER_LOG_TABLE' => array(
            l("config_novice_guidance")=>L("config_novice_guidance"),
            l("config_combat_record")=>L("config_combat_record"),
            l("config_social_record")=>L("config_social_record"),
            L("config_game_props")=>L("config_game_props"),
            L("config_role_cue_change")=>L("config_role_cue_change"),
            L("config_currency_record")=>L("config_currency_record"),
            L("config_merchandise_sales_record")=>L("config_merchandise_sales_record"),
            L("config_login_and_retention")=>L("config_login_and_retention"),
            //L("config_novice_boot")=>L("config_novice_boot"),
            //L("config_tournament_record")=>L("config_tournament_record"),
            //L("config_daily_task_record")=>L("config_daily_task_record"),
            //L("config_player_online_record")=>L("config_player_online_record"),
            L("config_share")=>L("config_share"),
        ),
     /*日志Log表菜单二级联动*/
     'USER_LOG_TABLE_LINKAGE' =>array(
            L("config_share")=>array(
                        L("config_share")=>'sharelink',
                        ),
            L("config_player_online_record")=>array(
                        L("config_player_online_record")=>'playeronline',
                        ),
            L("config_daily_task_record")=>array(
                        L("config_daily_task_record")=>'dailymission',
                        ),
            //L("config_novice_boot")=>array(
            //            L("config_novice_boot")=>'config_novice_boot',
            //            ),
            L("config_novice_guidance")=>array(
                        L("config_novice_guidance")=>'tutorial',
                        ),
            L("config_combat_record")=>array(
                        L("config_campaign")=>'stage_pve',
                        L("config_duel")=>'stage_dual',
                        L("config_encounter_mode")=>'stage_encounter',
                        L("config_leisure_mode")=>'stage_leisure',
                        L("config_competitive_mode")=>'stage_sports',
                        L("config_matching_records")=>'matchinfo',
                        ),
            L("config_social_record")=>array(
                        L("config_friend_operation")=>'friend',
                        //L("config_legion_records")=>'unioninfo',
                        //L("config_legion_members_change")=>'unionmember',
                        ),
            L("config_game_props")=>array(
                        L("config_card_changes")=>'card_change',
                        L("config_appearance")=>'Skin',
                        L("public_email")=>'mail',
                        L("public_goods_type_four")=>'Chest',
                        ),
            L("config_role_cue_change")=>array(
                        L("config_level_upgrade")=>'card_levelup',
                        L("config_experience_change")=>'player_exp',
                        L("config_dan_change")=>'rankchange',
                        L("config_changes_in_athletic_scores")=>'Scorechange',
                        ),
            L("config_currency_record")=>array(
                        L("config_gold_change")=>'gold',
                        L("config_gem_changes")=>'Gem',
                        ),
            L("config_merchandise_sales_record")=>array(
                        L("config_store_purchase_records")=>'Buying',
                        L("config_recharge_record")=>'payment',
                        ),
            L("config_login_and_retention")=>array(
                        //L("config_installation_records")=>'Install',
                        L("config_player_registration")=>'player_register',
                        L("config_player_landing")=>'player_login',
                        L("config_player_logout")=>'Player_logout',
                        ),
            L("config_tournament_record")=>array(
                        L("config_tournament_registration")=>'competition',
                        L("config_tournament_vs")=>'competition_info',
                        L("config_tournament_player_ranking")=>'competition_ranking',
                        ),
        ),
    /*消息协议号*/
    'OPERATION_RECORD_TYPE' => array(
        '1' => L("config_archivechanges"),
        '2' => L("public_use_cancellation"),
        '3' => L("sanction_return_sanction"),
        '4' => L("public_email"),
        '5' => L("config_payment"),
        '6' => L("config_announcement"),
        '7' => L("config_share"),

        '8' => L("config_text"),
        '9' => L("config_preferential"),
        '10' => L("config_reward"),
        '11' => L("config_activity"),
        '12' => L("config_order"),
        '13' => L("config_task"),
    ),
    'CONTROLLER_NAME'=>array(
        'Text',
        'Panel',
        'Adjudication',
        'GmTool',
        'Email',
        'Notice',
        'Prop',
        'Server',
        'Identifier',
        'Preferential',
        'LimitActivity',
        'Order',
    ),
    'CONTROLLER_NAMES'=>array(
        'textdeletepost',
        'textadd',
        'textreloadpost',
        'textreloadpost',
        'upgameuserinfo',
        'getuserinfolog',
        'setrequestunlock',
        'kickplayerffline',
        'setrecovery',
        'sendemail',
        'delemaillog',
        'noticeaddpost',
        'noticemove',
        'onticeedit',
        'onticeeditpost',
        'noticeend',
        'noticedelete',
        'unlockrole',
        'preferentialadd',
        'preferentialedit',
        'preferentialdeletepost',
        'rewardadd',
        'rewardedit',
        'rewarddeletepost',
        'taskdeletepost',
        'limitdeletepost',
        'task_add',
        'task_edit',
        'limit_edit',
        'limit_add',
        'orderedit',
    ),
);