<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 6:45
 */

namespace Home\Controller;


class UserPayController extends BaseController
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
   // N日付费率    活跃用户里的付费用户的占比
    public function index2(){

        // 游戏区/服id =
        $clostu=D("db")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if($db_id!=0){
            $ru2['db_id']=$db_id;
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
        $day=count_days($stime,$etime);
        $Stime=null;
        for($i=0;$i<=$day;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;  // 时间
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            // 活跃用户
            $Strtime=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
            $ru2['_string']="start_time>='$Strtime' and start_time<='$Endtime'";//活跃用户
            $sum2=D("sign")->where($ru2)->group('user_id')->select();
            $arr[$i]["snum"]=count($sum2);
            //付费用户
            $ru3["_string"]="pay_time>='$Strtime' and pay_time<='$Endtime'";//充值用户
            $pay_user =D("pay")->where($ru3)->group('user_id')->select(); // 今日充值用户
        
            $arr[$i]["pnum"]=count($pay_user);
            // 付费率
            $arr[$i]["num"]=round($arr[$i]["pnum"]/ $arr[$i]["snum"],4)*100;
        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$arr);
        $jsoBj=json_encode($arr);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();


    }

 // N日ARPPU  付费用户的平均付费金额
    public function index3(){
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
            $ru3['db_id']=$db_id;
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
        $day=count_days($stime,$etime);
        $Stime=null;
        for($i=0;$i<=$day;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;  // 时间
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

            $Strtime=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
            //付费用户
            $ru3["_string"]="pay_time>='$Strtime' and pay_time<='$Endtime' ";//充值用户
            $pay_user =D("pay")->where($ru3)->group('user_id')->select(); // 充值账号
            $payrole_user =D("pay")->where($ru3)->group('game_user_id')->select(); // 充值角色
            $arr[$i]["pnum"]=count($pay_user);
            $arr[$i]["prnum"]=count($payrole_user);
            //付费金额
            $arr[$i]["money"]=D("pay")->where($ru3)->sum(pay_number); // 今日付费总金额
            // ARPPU
            $arr[$i]["num"]=round($arr[$i]["money"]/ $arr[$i]["pnum"],4);
            $arr[$i]["numr"]=round($arr[$i]["money"]/ $arr[$i]["prnum"],4);
        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$arr);
        $jsoBj=json_encode($arr);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();


    }


 // N日ARPU 活跃用户的平均付费金额
    public function index4(){
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
            $ru3['db_id']=$db_id;
        }
        // 图标 默认 最新服
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
        $day=count_days($stime,$etime);
        $Stime=null;
        for($i=0;$i<=$day;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;  // 时间
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

            $Strtime=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
            //付费用户
            $ru3["_string"]="start_time>='$Strtime' and start_time<='$Endtime' ";//充值用户
            $pay_user =D("sign")->where($ru3)->group('user_id')->select(); // 今日活跃账号
            $role_user =D("sign")->where($ru3)->group('game_user_id')->select(); // 今日活跃账号
            $arr[$i]["pnum"]=count($pay_user);//活跃账号数量
            $arr[$i]["prnum"]=count($role_user);//活跃角色数量
            //付费金额
            $arr[$i]["money"]=D("pay")->where("LogTime>='$Strtime' and LogTime<='$Endtime'")->sum(pay_number); // 今日付费总金额
            // ARPU
            $arr[$i]["num"]=round($arr[$i]["money"]/ $arr[$i]["pnum"],4);//账号ARPU
            $arr[$i]["rnum"]=round($arr[$i]["money"]/ $arr[$i]["prnum"],4);//角色ARPU
        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$arr);
        $jsoBj=json_encode($arr);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();


    }

}