<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 4:13
 * 用户注册来源
 */

namespace Admin\Controller;


class RegisteController extends  BaseController
{
    public function index(){
        if (isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if($bclothes==0 & $eclothes==0){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $bclothes=0;
            $eclothes=0;
            $db=D("db")->select();
        }

        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-01 00:00:00",time());
            $etime=date("Y-m-d H:i:s",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $sum=0;
        for($i=0;$i<count($db);$i++){
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
            $user_count = M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and db_id=$db_id")->count();
            $sum=$sum+$user_count;  // 总人数
            // 平台 分类
            $stu =  M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
          // var_dump($stu);
            for($j=0;$j<count($stu);$j++){
                $arr[$i][$j]["name"]=$stu[$j]["source"];
                $source=$stu[$j]["source"];
                $arr[$i][$j]["count"]=M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and source='$source'")->count();

            }
        }
      //  echo $sum."<br/>";
        $newArr = array();
        foreach($arr as $value){
            foreach($value as $v){
                $newArr[]=$v;
            }
        }

        $item=array();
        foreach($newArr as $k=>$v){
            if(!isset($item[$v['name']])){
                $item[$v['name']]=$v;
            }else{
                $item[$v['name']]['count']+=$v['count'];

            }
        }
       $data=array_values($item);
        for($i=0;$i<count($data);$i++){
            $data[$i]["num"]=round($data[$i]["count"]/$sum,4)*100;
        }




        $this->assign("sum",$sum);
        $this->assign("arr",$data);

        $this->display();
    }

    public function lists(){
        if (isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if($bclothes==0 & $eclothes==0){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $bclothes=0;
            $eclothes=0;
            $db=D("db")->select();
        }

        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-01 00:00:00",time());
            $etime=date("Y-m-d H:i:s",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $sum=0;
        for($i=0;$i<count($db);$i++){
            $db_id=$db[$i]["db_id"];
            $connection=db2($game_id,$db_id);
            $user_count = M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and db_id=$db_id")->count();
            $sum=$sum+$user_count;  // 总人数
            // 平台 分类
            $stu =  M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
            // var_dump($stu);
            for($j=0;$j<count($stu);$j++){
                $arr[$i][$j]["name"]=$stu[$j]["source"];
                $source=$stu[$j]["source"];
                $arr[$i][$j]["count"]=M('user','',$connection)->where("register_time>='$stime' and register_time<'$etime' and source='$source'")->count();

            }
        }
        //  echo $sum."<br/>";
        $newArr = array();
        foreach($arr as $value){
            foreach($value as $v){
                $newArr[]=$v;
            }
        }

        $item=array();
        foreach($newArr as $k=>$v){
            if(!isset($item[$v['name']])){
                $item[$v['name']]=$v;
            }else{
                $item[$v['name']]['count']+=$v['count'];

            }
        }
        $data=array_values($item);
        for($i=0;$i<count($data);$i++){
            $data[$i]["num"]=round($data[$i]["count"]/$sum,4)*100;
        }




        $this->assign("sum",$sum);
        $this->assign("arr",$data);

        $this->display();
    }

}