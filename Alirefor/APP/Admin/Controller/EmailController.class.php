<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 上午 11:44
 */

namespace Admin\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class EmailController extends BaseController
{
    public function index(){
        $count      =D("email")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("email")->order("email_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }

    public function add(){
        if(isset($_SESSION["game_id"]) && isset($_SESSION["game_name"])) {
            $game_id = $_SESSION["game_id"];
            $game_name = $_SESSION["game_name"];
        } else {
            $str = D("game")->where("game_id=1")->find();
            $game_id = 1;
            $game_name = $str["game_name"];
        }
        if(isset($_POST["sub"])){
            $arr["clothes"]=I("post.clothes");
            $arr["game_user_ids"]=I("post.game_user_ids");
            $arr["money"]=I("post.money");
            $arr["acers"]=I("post.acers");
            $arr["goods_ids"]=I("post.goods_ids");
            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $arr["sender"]=I("post.sender");
            $arr["status"]=0;
            $arr["time"]=date("Y-m-d H:i:s",time());
            $arr["send_time"]="";

            $rus=D("email")->add($arr);
            if($rus !=null){
$userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$rus."的邮件";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Email/index"));
            }else{
                $this->error("新增失败", U("Email/add"));
            }
        }else{
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["email1"];
            if($user2==1) {
                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("arr", $clostu);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Email/index"));
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
        if(isset($_POST["email_id"])){
            $email_id=I("post.email_id");
            $arr["clothes"]=I("post.clothes");
            $arr["game_user_ids"]=I("post.game_user_ids");
            $arr["money"]=I("post.money");
            $arr["acers"]=I("post.acers");
            $arr["goods_ids"]=I("post.goods_ids");
            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $arr["sender"]=I("post.sender");
            $rus=D("email")->where("email_id=$email_id")->save($arr);;
            if($rus ==1){
                //  $this->redirect('Reward/index');
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$email_id."的邮件";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Email/index"));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("修改失败", U("Email/edit",array("id"=>$email_id)));
            }
        }else if(isset($_GET["id"])){
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["email3"];
            if($user2==1) {
                $email_id = I("get.id");
                $arr = D("email")->where("email_id=$email_id")->find();
                $this->assign("arr", $arr);
                $clothes = $arr["clothes"];
                $this->assign("clothes", $clothes);

                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("clostu", $clostu);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Email/index"));
            }
        }

    }


    public function del(){
        $id=$_SESSION["userID"];
        $rus=D('admin')->where("id=$id")->find();
        $user2=$rus["email2"];
        if($user2==1) {
            if (isset($_GET["ids"])) {
                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
                $M = D("email"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $id = $str[$i];
                    if ($id != null) {
                        if ($id == 1) {
                            $nums = -1;
                        } else {
                            $num = $M->where("email_id=$id")->delete();
                            if ($num == 1) {
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="删除id为".$rus."的邮件";
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
    public function userselect(){
        if(isset($_GET["game_user_name"])){
            // var_dump($_GET);
            $game_user_name=I("get.game_user_name");
            $db_id=I("get.db_id");
            $game_id = $_SESSION["game_id"];
            $connection=db($game_id,$db_id);
            // var_dump($connection);
            $Userbase = M('San_userbase','',$connection);
            $arr=$Userbase->where("uname like '%$game_user_name%'")->field("uid,uname,level")->select();
            $data=json_encode($arr);
            echo $data;
        }
    }
// 发送邮件
    public function send(){
        if(isset($_GET["email_id"])){
            $email_id=I("get.email_id");
            $arr=D("email")->where("email_id=$email_id")->find();
            $db_id=$arr["clothes"];
            $data=D("db")->where("db_id=$db_id")->find();
            $ip=$data["ip"];
            $port=$data["db_port"];
            $uid=$arr["game_user_ids"];
            $stUid=explode(",",$uid);
            for($i=0;$i<count($stUid);$i++){
                $Uarr[$i]=(int)$stUid[$i];
            }
            $get_data["uid"]=$Uarr;
            $get_data["title"]=$arr["title"];
            $get_data["body"]=$arr["content"];
            // var_dump($_SESSION);exit;
            $get_data["sender"]=$arr["sender"];

            $goods_ids=$arr["goods_ids"];
            $bag=explode(";",$goods_ids);
            $bag=array_filter($bag);

            for($i=0;$i<count($bag);$i++){
                $baGarr=$bag[$i];
                $ru=explode(':',$baGarr);
                $Gstu[$i]["itemid"]=(int)$ru[0];
                $Gstu[$i]["num"]=(int)$ru[1];
            }
if($arr["money"]!=0){
            $Gstu[count($bag)]["itemid"]=(int)91000001;
            $Gstu[count($bag)]["num"]=(int)$arr["money"];}
if((int)$arr["acers"]!=0){
            $Gstu[count($bag)+1]["itemid"]=(int)91000002;
            $Gstu[count($bag)+1]["num"]=(int)$arr["acers"];
}	
            $get_data["item"]=array_merge($Gstu);

            $jsData= json_encode($get_data,JSON_UNESCAPED_UNICODE);

            $ruSdata=base64_encode($jsData);
            $ruSdata = str_replace(array('+','/'),array('-','_'),$ruSdata);
   
        
            $ip=$data["ip"];
            $port=$data["db_port"];
            $url="http://$ip:$port/sendmail?context=$ruSdata";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);

            curl_close($ch);
            $a=json_decode($output);
            if($a->code==0){
                //改变状态
                $where["status"]=1;
                $where["send_time"]=date("Y-m-d H:i:s",time());
                $rus=D("email")->where("email_id=$email_id")->save($where);
                if($rus==1){
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="发送id为".$email_id."的邮件";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                    echo 0;
                }
            }
           



        }
    }
}