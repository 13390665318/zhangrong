<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 下午 12:06
 * 请求服务器信息
 */

namespace Doc\Controller;


use Doc\Controller\BaseController;
use Think\Controller;

class ClothesController extends Controller
{

    public function a(){
        $redis = new \Redis();
        $redis->connect("127.0.0.1","6379");
        $notice=$redis->keys("dbAlirefor"."*");
        var_dump($notice);
//$redis->delete("dbTest_999");
//$redis->get($notice[0]);
//dump($redis->get($notice[1]));
    }

    public function clothes_count()
    {
        $_GET["game_id"] = 1;
        if (isset($_GET["game_id"])) {
            $game_id = I("get.game_id");
            $time = strtotime(date("Y-m-d H:i:s", time()));
            $ip=$_SERVER['REMOTE_ADDR'];
            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            $notice=$redis->keys("dbAlirefor"."*");
            for($i=0;$i<count($notice);$i++){
                $arrs[$i]=$redis->get($notice[$i]);
                $ksd=json_decode($arrs[$i],true);
                //var_dump($ksd);
                if(strtotime($ksd["start_time"])<=$time){//echo 1;

                    if( $ksd["status"]!=0){ //echo 3;

                        $arr[$i]=$ksd;


                    }
                }
            }
            $arr=array_values($arr);
//var_dump($arr);

            //   $arr = D("db")->where("game_id=$game_id and start_time<='$time' ")->order("db_id asc")->select();
            for($i=0;$i<count($arr);$i++){
                if($arr[$i]["iplist"]!=null){
                    $iplist=$arr[$i]["iplist"];
                    $str = explode(';', $iplist);
//var_dump($str);
                    if($str!=null){
                        for($j=0;$j<=count($str);$j++){
                            if($str[$j]==$ip){
                                //echo $str[$j];
                                $stu[$i]=$arr[$i];
                                continue;
                            }
                        }
                    }
                }else{
                    $stu[$i]=$arr[$i];
                }
            }
            $stu=array_values($stu);
            if($stu!=null){
                $data["num"] = count($stu);
                $data["begin_clothes"] = $arr[0]["clothes"];
                $data["begin_clothes_num"] = $arr[0]["clothes_num"];
                $num = count($stu) - 1;
                $data["end_clothes"] = $arr[$num]["clothes"];
                $data["end_clothes_num"] = $arr[$num]["clothes_num"];
                for($i=0;$i<count($stu);$i++){
                    if($stu[$i]["type"]==1 && $stu[$i]["status"] !=0){
                        $rus[0]["clothes"] = $stu[$i]["clothes"];
                        $rus[0]["status"] = $stu[$i]["status"];
                        $rus[0]["db_id"] = $stu[$i]["db_id"];
                        $rus[0]["clothes_num"] = $stu[$i]["clothes_num"];
                        $rus[0]["ip"] = $stu[$i]["ip"];
                        $rus[0]["db_port"] = $stu[$i]["db_port"];
                    }
                }
                if($rus==null){
                    $rus[0]["clothes"] = $stu[$num]["clothes"];
                    $rus[0]["status"] = $stu[$num]["status"];
                    $rus[0]["db_id"] = $stu[$num]["db_id"];
                    $rus[0]["clothes_num"] = $stu[$num]["clothes_num"];
                    $rus[0]["ip"] = $stu[$num]["ip"];
                    $rus[0]["db_port"] = $stu[$num]["db_port"];
                }



                $data["new_clothes"] = $rus;



                $arrs = array();
                $list["list"] = $data;
                $list = json_encode($list, JSON_UNESCAPED_UNICODE);
            }else{
                $list["list"] = null;
                $list = json_encode($list, JSON_UNESCAPED_UNICODE);
            }
            echo $list;

        }
    }













