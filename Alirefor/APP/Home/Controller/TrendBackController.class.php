<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11 0011
 * Time: 下午 3:51
 */

namespace Home\Controller;


class TrendBackController extends BaseController
{
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
        for($i=0;$i<count($db);$i++){
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
// 本周
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-6 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($stime)));
            $day2=M('sign','',$connection)->where("start_time>='$Strtime' and start_time<='$Endtime'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day2);$j++){
                $Strtime1=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($stime)));
                $Endtime1=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));
                $uid=$day2[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1' and start_time<='$Endtime1' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
                    $Endtime2=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2' and start_time<='$Endtime2' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[0]["week"]++;
                    }
                }
            }
// 上周
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-13 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));
            $day3=M('sign','',$connection)->where("start_time>='$Strtimes' and start_time<='$Endtimes'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day3);$j++){
                $Strtime1s=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
                $Endtime1s=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
                $uid=$day3[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1s' and start_time<='$Endtime1s' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-27 day", strtotime($stime)));
                    $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-21 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2s' and start_time<='$Endtime2s' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[1]["week"]++;
                    }
                }
            }
//上月
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-36 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
            $day4=M('sign','',$connection)->where("start_time>='$Strtimess' and start_time<='$Endtimess'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day4);$j++){
                $Strtime1ss=date('Y-m-d 00:00:00', strtotime ("-43 day", strtotime($stime)));
                $Endtime1ss=date('Y-m-d 23:59:59', strtotime ("-37 day", strtotime($stime)));
                $uid=$day4[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1ss' and start_time<='$Endtime1ss' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-50 day", strtotime($stime)));
                    $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-44 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2ss' and start_time<='$Endtime2ss' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[2]["week"]++;
                    }
                }
            }



        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
// 双周
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
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($stime)));
            $day2=M('sign','',$connection)->where("start_time>='$Strtime' and start_time<='$Endtime'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day2);$j++){
                $Strtime1=date('Y-m-d 00:00:00', strtotime ("-27 day", strtotime($stime)));
                $Endtime1=date('Y-m-d 23:59:59', strtotime ("-14 day", strtotime($stime)));
                $uid=$day2[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1' and start_time<='$Endtime1' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2=date('Y-m-d 00:00:00', strtotime ("-41 day", strtotime($stime)));
                    $Endtime2=date('Y-m-d 23:59:59', strtotime ("-28 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2' and start_time<='$Endtime2' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[0]["week"]++;
                    }
                }
            }
// 上周
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-20 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-7 day", strtotime($stime)));
            $day3=M('sign','',$connection)->where("start_time>='$Strtimes' and start_time<='$Endtimes'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day3);$j++){
                $Strtime1s=date('Y-m-d 00:00:00', strtotime ("-34 day", strtotime($stime)));
                $Endtime1s=date('Y-m-d 23:59:59', strtotime ("-21 day", strtotime($stime)));
                $uid=$day3[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1s' and start_time<='$Endtime1s' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-48 day", strtotime($stime)));
                    $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-35 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2s' and start_time<='$Endtime2s' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[1]["week"]++;
                    }
                }
            }
//上月
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-41 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
            $day4=M('sign','',$connection)->where("start_time>='$Strtimess' and start_time<='$Endtimess'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day4);$j++){
                $Strtime1ss=date('Y-m-d 00:00:00', strtotime ("-55 day", strtotime($stime)));
                $Endtime1ss=date('Y-m-d 23:59:59', strtotime ("-42 day", strtotime($stime)));
                $uid=$day4[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1ss' and start_time<='$Endtime1ss' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-69 day", strtotime($stime)));
                    $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-56 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2ss' and start_time<='$Endtime2ss' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[2]["week"]++;
                    }
                }
            }



        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
    // 月
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
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-29 day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($stime)));
            $day2=M('sign','',$connection)->where("start_time>='$Strtime' and start_time<='$Endtime'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day2);$j++){
                $Strtime1=date('Y-m-d 00:00:00', strtotime ("-59 day", strtotime($stime)));
                $Endtime1=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
                $uid=$day2[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1' and start_time<='$Endtime1' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2=date('Y-m-d 00:00:00', strtotime ("-89 day", strtotime($stime)));
                    $Endtime2=date('Y-m-d 23:59:59', strtotime ("-60 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2' and start_time<='$Endtime2' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[0]["week"]++;
                    }
                }
            }
// 上周
            $Strtimes=date('Y-m-d 00:00:00', strtotime ("-59 day", strtotime($stime)));
            $Endtimes=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
            $day3=M('sign','',$connection)->where("start_time>='$Strtimes' and start_time<='$Endtimes'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day3);$j++){
                $Strtime1s=date('Y-m-d 00:00:00', strtotime ("-89 day", strtotime($stime)));
                $Endtime1s=date('Y-m-d 23:59:59', strtotime ("-60 day", strtotime($stime)));
                $uid=$day3[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1s' and start_time<='$Endtime1s' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2s=date('Y-m-d 00:00:00', strtotime ("-119 day", strtotime($stime)));
                    $Endtime2s=date('Y-m-d 23:59:59', strtotime ("-90 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2s' and start_time<='$Endtime2s' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[1]["week"]++;
                    }
                }
            }
//上月
            $Strtimess=date('Y-m-d 00:00:00', strtotime ("-394 day", strtotime($stime)));
            $Endtimess=date('Y-m-d 23:59:59', strtotime ("-365 day", strtotime($stime)));
            $day4=M('sign','',$connection)->where("start_time>='$Strtimess' and start_time<='$Endtimess'")->distinct(true)->field('game_user_id')->select();
            for($j=0;$j<count($day4);$j++){
                $Strtime1ss=date('Y-m-d 00:00:00', strtotime ("-424 day", strtotime($stime)));
                $Endtime1ss=date('Y-m-d 23:59:59', strtotime ("-395 day", strtotime($stime)));
                $uid=$day4[$j]["game_user_id"];
                $rus=M('sign','',$connection)->where("start_time>='$Strtime1ss' and start_time<='$Endtime1ss' and game_user_id=$uid")->select();
                if($rus==null){
                    $Strtime2ss=date('Y-m-d 00:00:00', strtotime ("-454 day", strtotime($stime)));
                    $Endtime2ss=date('Y-m-d 23:59:59', strtotime ("-425 day", strtotime($stime)));
                    $ru=M('sign','',$connection)->where("start_time>='$Strtime2ss' and start_time<='$Endtime2ss' and game_user_id=$uid")->select();
                    if($ru!=null){
                        $data[2]["week"]++;
                    }
                }
            }



        }

//var_dump($data);exit;


        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
}