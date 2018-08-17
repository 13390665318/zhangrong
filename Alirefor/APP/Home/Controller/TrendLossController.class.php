<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11 0011
 * Time: 上午 11:13
 */

namespace Home\Controller;


class TrendLossController extends BaseController
{
        // 周流失
    public function index(){
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $info['db_id']=$db_id;
            $ru['a.db_id'] = $db_id;
            $ru['b.db_id'] = $db_id;
        }

            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());

        $this->assign("stime",$stime);

//var_dump($db);

//echo $i." ";

           // $connection=db2($game_id,$db_id);
// 本周

            $Strtime=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($stime)));//上周开始
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));//上周结束
            $info['_string']="register_time>='$Strtime' and register_time<='$Endtime'";
            $arr["num"]=count(M('user')->where($info)->group('game_id')->select());//上周的注册





//echo $arr[$i]["num"]." ";
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-6 day", strtotime($Strtime)));//本周开始
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));//本周结束
            $ru['_string']="b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'";
            $day2=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->group("a.game_id")->field('a.game_id')->select();

            //注册人数-上线人数
             $user=$arr["num"]-count($day2);
             $data[0]["week"]= $user;
// 上周流失
             $Strtimes=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
             $Endtimes=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
             $info['_string']="register_time>='$Strtimes' and register_time<='$Endtimes'";
             $arr["num2"]=count(D("user")->where($info)->group('game_id')->select());//上上周的注册


             $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($Strtimes)));
             $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($Strtimes)));
             $ru['_string']="b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'";
             $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
             $user2=$arr["num2"]-count($day3);
             $data[1]["week"]= $user2;

//上月本周
             $Strtimess=date('Y-m-d 00:00:00', strtotime ("-42 day", strtotime($stime)));
             $Endtimess=date('Y-m-d 23:59:59', strtotime ("-36 day", strtotime($stime)));
             $info['_string']="register_time>='$Strtimess' and register_time<='$Endtimess'";
             $arr["num3"]=count(D("user")->where($info)->group('game_id')->select());//上个月的注册

             $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-36 day", strtotime($Strtime)));
             $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtime)));
             $ru['_string']="b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'";
             $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
           //  echo M('user as a','',$connection)->getLastSql()."<br/>";
             $user=$arr["num3"]-count($day3);
             $data[2]["week"]= $user;
           //  exit;


//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
    // 双周流失
    public function index2(){
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        $stime=date("Y-m-d",time());
        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $info['db_id']=$db_id;
            $ru['a.db_id'] = $db_id;
            $ru['b.db_id'] = $db_id;
        }



// 本双周
            //上双周注册人数
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-27 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtime' and register_time<='$Endtime'";
            $arr["num"]=count(M('user')->where($info)->group('game_id')->select());


            //本双周登录人数
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-13day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("-0day", strtotime($Strtime)));
            $ru['_string']="b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'";
            $day2=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr["num"]-count($day2);
            $data[0]["week"]= $user;
// 上双周
            //上双周的前一个双周注册人数
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-41 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-28 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtimes' and register_time<='$Endtimes'";
            $arr["num2"]=count(M('user')->where($info)->group('game_id')->select());

            //上双周登录人数
            $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-27 day", strtotime($Strtimes)));
            $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($Strtimes)));
            $ru['_string']="b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'";
            $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user2=$arr["num2"]-count($day3);
            $data[1]["week"]= $user2;

//上月双周
            //上月双周前一个双周注册人数
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-57 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-44 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtimess' and register_time<='$Endtimess'";
            $arr["num3"]=count(M('user')->where($info)->group('game_id')->select());
            //上月双周登录人数
            $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-43 day", strtotime($Strtime)));
            $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtime)));
            $ru['_string']="b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'";
            $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr["num3"]-count($day3);
            $data[2]["week"]=$user;
            //  exit;


//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }

    //月活趋势
    public function index3(){
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        $stime=date("Y-m-d",time());
        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $info['db_id']=$db_id;
            $ru['a.db_id'] = $db_id;
            $ru['b.db_id'] = $db_id;
        }




// 本月流失
        //取上个月注册的人数
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-60 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtime' and register_time<='$Endtime'";
            $arr["num"]=count(M('user')->where($info)->group('game_id')->select());

        //本月登录人数
        //上个月注册人数-本月的登录人数=流失人数
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-30 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));
            $ru['_string']="b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'";
            $day2=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            $user=$arr["num"]-count($day2);
            $data[0]["week"]=$user;
//上个月
        //取上上个月注册人数
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-90 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-60 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtimes' and register_time<='$Endtimes'";
            $arr["num2"]=count(M('user')->where($info)->group('game_id')->select());

        //上个月的登录人数
        //上上个月注册人数-上个月登录人数=上个月流失人数
            $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-60 day", strtotime($Strtimes)));
            $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtimes)));
            $ru['_string']="b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'";
            $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            $user2=$arr["num2"]-count($day3);
            $data[1]["week"]=$user2;


//去年
            //去年这个月的前一个月注册人数
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-425 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-395 day", strtotime($stime)));
            $info['_string']="register_time>='$Strtimess' and register_time<='$Endtimess'";
            $arr["num3"]=count(M('user')->where($info)->group('game_id')->select());
            //去年这个月的登录人数
        //去年上个月注册人数-上个月登录人数=上个月流失人数
            $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-395 day", strtotime($Strtime)));
            $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-365 day", strtotime($Strtime)));
            $ru['_string']="b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'";
            $day3=M('user as a')->join("sign as b on a.game_id =b.user_id ")->where($ru)->distinct(true)->field('a.game_id')->select();
            $user3=$arr["num3"]-count($day3);
            $data[2]["week"]= $user3;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
}