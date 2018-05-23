<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 上午 10:18
 */

namespace Home\Controller;


class AccountController extends BaseController
{
    public function index(){
        $game_id = 1;

        // 游戏区/服
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
   /*     $connection=db($game_id,$db_id);
        $Account = M('San_account','',$connection);
        $count      =$Account->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=$Account->limit($Page->firstRow.','.$Page->listRows)->order("uid desc")->select();

        $this->assign("arr",$arr);*/
        $this->display();
    }

        public function add(){
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $game_id=1;
            $connection=db($game_id,$db_id);
            $Account = M('San_account','',$connection);
            $count      =$Account->count();
            $num=I("get.num");
            $prefix=I("get.prefix");
            $start=I("get.start");
            for($i=0;$i<$num;$i++){
                $ac=$start+1+$i;
                $arr["account"]=$prefix.$ac;
                $arr["password"]="1";
                $arr["creator"]="admin";
                $arr["time"]=time();
                //  新增入库
              $r=$Account->add($arr);
              if($r){
                  $nums=0;
              }else{
                  $nums=$r;
                  break;
              }
            }
           echo $nums;

        }
        }
}