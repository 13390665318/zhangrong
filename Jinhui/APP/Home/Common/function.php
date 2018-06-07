<?php

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

function db3($game_id,$db_id){
    $db=D("db")->where("game_id=$game_id and db_id=$db_id")->find();
    $connection = array(

        'redis_ip'  => $db["redis_ip"],
        'redis_port'   => $db["redis_port"],
        'redis_db'  =>$db["redis_db"],

    );
    return $connection;
}

function db2($game_id,$db_id){
//echo $game_id . " ".$db_id;

    $db=D("db")->where("game_id=$game_id and db_id=$db_id")->find();
//var_dump($db);
    $connection = array(
        'db_type'  => "mysql",
        'db_user'  => "root",
        'db_pwd'   => "666666",
        'db_host'  =>"localhost",
        'db_port'  => "3306",
        'db_name'  =>$db["localhost_db_name"],
        'db_charset' => 'utf8',
    );
    return $connection;
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

function timediff($begin_time,$end_time)
{
    if($begin_time < $end_time){
        $starttime = $begin_time;
        $endtime = $end_time;
    }else{
        $starttime = $end_time;
        $endtime = $begin_time;
    }

    //计算天数
    $timediff = $endtime-$starttime;
    $days = intval($timediff/86400);
    //计算小时数
    $remain = $timediff%86400;
    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;
    // $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
    $res=$days."天".$hours."小时".$mins."分".$secs."秒";
    return $res;
}
function input_csv($handle)
{
    $out = array ();
    $n = 0;
    while ($data = fgetcsv($handle, 10000))
    {
        $num = count($data);
        for ($i = 0; $i < $num; $i++)
        {
            $out[$n][$i] = $data[$i];
        }
        $n++;
    }
    return $out;
}

function getNeedBetween($kw1,$mark1,$mark2){
$kw=$kw1;
$kw='123'.$kw.'123';
$st =stripos($kw,$mark1);
$ed =stripos($kw,$mark2);
if(($st==false||$ed==false)||$st>=$ed)
return 0;
$kw=substr($kw,($st+1),($ed-$st-1));
return $kw;
}


function FetchRepeatMemberInArray($array) {
    // 获取去掉重复数据的数组
    $unique_arr = array_unique ( $array );
    // 获取重复数据的数组
    $repeat_arr = array_diff_assoc ( $array, $unique_arr );
    return $repeat_arr;
} 

function arr_foreach ($arr)
   {
      static $tmp=array(); 
      if (!is_array ($arr))
      {
         return false;
      }
      foreach ($arr as $val )
      {
         if (is_array ($val))
         {
            arr_foreach ($val);
         }
         else
         {
            $tmp[]=$val;
         }
      }
      return $tmp;
   }

?>