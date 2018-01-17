<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26 0026
 * Time: 下午 2:25
 */

namespace Home\Controller;


class CheckpointController extends BaseController
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
//var_dump( $db_id);exit;
        $this->assign("db_id",$db_id);

        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d 00:00:00",time());
            $etime=date("Y-m-d H:i:s",time());
        }
if($_GET){
        $start_time=strtotime($stime);
        $end_time=strtotime($etime);
        $sum=0;


            $connection=db($game_id,$db_id);
//var_dump($connection);
            $San_log = M('San_log','',$connection);
	
$sum=$San_log->where("time>='$start_time' and time<'$end_time' and type=1")->distinct(true)->field('uid')->select();

$sum= count($sum);

            // 查询所有关卡
            $data=$San_log->where("time>='$start_time' and time<'$end_time' and type=1")->order("value desc")->field("value")->group("value")->select();
         //  var_dump($data);exit;
 for($j=0;$j<count($data);$j++){
                $arr[$j]["value"]=$data[$j]["value"];
                $zhang= substr($data[$j]["value"],2,2)+1;
                $jie= substr($data[$j]["value"],4,2);
                $arr[$j]["pass"]="第".$zhang."章 第".$jie."节"; // 当前章节 san_pass
                $value=$data[$j]["value"];
                $num=$San_log->where("time>='$start_time' and time<'$end_time' and type=1 and value='$value'")->count();
                $arr[$j]["num"]=$num;
           //     $sum=$sum+$num;

            }





        for($i=0;$i<count($arr);$i++){
            $arr[$i]["nums"]=round($arr[$i]["num"]/$sum,4)*100;
        }
        $sort = array_column($arr, 'value');

        array_multisort($sort, SORT_DESC, $arr);

       
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $this->assign("sum",$sum);
        $this->assign("arr",$arr);
        $this->display();
}else{
 $this->assign("stime",$stime);
        $this->assign("etime",$etime);
     
        $this->display();
}
    }
}