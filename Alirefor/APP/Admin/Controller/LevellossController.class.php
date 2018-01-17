<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 5:43
 */

namespace Admin\Controller;


class LevellossController extends BaseController
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
        if(isset($_GET["time"])){
            $time=I("get.time");
        }else{
            $time=date("Y-m-d",time());
        }
        $this->assign("time",$time);
        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $endtime=date("Y-m-d H:i:s",time());
        // 计算 总人数，流失总人数
        $num=D("user")->where("register_time>='$stime' and register_time<'$etime' and game_id=$game_id and db_id='$db_id'")->count();
        $loss=D("user")->where("register_time>='$stime' and register_time<'$etime' and end_time<='$endtime'  and game_id=$game_id and db_id='$db_id'")->count();
        $loss_num=round($loss/$num, 2)*100;
        $this->assign("num",$num);
        $this->assign("loss",$loss);
        $this->assign("loss_num",$loss_num);
        $stu = D("user")->where("register_time>='$stime' and register_time<'$etime' and game_id=$game_id and db_id='$db_id'")->group("level")->order("level desc")->select();
        for($i=0;$i<count($stu);$i++){
            $arr[$i]["level"]=$stu[$i]["level"];
            $level=$stu[$i]["level"];
            // 等级所有人数
            $arr[$i]["num"]=D("user")->where("register_time>='$stime' and register_time<'$etime' and level='$level' and game_id=$game_id and db_id='$db_id'")->count();
            //等级流失人数
            $arr[$i]["loss"]=D("user")->where("register_time>='$stime' and register_time<'$etime' and end_time<='$endtime' and level='$level' and game_id=$game_id and db_id='$db_id'")->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$num,2)*100;
        }
       // var_dump($arr);
        $this->assign("arr",$arr);
        $this->display();
    }
}