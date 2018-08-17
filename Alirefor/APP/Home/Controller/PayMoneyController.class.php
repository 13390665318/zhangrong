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
        $game_id = 2;
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if($db_id!=0){
            $ru['db_id']=$db_id;
        }
        $nowtime = date("Y-m-d H:i:s", time());
        if (isset($_GET["stime"]) && isset($_GET["etime"])) {
            $stime = I("get.stime");
            $etime = I("get.etime");
        } else {
            $stime = date("Y-m-d ", strtotime("-6 day"));
            $etime = date("Y-m-d ", time());
        }


        $this->assign('stime',$stime);
        $this->assign('etime',$etime);
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
            $ru['_string']=" pay_time>='$Strtime' and pay_time<='$Endtime'";
            $mdata=M("pay")->where($ru)->group('user_id')->select();
            $data[$i]["num"]=count($mdata);
            $user=$user+$data[$i]["num"];
        }
        //dump($user);exit;
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
        $game_id = 2;
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if($db_id!=0){
            $ru['db_id']=$db_id;
        }
        $nowtime = date("Y-m-d H:i:s", time());
        if (isset($_GET["stime"]) && isset($_GET["etime"])) {
            $stime = I("get.stime");
            $etime = I("get.etime");
        } else {
            $stime = date("Y-m-d ", strtotime("-6 day"));
            $etime = date("Y-m-d ", time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);

        $where=null;


        $num=count_days($stime,$etime);
        //   var_dump($con);//exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
       // dump($con);exit;
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="pay_time>='$Strtime' and pay_time<='$Endtime'";
            $data[$i]["num"]=(int)M("pay")->where($ru)->sum(pay_number);
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