    public function clothes_num()
    {
        // $_GET["begin_clothes"]=1;
        //   $_GET["end_clothes"]=5;
        //    $_GET["game_id"]=1;
        if (isset($_GET["begin_clothes"]) && isset($_GET["end_clothes"]) && isset($_GET["game_id"])) {
            $time = date("Y-m-d H:i:s", time());
            $game_id = I("get.game_id");
            $begin_clothes = (int)I("get.begin_clothes");

            $end_clothes = (int)I("get.end_clothes");
            // $num = $end_clothes - $begin_clothes;
            $ip=$_SERVER['REMOTE_ADDR'];
            $time = strtotime(date("Y-m-d H:i:s", time()));

            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            $notice=$redis->keys("dbAlirefor"."*");
            for($i=0;$i<count($notice);$i++){
                $arrs[$i]=$redis->get($notice[$i]);
                $ksd=json_decode($arrs[$i],true);
                //   var_dump($ksd);
                if(strtotime($ksd["start_time"])<=$time){//echo 1;

                    if( $ksd["status"]!=0 && $ksd["clothes_num"]>=$begin_clothes && $ksd["clothes_num"]<=$end_clothes){ //echo 3;

                        $arr[$i]=$ksd;


                    }
                }
            }
            $arr=array_values($arr);
//var_dump($arr);exit;



            //  $arr = D("db")->where("game_id=$game_id and start_time<='$time'")->field("db_id,clothes,status,ip,db_port,clothes_num,iplist")->limit($begin_clothes, $num)->select();
            for($i=0;$i<count($arr);$i++){
                if($arr[$i]["iplist"]!=null){
                    $iplist=$arr[$i]["iplist"];
                    $str = explode(';', $iplist);
                    if($str!=null){
                        for($j=0;$j<count($str);$j++){
                            if($str[$j]==$ip){
                                $stu[$i]=$arr[$i];
                                continue;
                            }
                        }
                    }
                }else{
                    $stu[$i]=$arr[$i];
                }
            }
            $stu=array_values($stu);
            $data["list"] = $stu;

            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            echo $data;
        }
    }
    /**
     * 查询是否存在服务器
     */

    public function find()
    {
        if (isset($_GET["db_id"])) {
            $db_id = I("get.db_id");
            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            //  $notice=$redis->keys("dbAlirefor"."*");
            $arrs=$redis->get("dbAlirefor_".$db_id);
            $arr=json_decode($arrs,true);
            $ip=$_SERVER['REMOTE_ADDR'];
            if ($arr == null) {
                $list["code"] = -1;
                $list["list"] = "没有该服务器";
            } else {

                if($arr["iplist"]!=null){
                    $iplist=$arr["iplist"];
                    $str = explode(';', $iplist);
                    if($str!=null){
                        for($j=0;$j<count($str);$j++){
                            if($str[$j]==$ip){
                                $stu=1;
                                continue;
                            }
                        }
                    }
                }else{
                    $stu=1;
                }
                if($stu==1){
                    $list["code"] = 0;
                    $list["list"] = "存在服务器";
                }else{
                    $list["code"] = -1;
                    $list["list"] = "没有该服务器";
                }

            }
            $data = json_encode($list, JSON_UNESCAPED_UNICODE);
            echo $data;
        }
    }

