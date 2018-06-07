<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31 0031
 * Time: 上午 11:47
 */

namespace Home\Controller;


class PasswordController extends BaseController
{
    public function index(){
       if(isset($_POST["userID"])){
            $id=I("post.userID");
           $data=D("admin")->where("id=$id")->find();
           $password1=I("post.password1");
           $password2=I("post.password2");
           if($password1!=$data["password"]){
               $this->error("修改失败,旧密码输入错误", U("Password/index"));
           }else if($password1==$password2){
               $this->error("修改失败,旧密码与新密码相同", U("Password/index"));
           }else{
               $arr["password"]=$password2;
               $num=D("admin")->where("id=$id")->save($arr);
               if ($num == 1) {
                   $this->success("修改成功", U("Password/index"));
               } else {
                   $this->error("修改失败", U("Password/index"));
               }
           }
       }else{
           $userID=$_SESSION["userID"];
           $data=D("admin")->where("id=$userID")->find();
           $this->assign("data",$data);
           $this->display();
       }
    }
}