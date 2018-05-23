<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 下午 2:12
 */

namespace Admin\Controller;


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
                $arr=explode(",", $ru);

                for($j=0;$j<count($arr);$j++){
                    $id=$arr[$j];
                    if($id!=''){
                        $rus=D("admin_auth_rule")->where("id=$id")->find();
                        $list[$i]["auto_name"]=$rus["title"].','.$list[$i]["auto_name"];
                    }}
            }
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
            $stu["auth"]="1";
            for($i=1;$i<16;$i++){
                if(isset($_POST["auth".$i])){
                    $stu["auth"]=$_POST["auth".$i].','.$stu["auth"];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["auths".$i])){
                    $stu["auths"]=$_POST["auths".$i].','.$stu["auths"];
                }
            }
            for($i=1;$i<8;$i++){
                if(isset($_POST["authss".$i])){
                    $stu["authss"]=$_POST["authss".$i].','.$stu["authss"];
                }
            }
            for($i=1;$i<8;$i++){
                if(isset($_POST["authsss".$i])){
                    $stu["authsss"]=$_POST["authsss".$i].','.$stu["authsss"];
                }
            }
            for($i=1;$i<7;$i++){
                if(isset($_POST["authssss".$i])){
                    $stu["authssss"]=$_POST["authssss".$i].','.$stu["authssss"];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["authsssss".$i])){
                    $stu["authsssss"]=$_POST["authsssss".$i].','.$stu["authsssss"];
                }
            }
            $arr["auth"]=$stu["auth"].$stu["auths"].$stu["authss"]. $stu["authsss"].$stu["authssss"].$stu["authsssss"];
            $arr["role"]=1;
            $arr["status"]=1;
            $arr["time"]=date("Y-m-d H:i:s",time());
            for($i=1;$i<4;$i++){
                if(isset($_POST["user".$i])){
                    $arr["user".$i]=$_POST["user".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["clothes".$i])){
                    $arr["clothes".$i]=$_POST["clothes".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["game".$i])){
                    $arr["game".$i]=$_POST["game".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["sysnotice".$i])){
                    $arr["sysnotice".$i]=$_POST["sysnotice".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["reword".$i])){
                    $arr["reword".$i]=$_POST["reword".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["email".$i])){
                    $arr["email".$i]=$_POST["email".$i];
                }
            }
            for($i=1;$i<6;$i++){
                if(isset($_POST["code".$i])){
                    $arr["code".$i]=$_POST["code".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["snotice".$i])){
                    $arr["snotice".$i]=$_POST["snotice".$i];
                }
            }
            for($i=1;$i<4;$i++){
                if(isset($_POST["rnotice".$i])){
                    $arr["rnotice".$i]=$_POST["rnotice".$i];
                }
            }
       
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

$id=$_SESSION["userID"];
$rus=D('admin')->where("id=$id")->find();
$user1=$rus["user1"];
if($user1==1) {
    $ru1 = D("admin_auth_rule")->where("type=1")->order("type asc")->select();
    $ru2 = D("admin_auth_rule")->where("type=2")->order("type asc")->select();
    $ru3 = D("admin_auth_rule")->where("type=3")->order("type asc")->select();
    $ru4 = D("admin_auth_rule")->where("type=4")->order("type asc")->select();
    $ru5 = D("admin_auth_rule")->where("type=5")->order("type asc")->select();
    $ru6 = D("admin_auth_rule")->where("type=6")->order("type asc")->select();
    $this->assign('ru1', $ru1);
    $this->assign('ru2', $ru2);
    $this->assign('ru3', $ru3);
    $this->assign('ru4', $ru4);
    $this->assign('ru5', $ru5);
    $this->assign('ru6', $ru6);

    $this->display();
}else{
    $this->error('没有操作权限',U("User/index"));
}
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
$arr["auth"]=1;
                for($i=0;$i<52;$i++){
                    if(isset($_POST["auth".$i])){
                        $arr["auth"]=$_POST["auth".$i].','.$arr["auth"];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["user".$i])){
                        $arr["user".$i]=$_POST["user".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["clothes".$i])){
                        $arr["clothes".$i]=$_POST["clothes".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["game".$i])){
                        $arr["game".$i]=$_POST["game".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["sysnotice".$i])){
                        $arr["sysnotice".$i]=$_POST["sysnotice".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["reword".$i])){
                        $arr["reword".$i]=$_POST["reword".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["email".$i])){
                        $arr["email".$i]=$_POST["email".$i];
                    }
                }
                for($i=1;$i<6;$i++){
                    if(isset($_POST["code".$i])){
                        $arr["code".$i]=$_POST["code".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["snotice".$i])){
                        $arr["snotice".$i]=$_POST["snotice".$i];
                    }
                }
                for($i=1;$i<4;$i++){
                    if(isset($_POST["rnotice".$i])){
                        $arr["rnotice".$i]=$_POST["rnotice".$i];
                    }
                }
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
                $id = $_SESSION["userID"];
                $rus = D('admin')->where("id=$id")->find();
                $user3 = $rus["user3"];
                if ($user3 == 1) {
                    $id = I("get.id");
                    $M = D("admin");
                    $list = $M->where("id=$id")->find();
                    $ru = D("admin_auth_rule")->select();
                    $auth = $list["auth"];
                    $arr = explode(',', $auth);
                    for ($i = 0; $i < count($arr); $i++) {
                        $arrs[$i]["num"] = $arr[$i];
                    }
                    $rus = json_encode($arrs);
                    $this->assign('rus', $rus);
                    $this->assign('ru', $ru);
                    $this->assign("list", $list);
                    $this->display();

                }else{
                    $this->error('没有操作权限',U("User/index"));
                }
            }
        }


}