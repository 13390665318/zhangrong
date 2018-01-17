<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/10 0010
 * Time: 下午 6:27
 */

namespace Admin\Controller;


class LtvController extends BaseController
{
    public function index(){
        if (isset($_SESSION["game_id"]) && isset($_SESSION["game_name"])) {
            $game_id = $_SESSION["game_id"];
         } else {
             $game_id = 1;
         }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
       if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            if(isset($_SESSION["db_id"])){
                
                $db_id= $_SESSION["db_id"];
            }else{
                $db_id=$clostu[0]["db_id"];
                $_SESSION["db_id"]=$db_id;
            }

        }
        $this->assign("db_id",$db_id);
        // 平台查询
        $Souarr=D("user")->where("game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
        $this->assign("Souarr",$Souarr);
        // 选择运营平台
        $Parr=array();
        if(isset($_GET["source"])){
            $source=I("get.source");
            $Parr= explode(',',$source);
            $Parr=array_filter($Parr);
        }else{
            for($i=0;$i<count($Souarr);$i++){
                $Parr[$i]=$Souarr[$i]["source"];
            }
        }
        $Ptarr=implode(',',$Parr);
        $this->assign("Parr",$Ptarr);

        //选择汇总方式
        if(isset($_GET["type"])){
            $type=I("get.type");
        }else{
            $type=1;
        }
        $this->assign("type",$type);
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-30 day"));
            $etime=date("Y-m-d",strtotime("-0 day"));
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $day=count_days($stime,$etime);
        $dbname=D("db")->where("db_id=$db_id")->find();
        for($i=0;$i<$day;$i++){
            for($j=0;$j<count($Souarr);$j++){
                $arr[$i][$j]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
                $arr[$i][$j]["clothes"]=$dbname["clothes"];
                $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
                $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
                $source=$Souarr[$j]["source"];
                $arr[$i][$j]["source"]=$source;
                $arr[$i][$j]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and source='$source'  and game_id=$game_id and db_id=$db_id")->count();
                //  day 1
                $Strtime1=date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime)));
                $Endtime1=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime)));
                $arr[$i][$j]["money1"]=D("pay")->where("pay_time>='$Strtime1' and pay_time<='$Endtime1'and game_id=$game_id and db_id=$db_id")->sum("money");
                //day 2
                $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day", strtotime($Strtime)));
                $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                $arr[$i][$j]["money2"]=D("pay")->where("pay_time>='$Strtime2' and pay_time<='$Endtime2'and game_id=$game_id and db_id=$db_id")->sum("money");
                //day 3
                $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                $arr[$i][$j]["money3"]=D("pay")->where("pay_time>='$Strtime3' and pay_time<='$Endtime3'and game_id=$game_id and db_id=$db_id")->sum("money");
                // day 4
                $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                $Endtime4=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                $arr[$i][$j]["money4"]=D("pay")->where("pay_time>='$Strtime4' and pay_time<='$Endtime4'and game_id=$game_id and db_id=$db_id")->sum("money");
                // day 5
                $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                $arr[$i][$j]["money5"]=D("pay")->where("pay_time>='$Strtime5' and pay_time<='$Endtime5'and game_id=$game_id and db_id=$db_id")->sum("money");
                //day 6
                $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                $arr[$i][$j]["money6"]=D("pay")->where("pay_time>='$Strtime6' and pay_time<='$Endtime6'and game_id=$game_id and db_id=$db_id")->sum("money");
                // day 7
                $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day", strtotime($Strtime)));
                $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                $arr[$i][$j]["money7"]=D("pay")->where("pay_time>='$Strtime7' and pay_time<='$Endtime7'and game_id=$game_id and db_id=$db_id")->sum("money");
                //day 30
                $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                $arr[$i][$j]["money30"]=D("pay")->where("pay_time>='$Strtime30' and pay_time<='$Endtime30'and game_id=$game_id and db_id=$db_id")->sum("money");

            }
        }
        $arr2=array();
        foreach($arr as $value)
        {
            foreach($value as $v){
                $arr2[]=$v;
                unset($arr,$value,$v);
            }
        }
      $stu=array_reverse($arr2);
        $this->assign("arr",$stu);
        $this->display();
    }

}