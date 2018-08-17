<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/10 0010
 * Time: 下午 6:27
 */

namespace Home\Controller;


class LtvController extends BaseController
{
    public function index(){
        $game_id=2;
        // 游戏区/服

        // 图标 默认 最新服
     /*  if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            if(isset($_SESSION["db_id"])){

                $db_id= $_SESSION["db_id"];
            }else{
                $db_id=$clostu[0]["db_id"];
                $_SESSION["db_id"]=$db_id;
            }

        }*/
       // $this->assign("db_id",$db_id);
        // 平台查询
        /*$Souarr=D("user")->where("game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
        $this->assign("Souarr",$Souarr);*/
        // 选择运营平台
      //  $Parr=array();
        /*if(isset($_GET["source"])){
            $source=I("get.source");
            $Parr= explode(',',$source);
            $Parr=array_filter($Parr);
        }else{
            for($i=0;$i<count($Souarr);$i++){
                $Parr[$i]=$Souarr[$i]["source"];
            }
        }
        $Ptarr=implode(',',$Parr);
        $this->assign("Parr",$Ptarr);*/

        //选择汇总方式
        /*if(isset($_GET["type"])){
            $type=I("get.type");
        }else{
            $type=1;
        }*/
        //$this->assign("type",$type);
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-7 day"));
            $etime=date("Y-m-d",strtotime("-0 day"));
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $day=count_days($stime,$etime);
        $today=date("Y-m-d ",time());
        //$dbname=D("db")->where("db_id=$db_id")->find();
        for($i=0;$i<=$day;$i++){

            $arr[$i]["time"] = date('Y-m-d', strtotime("+$i day", strtotime($stime)));
            // $arr[$i][$j]["clothes"]=$dbname["clothes"];
            $Strtime = date('Y-m-d 00:00:00', strtotime("+$i day", strtotime($stime)));
            $Endtime = date('Y-m-d 23:59:59', strtotime("+$i day", strtotime($stime)));
            // $source=$Souarr[$j]["source"];
            // $arr[$i][$j]["source"]=$source;
            //取出当天注册的玩家ID
            $newuser= M('user')->group('game_id')->having("register_time>='$Strtime' and register_time<='$Endtime'")->select();
            $arr[$i]["num"] =count($newuser);

            //  day 1
            $Strtime1 = date('Y-m-d 00:00:00', strtotime("+0 day", strtotime($Strtime)));
            $Endtime1 = date('Y-m-d 23:59:59', strtotime("+0 day", strtotime($Strtime)));
            $time1 = date('Y-m-d ', strtotime("+0 day", strtotime($Strtime)));
            if($time1>$today){
                $arr[$i]["money1s"]='';
            }else{
               /* $arr[$i]["money1"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                where("pay_time>='$Strtime1' and pay_time<='$Endtime1' and register_time>='$Strtime' and register_time<='$Endtime'")->
                sum("pay_number");
                dump(D("pay")->getLastSql());exit;
                $sql="SELECT sum(pay_number) from  pay a 
              LEFT JOIN (SELECT game_id  FROM user WHERE register_time>='$Strtime' and register_time<='$Endtime' GROUP BY game_id) b 
              ON a.user_id = b.game_id where  pay_time>='$Strtime1' and pay_time<='$Endtime1'";
                $arr[$i]["money1"] = D("pay")->query($sql);*/
                /*$sql="SELECT SUM(pay_number) AS sum from  pay a
				LEFT JOIN (SELECT game_id,register_time  FROM user  GROUP BY game_id having register_time>='$Strtime' and register_time<='$Endtime') b 
				ON a.user_id = b.game_id where  pay_time>='$Strtime' and pay_time<='$Endtime1'";*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime1'";
                $arr[$i]["money1"] = D("pay")->query($sql)[0]['sum'];
                //留存个数
              /*  $arr[$i]["num1"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                group('a.game_id')->
                having("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime1' and start_time<='$Endtime1'")->
                select());*/
                $arr[$i]["money1s"]=round($arr[$i]["money1"]/$arr[$i]["num"],2);
            }

            //day 2
            $Strtime2 = date('Y-m-d 00:00:00', strtotime("+1 day", strtotime($Strtime)));
            $Endtime2 = date('Y-m-d 23:59:59', strtotime("+1 day", strtotime($Strtime)));
			 $time2 = date('Y-m-d ', strtotime("+1 day", strtotime($Strtime)));
            if($time2>$today){
                $arr[$i]["money2s"]='';
            }else{
            /*    $arr[$i]["money2"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime2' and pay_time<='$Endtime2' and register_time>='$Strtime' and register_time<='$Endtime' and start_time>='$Strtime2' and start_time<='$Endtime2'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num2"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime2' and start_time<='$Endtime2'")->
                select());*/
                /*$sql="SELECT SUM(pay_number) AS sum from  pay a
				LEFT JOIN (SELECT game_id,register_time  FROM user  GROUP BY game_id having register_time>='$Strtime' and register_time<='$Endtime') b 
				ON a.user_id = b.game_id where  pay_time>='$Strtime' and pay_time<='$Endtime2'";*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime2'";
                $arr[$i]["money2"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money2s"] = round($arr[$i]["money2"]/$arr[$i]["num"],2);
            }

            //day 3
            $Strtime3 = date('Y-m-d 00:00:00', strtotime("+2 day", strtotime($Strtime)));
            $Endtime3 = date('Y-m-d 23:59:59', strtotime("+2 day", strtotime($Strtime)));
            $time3 = date('Y-m-d', strtotime("+2 day", strtotime($Strtime)));
            if($time3>$today){
                $arr[$i]["money3s"]='';
            }else {
                /*$arr[$i]["money3"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime3' and pay_time<='$Endtime3' and register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime3' and start_time<='$Endtime3'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num3"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime3' and start_time<='$Endtime3'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime3'";
                $arr[$i]["money3"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money3s"] = round($arr[$i]["money3"] / $arr[$i]["num"], 2);
            }
            // day 4
            $Strtime4 = date('Y-m-d 00:00:00', strtotime("+3 day", strtotime($Strtime)));
            $Endtime4 = date('Y-m-d 23:59:59', strtotime("+3 day", strtotime($Strtime)));
            $time4 = date('Y-m-d ', strtotime("+3 day", strtotime($Strtime)));
            if($time4>$today){
                $arr[$i]["money4s"]='';
            }else {
              /*  $arr[$i]["money4"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime4' and pay_time<='$Endtime4' and register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime4' and start_time<='$Endtime4'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num4"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime4' and start_time<='$Endtime4'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime4'";
                $arr[$i]["money4"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money4s"] = round($arr[$i]["money4"] / $arr[$i]["num"], 2);
            }
            // day 5
            $Strtime5 = date('Y-m-d 00:00:00', strtotime("+4 day", strtotime($Strtime)));
            $Endtime5 = date('Y-m-d 23:59:59', strtotime("+4 day", strtotime($Strtime)));
            $time5 = date('Y-m-d ', strtotime("+4 day", strtotime($Strtime)));
            if($time5>$today){
                $arr[$i]["money5s"]='';
            }else{
              /*  $arr[$i]["money5"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime5' and pay_time<='$Endtime5' and register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime5' and start_time<='$Endtime5'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num5"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime5' and start_time<='$Endtime5'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime5'";
                $arr[$i]["money5"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money5s"]=round($arr[$i]["money5"]/$arr[$i]["num"],2);
            }
            //day 6
            $Strtime6 = date('Y-m-d 00:00:00', strtotime("+5 day", strtotime($Strtime)));
            $Endtime6 = date('Y-m-d 23:59:59', strtotime("+5 day", strtotime($Strtime)));
            $time6 = date('Y-m-d ', strtotime("+5 day", strtotime($Strtime)));
            if($time6>$today){
                $arr[$i]["money6s"]='';
            }else{
                /*$arr[$i]["money6"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime6' and pay_time<='$Endtime6' and register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime6' and start_time<='$Endtime6'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num6"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime6' and start_time<='$Endtime6'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime6'";
                $arr[$i]["money6"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money6s"]=round($arr[$i]["money6"]/$arr[$i]["num"],2);
            }

            // day 7
            $Strtime7 = date('Y-m-d 00:00:00', strtotime("+6 day", strtotime($Strtime)));
            $Endtime7 = date('Y-m-d 23:59:59', strtotime("+6 day", strtotime($Strtime)));
            $time7 = date('Y-m-d ', strtotime("+6 day", strtotime($Strtime)));
            if($time7>$today){
                $arr[$i]["money7s"]='';
            }else{
               /* $arr[$i]["money7"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime7' and pay_time<='$Endtime7' and register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime7' and start_time<='$Endtime7'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num7"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime7' and start_time<='$Endtime7'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime7'";
                $arr[$i]["money7"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money7s"]=round($arr[$i]["money7"]/$arr[$i]["num"],2);
            }

            //day 30
            $Strtime30 = date('Y-m-d 00:00:00', strtotime("+29 day", strtotime($Strtime)));
            $Endtime30 = date('Y-m-d 23:59:59', strtotime("+29 day", strtotime($Strtime)));
            $time30 = date('Y-m-d ', strtotime("+29 day", strtotime($Strtime)));
            if($time30>$today){
                $arr[$i]["money30s"]='';
            }else{
              /*  $arr[$i]["money30"] = D("pay")->
                alias('a')->
                join('left join user b on a.user_id=b.game_id')->
                join('left join sign c on a.user_id=c.user_id')->
                where("pay_time>='$Strtime30' and pay_time<='$Endtime30' and register_time>='$Strtime' and register_time<='$Endtime' and start_time>='$Strtime30' and start_time<='$Endtime30'")->
                sum("pay_number");
                //留存个数
                $arr[$i]["num30"]=count(D("user")->
                alias('a')->
                join('left join sign c on a.game_id=c.user_id')->
                where("register_time>='$Strtime' and register_time<='$Endtime'and start_time>='$Strtime30' and start_time<='$Endtime30'")->
                select());*/
                $sql="select sum(pay_number) as sum from pay as a 
                LEFT JOIN (select * from user GROUP BY game_id) as b  
                on a.user_id=b.game_id  
                where  b.register_time>='$Strtime' and b.register_time<='$Endtime'and pay_time>='$Strtime' 
                and pay_time<='$Endtime30'";
                $arr[$i]["money30"] = D("pay")->query($sql)[0]['sum'];
                $arr[$i]["money30s"]=round($arr[$i]["money30"]/$arr[$i]["num"],2);
            }
        }
        $this->assign("arr",$arr);
        $this->display();
    }

}