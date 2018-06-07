<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 2:12
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class PayMoneyController extends BaseController
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

        $where=null;
        for($i=0;$i<count($db);$i++){
            $name=$db[$i]["db_id"];
            $where = "db_id = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);
    //   var_dump($con);//exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="(".$con.") and time>='$Strtime' and time<='$Endtime'";
            $mdata=M("order")->where($ru)->group('uid')->select();
$data[$i]["num"]=count($mdata);
            $user=$user+$data[$i]["num"];
        }
        for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();
    }
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

        $where=null;
        for($i=0;$i<count($db);$i++){
            $name=$db[$i]["db_id"];
            $where = "db_id = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);
        //   var_dump($con);//exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="(".$con.") and time>='$Strtime' and time<='$Endtime'";
            $data[$i]["num"]=M("order")->where($ru)->sum(amount)/100;
            $user=$user+$data[$i]["num"];
        }
        for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();
    }
}