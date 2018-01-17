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
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $data[0]["week"]=0;
        $data[1]["week"]=0;
        $data[2]["week"]=0;
//var_dump($db);
         for($i=0;$i<count($db);$i++){
//echo $i." ";
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
// 本周
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));
            $arr[$i]["num"]=M('user as a','',$connection)->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
//echo $arr[$i]["num"]." ";

            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-6 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->group("a.game_user_id")->field('a.game_user_id')->select();
          // echo M('user as a','',$connection)->getLastSql()."<br/>";
             $user=$arr[$i]["num"]-count($day2);
             $data[0]["week"]= $data[0]["week"]+$user;
// 上周
             $Strtimes=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
             $Endtimes=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
             $arr[$i]["num2"]=D("user")->where("register_time>='$Strtimes' and register_time<='$Endtimes'")->count();


             $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($Strtimes)));
             $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($Strtimes)));
             $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'")->distinct(true)->field('a.game_user_id')->select();
           //  echo M('user as a','',$connection)->getLastSql()."<br/>";
             $user2=$arr[$i]["num2"]-count($day3);
             $data[1]["week"]= $data[1]["week"]+$user2;

//上月本周
             $Strtimess=date('Y-m-d 00:00:00', strtotime ("-41 day", strtotime($stime)));
             $Endtimess=date('Y-m-d 23:59:59', strtotime ("-36 day", strtotime($stime)));
             $arr[$i]["num3"]=D("user")->where("register_time>='$Strtimess' and register_time<='$Endtimess'")->count();
             $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-36 day", strtotime($Strtime)));
             $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtime)));
             $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'")->distinct(true)->field('a.game_user_id')->select();
           //  echo M('user as a','',$connection)->getLastSql()."<br/>";
             $user=$arr[$i]["num3"]-count($day3);
             $data[2]["week"]= $data[2]["week"]+$user;
           //  exit;
        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
    // 双周流失
    public function index2(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $data[0]["week"]=0;
        $data[1]["week"]=0;
        $data[2]["week"]=0;
        for($i=0;$i<count($db);$i++){
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
// 本周
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));
            $arr[$i]["num"]=M('user as a','',$connection)->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();


            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-6 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr[$i]["num"]-count($day2);
            $data[0]["week"]= $data[0]["week"]+$user;
// 上周
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
            $arr[$i]["num2"]=D("user")->where("register_time>='$Strtimes' and register_time<='$Endtimes'")->count();


            $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($Strtimes)));
            $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($Strtimes)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user2=$arr[$i]["num2"]-count($day3);
            $data[1]["week"]= $data[1]["week"]+$user2;

//上月本周
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-41 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-36 day", strtotime($stime)));
            $arr[$i]["num3"]=D("user")->where("register_time>='$Strtimess' and register_time<='$Endtimess'")->count();
            $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-36 day", strtotime($Strtime)));
            $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr[$i]["num3"]-count($day3);
            $data[2]["week"]= $data[2]["week"]+$user;
            //  exit;
        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }

    //月活趋势
    public function index3(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $data[0]["week"]=0;
        $data[1]["week"]=0;
        $data[2]["week"]=0;
        for($i=0;$i<count($db);$i++){
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
// 本周
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-30 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-0 day", strtotime($stime)));
            $arr[$i]["num"]=M('user as a','',$connection)->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();


            $Strtime2=date('Y-m-d 00:00:00', strtotime ("-30 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr[$i]["num"]-count($day2);
            $data[0]["week"]= $data[0]["week"]+$user;
// 上周
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-31 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
            $arr[$i]["num2"]=D("user")->where("register_time>='$Strtimes' and register_time<='$Endtimes'")->count();


            $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-31 day", strtotime($Strtimes)));
            $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($Strtimes)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2s' and b.start_time<='$Endtime2s' and a.register_time>='$Strtimes' and a.register_time<='$Endtimes'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user2=$arr[$i]["num2"]-count($day3);
            $data[1]["week"]= $data[1]["week"]+$user2;

//上月本周
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-395 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-365 day", strtotime($stime)));
            $arr[$i]["num3"]=D("user")->where("register_time>='$Strtimess' and register_time<='$Endtimess'")->count();
            $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-395 day", strtotime($Strtime)));
            $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-365 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2ss' and b.start_time<='$Endtime2ss' and a.register_time>='$Strtimess' and a.register_time<='$Endtimess'")->distinct(true)->field('a.game_user_id')->select();
            //  echo M('user as a','',$connection)->getLastSql()."<br/>";
            $user=$arr[$i]["num3"]-count($day3);
            $data[2]["week"]= $data[2]["week"]+$user;
            //  exit;
        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
}