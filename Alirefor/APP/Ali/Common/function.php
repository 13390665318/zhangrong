<?php
/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage(&$m,$where,$pagesize=10){
    $m1=clone $m;//浅复制一个模型
    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置
    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型
    $p=new Think\Page($count,$pagesize);
    $p->lastSuffix=false;
    $p->setConfig('header','<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;每页<b>%LIST_ROW%</b>条&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev','上一页');
    $p->setConfig('next','下一页');
    $p->setConfig('last','末页');
    $p->setConfig('first','首页');
    $p->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

    $p->parameter=I('get.');

    $m->limit($p->firstRow,$p->listRows);

    return $p;
}
/**
 * 计算月数
 */
function getMonthNum($date1,$date2){
    $date1_stamp=strtotime($date1);
    $date2_stamp=strtotime($date2);
    list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',$date1_stamp));
    list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',$date2_stamp));
    return abs($date_1['y']-$date_2['y'])*12 +$date_2['m']-$date_1['m'];
}
/**
 * 计算天数
 */
function count_days($a,$b){
    $d1 = strtotime($a);
    $d2 = strtotime($b);
     $a_dt = getdate($d1);
     $b_dt = getdate($d2);
     $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
     $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
     return round(abs($a_new-$b_new)/86400);
}

// 返回数据库连接
function db($game_id,$db_id){
    $db=D("db")->where("game_id=$game_id and db_id=$db_id")->find();
    $connection = array(
        'db_type'  => "mysql",
        'db_user'  => $db["game_db_user"],
        'db_pwd'   => $db["game_db_pwd"],
        'db_host'  =>$db["game_db_host"],
        'db_port'  => $db["game_db_port"],
        'db_name'  =>$db["game_db_name"],
        'db_charset' => 'utf8',
    );
    return $connection;
}

function db2($game_id,$db_id){
    $db=D("db")->where("game_id=$game_id and db_id=$db_id")->find();
    $connection = array(
        'db_type'  => "mysql",
        'db_user'  => "root",
        'db_pwd'   => "xueranzhengbaohoutai@#!",
        'db_host'  =>"localhost",
        'db_port'  => "3306",
        'db_name'  =>$db["localhost_db_name"],
        'db_charset' => 'utf8',
    );
    return $connection;
}

// 排序
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
// 随机数
function GetRandStr($len) {
    $chars = array(
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = '';
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
function GetRandStr2($len) {
    $chars = array('0','1','2','3','4','5','6','7','8','9');
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = '';
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
?>