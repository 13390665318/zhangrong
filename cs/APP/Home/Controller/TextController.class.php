<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 6:24
 */

namespace Admin\Controller;


use Admin\Model\San_accountModel;

class TextController extends BaseController
{
    public function text(){
            $connection = array(
            'db_type'  => "mysql",
            'db_user'  => 'root',
            'db_pwd'   => 'clannadmylove123',
            'db_host'  => '120.24.215.214',
            'db_port'  => '3306',
            'db_name'  => 'gsyxsrc',
            'db_charset' => 'utf8',
        );
        $San_account = M('San_account','',$connection);
        $ad=$San_account->select();
       var_dump($ad);
    }
}