<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 下午 12:41
 */

namespace Admin\Model;


use Think\Model;

class San_accountModel extends  Model
{

    protected $connection = array(
        'db_type'  => 'mysql',
        'db_user'  => 'sa',
        'db_pwd'   => 'asdf1234!',
        'db_host'  => '192.168.88.20',
        'db_port'  => '3306',
        'db_name'  => 'loong_game',
        'db_charset' => 'utf8',
    );

}