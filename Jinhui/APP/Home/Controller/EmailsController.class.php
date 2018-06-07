<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29 0029
 * Time: 下午 4:49
 */

namespace Home\Controller;


class EmailsController extends BaseController
{
    public function index(){
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
        //var clothes=$("#clothes").val();   区服
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            $db_id=$clostu[0]["db_id"];
            $db_id= $_SESSION["db_id"];
        }
        $con["clothes"]=$db_id;
        $this->assign("db_id",$db_id);
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");
        }else{
            $Stime=date("Y-m-01 00:00:00",time());
            $Etime=date("Y-m-d H:i:s",time());
        }
        
        $con["_string"]="time>='$Stime' AND time<='$Etime'";
        $this->assign("Stime",$Stime);
        $this->assign("Etime",$Etime);

        $con=array_filter($con);
	//var_dump($con);
        $count      = M("email")->where($con)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=M("email")->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
	 for($i=0;$i<count($arr);$i++){
	$db_id=$arr[$i]["clothes"];
         $connection=db2($game_id,$db_id);
//var_dump($connection);
          $game_user=$arr[$i]["game_user_ids"];
          $user=explode(',',$game_user);
	 
          for($j=0;$j<count($user);$j++){
              if($user[$j]!=null){
                  $uid=$user[$j];
		
                  $ru=M('user','',$connection)->where("game_user_id=$uid")->find();
//var_dump($ru);
                  $arr[$i]["uname"]=$ru["game_user_name"];
              }
          }
            $goods=$arr[$i]["goods_ids"];
            $r=explode(';',$goods);
            for($j=0;$j<count($r);$j++){
                if($r[$j]!=null){
                    $rus=explode(':',$r[$j]);
                    $itemid=$rus[0];
                    $stu=D("goods")->where("itemid=$itemid")->find();
                     $arr[$i]["goods_name"].=$stu["itemname"].":".$rus[1].",";
                }
            }
        }


        $this->assign("arr",$arr);
        $this->display();


    }
	public function index3(){
        $count      =D("email")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("email")->order("email_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
	$game_id=1;
        for($i=0;$i<count($arr);$i++){
	$db_id=$arr[$i]["clothes"];
         $connection=db2($game_id,$db_id);
//var_dump($connection);
          $game_user=$arr[$i]["game_user_ids"];
          $user=explode(',',$game_user);
	 
          for($j=0;$j<count($user);$j++){
              if($user[$j]!=null){
                  $uid=$user[$j];
		
                  $ru=M('user','',$connection)->where("game_user_id=$uid")->find();
//var_dump($ru);
                  $arr[$i]["uname"]=$ru["game_user_name"];
              }
          }
            $goods=$arr[$i]["goods_ids"];
            $r=explode(';',$goods);
            for($j=0;$j<count($r);$j++){
                if($r[$j]!=null){
                    $rus=explode(':',$r[$j]);
                    $itemid=$rus[0];
                    $stu=D("goods")->where("itemid=$itemid")->find();
                    $arr[$i]["goods_name"].=$stu["itemname"].",";
                }
            }
        }
        $this->assign("arr",$arr);
        $this->display();
    }



}