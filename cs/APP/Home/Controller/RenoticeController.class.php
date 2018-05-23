<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 下午 7:12
 */

namespace Admin\Controller;

//Renotice
class RenoticeController extends  BaseController
{
    public function index(){
        $count      =D("notice_replay")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("notice_replay")->order("replay_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if(isset($_POST["sub"])){

            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $arr["status"]=0;
            $arr["time"]=date("Y-m-d H:i:s",time());
            $rus=D("notice_replay")->add($arr);
            if($rus !=null){
$userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$rus."的公告配置";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Renotice/index"));
            }else{
                $this->error("新增失败", U("Renotice/add"));
            }
        }else{
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["rnotice1"];
            if($user2==1) {
                $this->display();
            }else{
                $this->error('没有操作权限',U("Renotice/index"));
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
        if(isset($_POST["replay_id"])){
            $replay_id=I("post.replay_id");
            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $rus=D("notice_replay")->where("replay_id=$replay_id")->save($arr);;
            if($rus ==1){
$userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$replay_id."的公告配置";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Renotice/index"));
            }else{
                $this->error("修改失败", U("Renotice/edit",array("id"=>$replay_id)));
            }
        }else if(isset($_GET["id"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["rnotice3"];
            if($user2==1) {
                $replay_id = I("get.id");
                $arr = D("notice_replay")->where("replay_id=$replay_id")->find();
                $this->assign("arr", $arr);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Renotice/index"));
            }

        }

    }

    public function del(){

        if(isset($_GET["ids"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["rnotice2"];
            if($user2==1) {
                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
                $M = D("notice_replay"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $replay_id = $str[$i];
                    if ($replay_id != null) {
                        $num = $M->where("replay_id=$replay_id")->delete();
                        if ($num == 1) {
		  $userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="删除id为".$replay_id."的公告配置";
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