    public function NewClothes()
    {
        if (isset($_GET["localhost_db_name"])) {

            $arr = array();
            $arr["game_id"] =1;
            $game_id =1;
            $stu = D("game")->where("game_id=$game_id")->find();
            $arr["game_name"] = $stu["game_name"];
            $arr["db_id"] = I("get.db_id");
            $arr["clothes_num"] = I("get.clothes_num");
            $arr["clothes"] = I("get.clothes");
            $arr["start_time"] = I("get.start_time");
            $arr["localhost_db_name"] = "dbAlirefor_" . I("get.localhost_db_name");
            $arr["game_db_name"] = I("get.game_db_name");
            $arr["game_db_host"] = I("get.game_db_host");
            $arr["game_db_port"] = I("get.game_db_port");
            $arr["game_db_user"] = I("get.game_db_user");
            // $arr["game_db_pwd"] = I("get.game_db_pwd");
            $arr["game_db_pwd"] = "Xrzp123!@#";
            $arr["ip"] = I("get.ip");
            $arr["redis_db"] = I("get.redis_db");
            $arr["redis_ip"] = I("get.redis_ip");
            $arr["redis_port"] = I("get.redis_port");
            $arr["redis_psw"] = I("get.redis_psw");
            $arr["db_port"] = I("get.db_port");
            $arr["status"] = I("get.status");
            $arr["type"] = I("get.type");
            $arr["iplist"]=$_GET["iplist"];
            $arr["time"] = date("Y-m-d", time());
            //var_dump($arr);
            //创建数据库  创建 表
            $db_name = $arr["localhost_db_name"];
            $ru = D("db")->where("localhost_db_name='$db_name'")->find();
            if ($ru == null) {
                $M = M();
                $sql = "CREATE DATABASE $db_name default character set utf8";

                $res = mysql_query($sql);     //主要针对查询  //返回一个维数组
                mysql_query("set names utf8");
                $res = $M ->execute($sql);//exit;
                if ($res == 1) {
                    $con = mysql_connect('localhost', 'root', 'xueranzhengbaohoutai@#!');
                    // $con = mysql_connect('localhost', 'root', '');
                    mysql_select_db($db_name, $con);
                    $sql0 = "CREATE TABLE user (user_id int NOT NULL AUTO_INCREMENT, PRIMARY KEY(user_id),game_user_id int(11),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),mobile_model varchar(225),register_time varchar(225),status varchar(225),num int(11),count_num varchar(225),end_time varchar(225),user_day varchar(225),user_num int(11),user_time varchar(225),game_user_name varchar(225),source varchar(225),level int(11),country varchar(225))default character set utf8;";

                    $res = mysql_query($sql0);     //主要针对查询  //返回一个维数组

                    if ($res == 1) {
                        mysql_query("SET NAMES 'utf8'");
                        $sql1 = "CREATE TABLE sign (sign_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(sign_id),game_user_id int(11),user_id int(11),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),start_time varchar(225),end_time varchar(225),duration varchar(225),source varchar(225),mobile_model varchar(225),ip  varchar(225))default character set utf8;";
                        $res = mysql_query($sql1);     //主要针对查询  //返回一个维数组

                        if ($res == 1) {
                            mysql_query("SET NAMES 'utf8'");
                            $sql2 = "CREATE TABLE period (period_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(period_id),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),num int(11),user_num int(11),f_time varchar(225),time varchar (225),source varchar(225))default character set utf8;";
                            $res = mysql_query($sql2);     //主要针对查询  //返回一个维数组
                            //  $res = $M ->execute($sql2);
                            if ($res == 1) {
                                mysql_query("SET NAMES 'utf8'");
                                $sql3 = "CREATE TABLE custom (coustom_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(coustom_id),game_name varchar(225),game_id int(11),db_id varchar(225),mobile_type varchar(225),game_user_id int(11),user_id int(11),param1 varchar(225),param2 varchar(225),param3 varchar(225),param4 varchar(225),time varchar (225))default character set utf8;";
                                $res = mysql_query($sql3);     //主要针对查询  //返回一个维数组
                                if ($res == 1) {
                                    mysql_query("SET NAMES 'utf8'");
                                    $sql4 = "CREATE TABLE pay (pay_id int NOT NULL AUTO_INCREMENT,PRIMARY KEY(pay_id),game_user_id int(11),game_name varchar(225),user_id int(11),db_id varchar(225),source varchar(225),game_user_name varchar(225),pay_type varchar(225),money varchar(225),pay_time varchar(225),pay_number varchar(225),acer varchar(225),game_id int(11),level varchar(225))default character set utf8;";
                                    $res = mysql_query($sql4);     //主要针对查询  //返回一个维数组
                                    if ($res == 1) {
                                        $ruNum = D("db")->add($arr);
                                        if ($ruNum != null) {
                                            // 新增 redis
                                            $notice_id="dbAlirefor_".$ruNum;
                                            $value=json_encode($arr,JSON_UNESCAPED_UNICODE);
                                            $redis = new \Redis();
                                            $redis->connect("127.0.0.1","6379");
                                            $redis->set($notice_id,$value);
                                            $list["code"]=0;
                                            $list["msg"]="新增成功";
                                        } else {
                                            $list["code"]=-1;
                                            $list["msg"]="新增失败";

                                        }
                                    } else {
                                        $list["code"]=-1;
                                        $list["msg"]="PAY表创建失败";


                                    }
                                } else {
                                    $list["code"]=-1;
                                    $list["msg"]="custom表创建失败";

                                }


                            } else {
                                $list["code"]=-1;
                                $list["msg"]="period表创建失败";

                            }

                        } else {
                            $list["code"]=-1;
                            $list["msg"]="sign表创建失败";

                        }

                    } else {
                        $list["code"]=-1;
                        $list["msg"]="sign表创建失败";
                    }
                } else {
                    $list["code"]=-1;
                    $list["msg"]="数据库创建失败";

                }
                // 新建表

            } else {
                $list["code"]=-1;
                $list["msg"]="数据库名重复";

            }

        }
        $ruselt=json_encode($list,JSON_UNESCAPED_UNICODE);
        echo $ruselt;
    }


