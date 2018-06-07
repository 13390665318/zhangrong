<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 下午 4:30
 */

namespace Home\Controller;


class LevelSaveController extends BaseController
{
    public function index(){
        $game_id = 1;
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
        if(isset($_GET["stime"])){
            $stime=I("get.stime");

        }else{
            // 默认 当天
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $connection=db2($game_id,$db_id);
        // 开始 结束时间
        $start_time=date('Y-m-d 00:00:00',  strtotime ("-14 day", strtotime($stime)));
        $end_time=date('Y-m-d 23:59:59', strtotime($stime));
        //查询所以等级
        $stu=null;
        $connection=db2($game_id,$db_id);
        $sum=0;
        $arr=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$start_time' and b.start_time<='$end_time' and a.register_time>='$start_time' and a.register_time<='$end_time'")->order("a.level desc")->distinct(true)->field('a.level')->select();
        $sum=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$start_time' and b.start_time<='$end_time' and a.register_time>='$start_time' and a.register_time<='$end_time' ")->distinct(true)->field('a.game_user_id')->count();

        for($i=0;$i<count($arr);$i++){
            $level=$arr[$i]["level"];
            $data[$i]["level"]=$level;
            $stu='" '.$level.'" '.",".$stu;
            $data[$i]["num"]=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where(" a.level=$level and b.start_time>='$start_time' and b.start_time<='$end_time' and a.register_time>='$start_time' and a.register_time<='$end_time' ")->count();
            $data[$i]["nums"]=round($data[$i]["num"]/$sum,4)*100;
        }



        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("stu",$stu);
        $this->display();
    }
}