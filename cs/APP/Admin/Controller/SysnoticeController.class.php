<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 下午 1:53
 */

namespace Admin\Controller;


class SysnoticeController extends BaseController
{
    public function index(){
        $count      =D("sysnotice")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("sysnotice")->order("sysnotice_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        //   var_dump($arr);
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        if(isset($_POST["sub"])){
            $arr["clothes"]=I("post.clothes");
            $arr["type"]=I("post.type");
            $arr["begin_time"]=I("post.begin_time");
            $arr["end_time"]=I("post.end_time");
            $arr["systime"]=I("post.systime");
            $arr["content"]=$_POST["content"];
            $arr["status"]=1;
            $arr["time"]=date("Y-m-d H:i:s",time());
            $rus=D("sysnotice")->add($arr);
            if($rus !=null){
		 $userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$rus."的系统通知";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Sysnotice/index"));
            }else{
                $this->error("新增失败", U("Sysnotice/add"));
            }
        }else{
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["snotice1"];
            if($user2==1) {
                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("arr", $clostu);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Sysnotice/index"));
            }
        }
    }
    public function edit(){
        if (isset($_SESSION["game_id"]) && isset($_SESSION["game_name"])) {
            $game_id = $_SESSION["game_id"];
            $game_name = $_SESSION["game_name"];
            $mobile_type = $_SESSION["mobile_type"];
        } else {
            $str = D("game")->where("game_id=1")->find();
            $game_id = 1;
            $game_name = $str["game_name"];
            $mobile_type = "Andriod";
        }
        if(isset($_POST["sysnotice_id"])){
            $sysnotice_id=I("post.sysnotice_id");
            $arr["clothes"]=I("post.clothes");
            $arr["type"]=I("post.type");
            $arr["begin_time"]=I("post.begin_time");
            $arr["end_time"]=I("post.end_time");
            $arr["systime"]=I("post.systime");
            $arr["content"]=$_POST["content"];

            $rus=D("sysnotice")->where("sysnotice_id=$sysnotice_id")->save($arr);;
            if($rus ==1){
                //  $this->redirect('Reward/index');
$userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$sysnotice_id."的系统通知";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Sysnotice/index"));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("修改失败", U("Sysnotice/edit",array("id"=>$sysnotice_id)));
            }
        }else if(isset($_GET["id"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["snotice3"];
            if($user2==1) {
                $sysnotice_id = I("get.id");

                $arr = D("sysnotice")->where("sysnotice_id=$sysnotice_id")->find();
                $this->assign("arr", $arr);
                $clothes = $arr["clothes"];
                $this->assign("clothes", $clothes);

                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("clostu", $clostu);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Sysnotice/index"));
            }

        }

    }
    public function del(){

        if(isset($_GET["ids"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["snotice2"];
            if($user2==1) {
                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
                $M = D("sysnotice"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $replay_id = $str[$i];
                    if ($replay_id != null) {
                        $num = $M->where("sysnotice_id=$replay_id")->delete();
                        if ($num == 1) {
$userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="删除id为".$sysnotice_id."的系统通知";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                            $nums = 1;
                        } else {
                            $nums = 0;
                        }
                    }
                }
                echo $nums;
            }else{
                echo -2;
            }

        }
    }

}