    public function edit(){
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $ru=D("db")->where("db_id=$db_id")->find();
            if($ru==null){
                $data["code"]=-1;
                $data["msg"]="该区服不存在";
            }else{
                $arr = array();

                $arr["clothes"] = I("get.clothes");
                $arr["game_db_name"] = I("get.game_db_name");
                $arr["game_db_host"] = I("get.game_db_host");
                $arr["game_db_port"] = I("get.game_db_port");
                $arr["game_db_user"] = I("get.game_db_user");
                $arr["game_db_pwd"] = I("get.game_db_pwd");
                $arr["ip"] = I("get.ip");
                $arr["status"] = I("get.status");
                $arr["type"] = I("get.type");
                $arr["db_port"] = I("get.db_port");
                $arr["clothes_num"] = I("get.clothes_num");
                $arr["start_time"] = I("get.start_time");
                $arr["iplist"]=I("get.iplist");
                $arr["redis_db"] = I("get.redis_db");
                $arr["redis_ip"] = I("get.redis_ip");
                $arr["redis_port"] = I("get.redis_port");
                $arr=array_filter($arr);
                $r=D("db")->where("db_id=$db_id")->save($arr);
                if($r=1){

                    $notice_id="dbAlirefor_".$db_id;
                    $value=json_encode($arr,JSON_UNESCAPED_UNICODE);
                    $redis = new \Redis();
                    $redis->connect("127.0.0.1","6379");
                    $redis->delete("dbAlirefor_".$db_id);
                    $redis->set($notice_id,$value);

                    $data["code"]=0;
                    $data["msg"]="修改OK";
                }else{
                    $data["code"]=-2;
                    $data["msg"]="修改error";
                }
            }

            $ruselt=json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $ruselt;







        }
    }



    public function del(){
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $r=D("db")->where("db_id=$db_id")->delete();
            if($r==1){
                $notice_id="dbAlirefor_".$db_id;
                //  $value=json_encode($arr,JSON_UNESCAPED_UNICODE);
                $redis = new \Redis();
                $redis->connect("127.0.0.1","6379");
                $redis->delete("dbAlirefor_".$db_id);
                //  $redis->set($notice_id,$value);
                $list["code"]=0;
                $list["msg"]="删除OK";
            }else{
                $list["code"]=-1;
                $list["msg"]="删除error";
            }
            $list = json_encode($list, JSON_UNESCAPED_UNICODE);
            echo $list;
        }
    }



    public function aa(){
        $redis = new \Redis();
        $redis->connect("127.0.0.1","6379");
        $notice=$redis->keys("dbAlirefor"."*");
        var_dump($notice);
        var_dump($redis->get($notice[0]));
    }

}