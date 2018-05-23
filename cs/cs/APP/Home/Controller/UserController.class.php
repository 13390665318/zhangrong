<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 下午 2:12
 */

namespace Home\Controller;


class UserController extends BaseController
{

    public function index(){
        $M=D("admin");
        $count      = $M->where("role=1")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $list = $M->where("role=1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        for($i=0;$i<count($list);$i++){
            $ru=$list[$i]["auth"];
           
		  $rus=D("admin_auth_type")->where("id=$ru")->find();
                    $list[$i]["auto_name"]=$rus["name"];
             
        }
	//var_dump($list);
        $this->assign("list",$list);// 赋值数据集
        $this->assign("page",$show);// 赋值分页输出
        $this->display();
    }

    public function add(){




        if(isset($_POST["sub"])){
//var_dump($_POST);//exit;
            $arr=array();
            $arr["user_name"]=I("post.user_name");
            $arr["name"]=I("post.name");
            $arr["user"]=I("post.user");
            $arr["password"]=I("post.password");


            $arr["auth"]=I("post.auth");
            $arr["role"]=1;
            $arr["status"]=1;
            $arr["time"]=date("Y-m-d H:i:s",time());


            $num=D("admin")->add($arr);
            if($num!=null){
                $userid=$_SESSION["userID"];
                $rus=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$rus["name"];
                $Rlog["account"]=$rus["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="增加id为".$num."的后台管理用户";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success('操作成功',U("User/index"));
            }else{
                $this->error('操作失败',U("User/add"));
            }

        }else{
                $arr=D("admin_auth_type")->order('id desc')->select();
                $this->assign('arr', $arr);

                $this->display();

        }
    }



    public function del(){
        $id=$_SESSION["userID"];
        $rus=D('admin')->where("id=$id")->find();
        $user2=$rus["user2"];
        if($user2==1) {
            if (isset($_GET["ids"])) {
                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
                $M = D("admin"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $id = $str[$i];
                    if ($id != null) {
                        if ($id == 1) {
                            $nums = -1;
                        } else {
                            $num = $M->where("id=$id")->delete();
                            if ($num == 1) {
                                $userid=$_SESSION["userID"];
                                $rus=D('admin')->where("$userid=$userid")->find();
                                $Rlog["user"]=$rus["name"];
                                $Rlog["account"]=$rus["user_name"];
                                $Rlog["ip"]=get_client_ip();
                                $Rlog["doc"]="删除id为".$id."的后台管理用户";
                                $Rlog["time"]=date("Y-m-d H:i:s",time());
                                $r=D("rlog")->add($Rlog);

                                $nums = 1;
                            } else {
                                $nums = 0;
                            }

                        }
                    }
                }
                echo $nums;

            }
        }else{
            echo -2;
        }
    }

    public function ksea(){
        if(isset($_GET["id"])){
            $id=I("get.id");
            $arr["status"]=I("get.type");
            //   echo I("get.type");
            $M=D("admin");
            $num=$M->where("id=$id")->save($arr);
            if($num==1){
                echo 1;
            }else{
                echo 0;
            }
        }
    }
    public function edit(){
        if(isset($_POST["id"])){
            $id=I("post.id");
            $arr["user_name"]=I("post.user_name");
            $arr["password"]=I("post.password");
            $arr["status"]=I("post.status");
            $arr["auth"]=I("post.auth");

            $M=D("admin");
            $num=$M->where("id=$id")->save($arr);
            if($num==1){
                $userid=$_SESSION["userID"];
                $rus=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$rus["name"];
                $Rlog["account"]=$rus["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="修改id为".$id."的后台管理用户";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U('User/index'));
            }else{
                $this->error("修改失败",U('User/index'));
            }
        }else {
            $arr=D("admin_auth_type")->order('id desc')->select();
                $id = I("get.id");
                $M = D("admin");
                $list = $M->where("id=$id")->find();
                $this->assign('arr', $arr);
                $this->assign("list", $list);
                $this->display();


        }
    }


}