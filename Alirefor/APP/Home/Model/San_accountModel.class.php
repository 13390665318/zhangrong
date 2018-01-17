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
        'db_user'  => 'root',
        'db_pwd'   => 'clannadmylove123',
        'db_host'  => '120.24.215.214',
        'db_port'  => '3306',
        'db_name'  => 'gsyxsrc',
        'db_charset' => 'utf8',
    );

}