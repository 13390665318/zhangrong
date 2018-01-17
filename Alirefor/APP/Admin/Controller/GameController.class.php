<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/6 0006
 * Time: 下午 4:53
 */

namespace Admin\Controller;


class GameController extends  BaseController
{
    public function index(){
        $count      =  D("game")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $list = D("game")->order('game_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("list",$list);// 赋值数据集
        $this->assign("page",$show);// 赋值分页输出
        $this->display();

    }
    public function del(){
          if(isset($_GET["ids"])){
              $id=$_SESSION["userID"];
              $rus=D('admin')->where("id=$id")->find();
              $game2=$rus["game2"];
              if($game2==1) {
                $ids=I("get.ids");
            $arr=array();
            $str = explode(',',$ids);
            $M = D("game"); // 实例化User对象
            for($i=0;$i<count($str);$i++){
                $id=$str[$i];
                if($id!=null){
                     $num=$M->where("game_id=$id")->delete();
                }
                }
		$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("id=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="删除id为".$id."的游戏列表";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
            	echo $num;
            }else{
                  echo -1;
              }}
    }
    public function add(){
        if(isset($_POST["sub"])){
            $arr=array();
            $arr["game_name"]=I("post.game_name");
            $arr["status"]=I("post.status");
            $arr["time"]=date("Y-m-d H:i:s",time());
            $arr["user_name"]=$_SESSION["username"];
            $num=M("game")->add($arr);
            if($num!=null){
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("id=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$num."的游戏列表";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Game/index"));
            }else{
                $this->error("新增失败",U("Game/add"));
            }


        }else{
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $game1=$rus["game1"];
            if($game1==1) {
                $this->display();
            }else{
                $this->error('没有操作权限',U("Game/index"));
            }
        }
    }
    public function edit(){
        if(isset($_POST['id'])){
            $arr=array();
            $arr["game_name"]=I("post.game_name");
            $arr["status"]=I("post.status");
            $game_id=I("post.id");
            $num=M("game")->where("game_id=$game_id")->save($arr);
            if($num==1){
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("id=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$game_id."的游戏列表";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Game/index"));
            }else{
                $this->error("修改失败",U("Game/add"));
            }


        }else{
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $game3=$rus["game3"];
            if($game3==1) {
                $id = I("get.id");
                $this->assign("id", $id);
                //查询所有公司信息
                $M = D("game");
                $list = $M->where("game_id=$id")->find();

                $this->assign("arr", $list);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Game/index"));
            }
        }
    }

}