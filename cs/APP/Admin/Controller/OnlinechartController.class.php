<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 上午 11:57
 */

namespace Admin\Controller;


class OnlinechartController extends BaseController
{
    public function index(){
        if (isset($_SESSION["game_id"]) && isset($_SESSION["game_name"])) {
            $game_id = $_SESSION["game_id"];

        } else {
            $str = D("game")->where("game_id=1")->find();
            $game_id = 1;
            ;
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
      //  判断时间
        $endtime=strtotime($time);
        $year=date('Y', $endtime);// 年
        $month=date('m', $endtime); // 当前月
        $day=(int)date('d', $endtime);//当前天
        // 当月第一天到现在的时间
          for($i=1;$i<=$day;$i++){
                    if($i<10){
                        $startime="$year-$month-0$i";
                    }else{
                        $startime="$year-$month-$i";
                    }
                    $arr[$i]["time"]=$startime;

                    // 最大值
                $ru=D("period")->where("time='$startime'")->order("num desc")->limit("0,1")->find();
                if($ru==null){
                    $arr[$i]["maxnum"]=0;
                }else{
                    $arr[$i]["maxnum"]=$ru["num"];
                }
                $arr[$i]["maxtime"]=$ru["time"]." ".$ru["f_time"];

                    // 最小值
              $rus=D("period")->where("time='$startime'")->order("num asc")->limit("0,1")->find();
              if($rus==null){
                  $arr[$i]["muxnum"]=0;
              }else{
                  $arr[$i]["muxnum"]=$rus["num"];
              }
              $arr[$i]["muxtime"]=$rus["time"]." ".$rus["f_time"];
              $arr[$i]["avnum"]=(int)(((int)$ru["num"]+(int)$rus["num"])/2);
            }

           $result = (array_merge($arr));
           $result=json_encode($result);
            $arr= array_reverse($arr);
        $this->assign("arr",$arr);
        $this->assign("result",$result);
        $this->display();
    }

}