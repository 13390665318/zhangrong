<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 下午 6:24
 */

namespace Admin\Controller;


class OnlinecountController extends BaseController
{
    public function index(){
        if (isset($_SESSION["game_id"]) && isset($_SESSION["game_name"]) ) {
            $game_id = $_SESSION["game_id"];
            $game_name = $_SESSION["game_name"];
        } else {
            $str = D("game")->where("game_id=1")->find();
            $game_id = 1;
            $game_name = $str["game_name"];
        }
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
        }else{
            $bclothes=0;
            $eclothes=0;
            $db=D("db")->select();
        }
       // var_dump($db);//exit;
        if(isset($_GET["time"])){
            $time=I("get.time");
	   $tnum=24;
        }else{
            $time=date("Y-m-d",time());
   	   $tnum=(int)date("H",time());
        }
        $this->assign("time",$time);
	
        for($j=0;$j<$tnum;$j++){
            if($j<10){
                $f_time="0$j";
            }else{
                $f_time="$j";
            }
            $data[$j]["num"]=0;
            for($i=0;$i<count($db);$i++){
                $db_id=$db[$i]["db_id"];
                $arr[$i]=D("periods")->where("db_id=$db_id and time='$time' and ftime='$f_time'")->sum("num");
                $data[$j]["num"]=$data[$j]["num"]+$arr[$i];
            }
            $data[$j]["ftime"]=$f_time;

        }
	$data= array_reverse($data);
   //    var_dump($data);exit;
        
       
        $result = (array_merge($data));
        $result=json_encode($result);
       
        $this->assign("results",$data);
        $this->assign("result",$result);

        $this->display();
    }
}