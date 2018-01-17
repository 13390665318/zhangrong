<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 下午 12:51
 */

namespace Admin\Controller;


class ClothesController extends  BaseController
{
  public function index(){
        $count      =D("db")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $list=D("db")->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign("list",$list);
        $this->display();
    }
    public function add(){
        if(isset($_POST["sub"])){
            $arr=array();
            $arr["game_id"]=I("post.game_id");
            $game_id=I("post.game_id");
            $stu=D("game")->where("game_id=$game_id")->find();
            $arr["game_name"]=$stu["game_name"];
		$arr["clothes_num"]=I("post.clothes_num");
            $arr["clothes"]=I("post.clothes");
$arr["start_time"]=I("post.start_time");
            $arr["localhost_db_name"]="Alirefor_".I("localhost_db_name");
            $arr["game_db_name"]=I("post.game_db_name");
            $arr["game_db_host"]=I("post.game_db_host");
            $arr["game_db_port"]=I("post.game_db_port");
            $arr["game_db_user"]=I("post.game_db_user");
            $arr["game_db_pwd"]=I("post.game_db_pwd");
		$arr["ip"]=I("post.ip");
		$arr["db_port"]=I("post.db_port");
            $arr["status"]=I("post.status");
            $arr["type"]=I("post.type");
            $arr["time"]=date("Y-m-d",time());
            //创建数据库  创建 表
            $db_name=$arr["localhost_db_name"];
	    $ru=D("db")->where("localhost_db_name='$db_name'")->find();
            if($ru==null){
                $M = M();
                $sql = " CREATE DATABASE $db_name default character set utf8";
                $res =mysql_query($sql);     //主要针对查询  //返回一个维数组
                mysql_query("set names utf8");
                $res = $M ->execute($sql);
                if($res==1){
                   $con = mysql_connect('localhost', 'root', 'xueranzhengbaohoutai@#!');
                 //   $con = mysql_connect('localhost', 'root', '');
                    mysql_select_db($db_name,$con);
                    $sql0 = "CREATE TABLE user (user_id int NOT NULL AUTO_INCREMENT, PRIMARY KEY(user_id),game_user_id int(11),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),mobile_model varchar(225),register_time varchar(225),status varchar(225),num int(11),count_num varchar(225),end_time varchar(225),user_day varchar(225),user_num int(11),user_time varchar(225),game_user_name varchar(225),source varchar(225),level varchar(225),country varchar(225))default character set utf8;";
			                  
 $res =mysql_query($sql0);     //主要针对查询  //返回一个维数组

                    if($res==1){
                        mysql_query("SET NAMES 'utf8'");
                        $sql1 = "CREATE TABLE sign (sign_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(sign_id),game_user_id int(11),user_id int(11),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),start_time varchar(225),end_time varchar(225),duration varchar(225),source varchar(225))default character set utf8;";
                        $res =mysql_query($sql1);     //主要针对查询  //返回一个维数组

                        if($res==1){
                            mysql_query("SET NAMES 'utf8'");
                            $sql2 = "CREATE TABLE period (period_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(period_id),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),num int(11),user_num int(11),f_time varchar(225),time varchar (225),source varchar(225))default character set utf8;";
                            $res =mysql_query($sql2);     //主要针对查询  //返回一个维数组
                            //  $res = $M ->execute($sql2);
                            if($res==1){
                                mysql_query("SET NAMES 'utf8'");
                                $sql3 = "CREATE TABLE custom (coustom_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(coustom_id),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),game_user_id int(11),user_id int(11),param1 varchar(225),param2 varchar(225),param3 varchar(225),param4 varchar(225),time varchar (225))default character set utf8;";
                                $res =mysql_query($sql3);     //主要针对查询  //返回一个维数组
                                if($res==1){
                                    mysql_query("SET NAMES 'utf8'");
                                    $sql4 = "CREATE TABLE pay (pay_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(pay_id),game_user_id int(11),game_name varchar(225),user_id int(11),db_id varchar(225),source varchar(225),game_user_name varchar(225),pay_type varchar(225),money varchar(225),pay_time varchar(225),pay_number varchar(225),acer varchar(225),game_id int(11),level varchar(225))default character set utf8;";
                                    $res =mysql_query($sql4);     //主要针对查询  //返回一个维数组
                                    if($res==1){
                                        $ruNum=D("db")->add($arr);
                                        if($ruNum!=null){
		  $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("id=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$ruNum."的游戏服务器";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                                            $this->success("新增成功",U("Clothes/index"));
                                        }else {
                                            $this->error("新增失败", U("Clothes/add"));
                                        }
                                    }else{
                                        //   echo $sql4;exit;
                                        $this->error("PAY表创建失败",U("Clothes/add"));

                                    }
                                }else{
                                    $this->error("custom表创建失败",U("Clothes/add"));
                                }


                            }else{
                                $this->error("period表创建失败",U("Clothes/add"));
                            }

                        }else{
                            $this->error("sign表创建失败",U("Clothes/add"));
                        }

                    }else{
                        $this->error("user表创建失败",U("Clothes/add"));
                    }
                }else{
                    $this->error("数据库创建失败",U("Clothes/add"));
                }
                // 新建表

            }else{
                $this->error("数据库名重复",U("Clothes/add"));
            }


        }else{
	 $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $clothes1=$rus["clothes1"];
            if($clothes1==1) {
            $list=D("game")->where("status=1")->select();
            $this->assign("list",$list);
            $this->display();
 	 }else{
                $this->error('没有操作权限',U("Clothes/index"));
            }
        }
    }

public function edit(){
        
            if(isset($_GET["id"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $clothes3=$rus["clothes3"];
            if($clothes3==1) {
           $db_id=I("get.id");
           $arr=D("db")->where("db_id=$db_id")->find();
           $game_id=$arr["game_id"];
            $list=D("game")->where("status=1")->select();
            $this->assign("list",$list);
            $this->assign("arr",$arr);
            $this->assign("game_id",$game_id);
            $this->display();
            }else{
                $this->error('没有操作权限',U("Clothes/index"));
            }
        }else if(isset($_POST["db_id"])){
            $db_id=I("post.db_id");
            $arr=array();
            $arr["game_id"]=I("post.game_id");
            $game_id=I("post.game_id");
            $stu=D("game")->where("game_id=$game_id")->find();
            $arr["game_name"]=$stu["game_name"];
            $arr["clothes_num"]=I("post.clothes_num");
            $arr["clothes"]=I("post.clothes");
            $arr["game_db_name"]=I("post.game_db_name");
            $arr["game_db_host"]=I("post.game_db_host");
            $arr["game_db_port"]=I("post.game_db_port");
            $arr["game_db_user"]=I("post.game_db_user");
            $arr["game_db_pwd"]=I("post.game_db_pwd");
            $arr["ip"]=I("post.ip");
            $arr["db_port"]=I("post.db_port");
            $arr["status"]=I("post.status");
            $arr["type"]=I("post.type");
$arr["start_time"]=I("post.start_time");
            $ruNum=D("db")->where("db_id=$db_id")->save($arr);
            if($ruNum==1){
		 $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("id=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$db_id."的游戏服务器";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Clothes/index"));
            }else {
                $this->error("修改失败", U("Clothes/edit",array("id"=>$db_id)));
            }

        }
}


    public function show(){
        if(isset($_GET["id"])){
            $id=I("get.id");
            $list=D("db")->where("game_id=$id")->select();
            $this->assign("list",$list);
            $this->display();
        }
    }

}