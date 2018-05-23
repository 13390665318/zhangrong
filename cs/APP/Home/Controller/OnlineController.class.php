<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9 0009
 * Time: 下午 4:19
 */

namespace Home\Controller;


class OnlineController extends BaseController
{
    //平均在线时长   总时长 / 总活跃数   7 天
    public function index(){
        $game_id = 1;
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
//$_SESSION["db_id"]=1;
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

        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);

        $num=count_days($stime,$etime);
        if($num>30){$num=30;}
//echo $num;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=strtotime(date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime))));
            $arr[$i]["time2"]=strtotime(date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime))));
        }
	$connection=db($game_id,$db_id);
        for($i=0;$i<count($arr);$i++){
            $data[$i]["times"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']=" time>=$Strtime and time<=$Endtime";

            //var_dump($connection);exit;
            $sum=M('san_linelog','',$connection)->where($ru)->field("uid")->group("uid")->select();
//echo M('san_linelog','',$connection)->getLastSql()."<br/>";
            $data[$i]["people"]=count($sum);
            $sum2=M('san_linelog','',$connection)->where($ru)->sum("line");
//echo $sum2." ";
            $data[$i]["time"]=round($sum2/3600,4);
            $data[$i]["num"]=round( $data[$i]["time"]/$data[$i]["people"],2);


        }
//var_dump($data);exit;;

        $Stime=substr($Stime,0,strlen($Stime)-1);
//echo $Stime;
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();
    }

    public function index2(){
        $game_id = 1;
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
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
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $num=count_days($stime,$etime);
        if($num>30){$num=30;}

        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=strtotime(date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime))));
            $arr[$i]["time2"]=strtotime(date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime))));
        }


        for($i=0;$i<count($arr);$i++){
            $data[$i]["times"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="time>=$Strtime and time<=$Endtime";
            $data[$i]["num"]=0;
            $connection=db($game_id,$db_id);
            //$sum=M('san_linelog','',$connection)->where($ru)->field("uid")->group("uid")->select();
            $data[$i]["people"]=M('san_linelog','',$connection)->where($ru)->count();
            $sum2=M('san_linelog','',$connection)->where($ru)->sum("line");
            $data[$i]["time"]=round($sum2/3600,2);
            $data[$i]["num"]=round( $data[$i]["time"]/$data[$i]["people"],2);


        }



        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();
    }
    // 平均在线时长区间分布
    // 0-30    30-60  60-120  120-299  300+
    //select uid,sum(line)  from san_linelog group by uid;
    public function index3(){
        $game_id = 1;
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        // echo D("db")->getLastSql();
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
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{;
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $Strtime=strtotime (date("Y-m-d 00:00:00",strtotime($stime)));
        $Endtime=strtotime (date("Y-m-d 23:59:59",strtotime($stime)));
        $connection=db($game_id,$db_id);
        //0-30
        $arr=array();
        $arr[0]["num"]=0;
        $arr[0]["nums"]="0-30 分钟";
        $arr[1]["num"]=0;
        $arr[1]["nums"]="30-60 分钟";
        $arr[2]["num"]=0;
        $arr[2]["nums"]="60-120 分钟";
        $arr[3]["num"]=0;
        $arr[3]["nums"]="120-300 分钟";
        $arr[4]["num"]=0;
        $arr[4]["nums"]="300 分钟以上";
        $data=M('san_linelog','',$connection)->where("time>$Strtime and time <$Endtime ")->field("uid,sum(line)")->field("uid,sum(line)")->group("uid")->select();

       for($i=0;$i<count($data);$i++){
           if($data[$i]["sum(line)"]<1800){
               $arr[0]["num"]++;
           }else if($data[$i]["sum(line)"]>=1800 and $data[$i]["sum(line)"]<3600){
               $arr[1]["num"]++;
           }else if($data[$i]["sum(line)"]>=3600 and $data[$i]["sum(line)"]<7200){
               $arr[2]["num"]++;
           }else if($data[$i]["sum(line)"]>=7200 and $data[$i]["sum(line)"]<18000){
               $arr[3]["num"]++;
           }else if($data[$i]["sum(line)"]>=18000){
               $arr[4]["num"]++;
           }
       }
        $this->assign("arr",$arr);
        $jsoBj=json_encode($arr);
        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
}