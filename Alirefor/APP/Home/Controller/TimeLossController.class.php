<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9 0009
 * Time: 下午 3:08
 */

namespace Home\Controller;


class TimeLossController extends  BaseController
{
    public function index(){
        if (isset($_SESSION["game_id"])) {
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
      //  $_GET["time"]="2017-06-08";
        if(isset($_GET["time"])){
            $time=I("get.time");
        }else{
            $time=date("Y-m-d",time());
        }
        $this->assign("time",$time);
if($_GET){
        $SumNumber=0;  // 流失总人数
        // 查找 注册未取名用户
        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $arr0[0]["name"]="注册但未取名";
        $arr0[0]["num"]=D("user")->where("register_time>'$stime' and register_time<='$etime' and game_user_name=''")->count();
        $SumNumber=$SumNumber+$arr0[0]["num"];
        $DayTime=date("Y-m-d H:i:s",time());
        //5 分钟以内
        for($i=1;$i<6;$i++){
            $Kuntime=($i-1)*60;
            $Sumtime=$i*60;
            $arr1[$i]["name"]=$i."分钟";
            $arr1[$i]["num"]=D("user")->where("register_time>'$stime' and register_time<='$etime' and game_id=$game_id and db_id=$db_id and user_time>='$Kuntime' and user_time<='$Sumtime' and game_id=$game_id and db_id=$db_id")->count();
            $SumNumber=$SumNumber+$arr1[$i]["num"];
        }
        // 10 -50 分钟
        for($i=1;$i<6;$i++){
            if($i==1){
                $Kuntime=300;
                $Sumtime=$i*60*10;
            }else{
                $Kuntime=($i-1)*60*10;
                $Sumtime=$i*60*10;
            }
            $arr2[$i]["name"]=$i."0分钟";
            $arr2[$i]["num"]=D("user")->where("register_time>'$stime' and register_time<='$etime' and game_id=$game_id and db_id=$db_id and user_time>='$Kuntime' and user_time<='$Sumtime'")->count();
            $SumNumber=$SumNumber+$arr2[$i]["num"];
        }
        // 24小时
        for($i=1;$i<=24;$i++){
            if($i==1){
                $Kuntime=3000;
                $Sumtime=$i*60*60;
            }else{
                $Kuntime=($i-1)*60*60;
                $Sumtime=$i*60*60;
            }
            $arr3[$i]["name"]=$i."小时";
            $arr3[$i]["num"]=D("user")->where("register_time>'$stime' and register_time<='$etime' and game_id=$game_id and db_id=$db_id and user_time>='$Kuntime' and user_time<='$Sumtime'")->count();
            $SumNumber=$SumNumber+$arr3[$i]["num"];
        }
        $arr=(array_merge($arr0,$arr1,$arr2,$arr3));
        for($i=0;$i<count($arr);$i++){
            $stu[$i]["name"]=$arr[$i]["name"];
            $stu[$i]["num"]=$arr[$i]["num"];
            $stu[$i]["%"]=round($arr[$i]["num"]/$SumNumber,2)*100;
        }
        $this->assign("stu",$stu);
        $this->display();
}else{
$this->display();
}
    }
}