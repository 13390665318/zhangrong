<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 上午 11:44
 */

namespace Home\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class EmailController extends BaseController
{
    public function index(){
        if(isset($_GET["status"])){
            $com["status2"]=array('neq',10);
        }else{
            $com["status2"]=array('neq',-2);
        }
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
        //var clothes=$("#clothes").val();   区服
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            $db_id=$clostu[0]["db_id"];
            $db_id= $_SESSION["db_id"];
        }
        $com["clothes"]=$db_id;
        $this->assign("db_id",$db_id);
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");
        }else{
            $Stime=date("Y-m-01 00:00:00",time());
            $Etime=date("Y-m-d H:i:s",time());
        }
        
        $com["_string"]="time>='$Stime' AND time<='$Etime'";
        $this->assign("Stime",$Stime);
        $this->assign("Etime",$Etime);

        $com=array_filter($com);
//var_dump($com);
        $count      =D("email")->where($com)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("email")->where($com)->order("email_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $game_id=1;
        for($i=0;$i<count($arr);$i++){
            $db_id=$arr[$i]["clothes"];
            $asd=D("db")->where("db_id=$db_id")->find();
            $arr[$i]["db_name"]=$asd["clothes"];
            $connection=db2($game_id,$db_id);
//var_dump($connection);
            $game_user=$arr[$i]["game_user_ids"];
            $user=explode(',',$game_user);

            for($j=0;$j<count($user);$j++){
                if($user[$j]!=null){
                    $uid=$user[$j];

                    $ru=M('user','',$connection)->where("game_user_id=$uid")->find();

                    $arr[$i]["uname"]=$ru["game_user_name"].",".$arr[$i]["uname"];
                }
            }
            $goods=$arr[$i]["goods_ids"];
            $r=explode(';',$goods);
            for($j=0;$j<count($r);$j++){
                if($r[$j]!=null){
                    $rus=explode(':',$r[$j]);
                    $itemid=$rus[0];
                    $stu=D("goods")->where("itemid=$itemid")->find();
                    $arr[$i]["goods_name"].=$stu["itemname"].":".$rus[1].",";
                }
            }
        }
        $this->assign("arr",$arr);
        $this->display();
    }

    public function add(){
	//dump($_SESSION);
        $game_id = 1;
        if(isset($_POST["sub"])){
            $arr["clothes"]=I("post.clothes");
            $arr["game_user_ids"]=I("post.game_user_ids");
$game_user_ids=$arr["game_user_ids"];
	$user_game=explode(',',$game_user_ids);
	$m=FetchRepeatMemberInArray ($user_game);
//var_dump(count($m));
	if($m){
		echo "<script>alert('存在相同用户id');window.history.go(-1);</script>";exit;
	  }
//var_dump($_POST);exit;
            $arr["money"]=I("post.money");
            if($arr["money"]<0){
                echo "<script>alert('金额数量不可为负');window.history.go(-1);</script>";exit;
            }
            $arr["acers"]=I("post.acers");
            if($arr["money"]<0){
                echo "<script>alert('元宝数量不可为负');window.history.go(-1);</script>";exit;
            }
            $arr["goods_ids"]=I("post.goods_ids");
            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $arr["sender"]=I("post.sender");
            $arr["status"]=0;
            $arr["time"]=date("Y-m-d H:i:s",time());
            $arr["send_time"]="";
	   $arr["status2"]=-10;
            $goods_ids=I("post.goods_ids");
            if($goods_ids!=null){
                if(strstr($goods_ids, "；")){
                    echo "<script>alert('存在中文:；');window.history.go(-1);</script>";exit;
                }else if(strstr($goods_ids, "：")){
                    echo "<script>alert('存在中文:：');window.history.go(-1);</script>";exit;
                }else{
                    $bag=explode(";",$goods_ids);

                    for($i=0;$i<count($bag);$i++){
                        $baGarr=$bag[$i];
                        $ru=explode(':',$baGarr);
                        if(count($ru)!=2){
                            echo "<script>alert('物品格式不正确');window.history.go(-1);</script>";exit;
                        }else if($ru[1]<=0){
                            echo "<script>alert('道具数量不可为负');window.history.go(-1);</script>";exit;
                        }
                    }
                }}
            //   var_dump($arr);exit;
            $rus=D("email")->add($arr);
            if($rus !=null){
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("id=$userid")->find();
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
            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("arr", $clostu);
            $this->display();

        }
    }
    public function edit(){
        $game_id = 1;

        if(isset($_POST["email_id"])){
            $email_id=I("post.email_id");
            $arr["clothes"]=I("post.clothes");
            $arr["game_user_ids"]=I("post.game_user_ids");
$game_user_ids=$arr["game_user_ids"];
$user_game=explode(',',$game_user_ids);
	$m=FetchRepeatMemberInArray ($user_game);
	if($m){
		echo "<script>alert('存在相同用户id');window.history.go(-1);</script>";exit;
	  }

            $arr["money"]=I("post.money");
            if($arr["money"]<0){
                echo "<script>alert('金额数量不可为负');window.history.go(-1);</script>";exit;
            }
            $arr["acers"]=I("post.acers");
            if($arr["money"]<0){
                echo "<script>alert('元宝数量不可为负');window.history.go(-1);</script>";exit;
            }
           
            $arr["goods_ids"]=I("post.goods_ids");
            $arr["title"]=I("post.title");
            $arr["content"]=I("post.content");
            $arr["sender"]=I("post.sender");
            // 判断 格式 是否正确
            $goods_ids=I("post.goods_ids");
if($goods_ids!=null){
            if(strstr($goods_ids, "；")){
                echo "<script>alert('存在中文:；');window.history.go(-1);</script>";exit;
            }else if(strstr($goods_ids, "：")){
                echo "<script>alert('存在中文:：');window.history.go(-1);</script>";exit;
            }else{
                $bag=explode(";",$goods_ids);

                for($i=0;$i<count($bag);$i++){
                    $baGarr=$bag[$i];
                    $ru=explode(':',$baGarr);
                    if(count($ru)!=2){
                        echo "<script>alert('物品格式不正确');window.history.go(-1);</script>";exit;
                    }else if($ru[1]<=0){
                        echo "<script>alert('道具数量不可为负');window.history.go(-1);</script>";exit;
                    }
                }
            }
}
            $rus=D("email")->where("email_id=$email_id")->save($arr);;
            if($rus ==1){
                //  $this->redirect('Reward/index');
                $userid=$_SESSION["userID"];
                $rus=D('admin')->where("id=$userid")->find();
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
            $email_id = I("get.id");
            $arr = D("email")->where("email_id=$email_id")->find();
            $this->assign("arr", $arr);
            $clothes = $arr["clothes"];
            $this->assign("clothes", $clothes);

            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("clostu", $clostu);
            $this->display();

        }

    }


    public function del(){

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
                        $arr["status2"]=-2;
                        $num = $M->where("email_id=$id")->save($arr);
		//	var_dump($num);exit;
                      if ($num == 1) {
			//var_dump($_SESSION);
                            $userid=$_SESSION["userID"];
			//echo $userid;
                            $rus=D('admin')->where("id=$userid")->find();
			//var_dump($rus);
                            $Rlog["user"]=$rus["name"];
                            $Rlog["account"]=$rus["user_name"];
                            $Rlog["ip"]=get_client_ip();
                            $Rlog["doc"]="删除id为".$id."的邮件";
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

    }
    public function userselect(){
        if(isset($_GET["game_user_name"])){
            // var_dump($_GET);
            $game_user_name=I("get.game_user_name");
            $db_id=I("get.db_id");
            $game_id =1;
            $connection=db($game_id,$db_id);
            // var_dump($connection);
            $Userbase = M('San_userbase','',$connection);
            $arr=$Userbase->where("uname like '%$game_user_name%'")->field("uid,uname,level")->select();
            if($arr!=null){
                $data=json_encode($arr);
            }else{
                $data=null;
            }

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

            $get_data["sender"]=$arr["sender"];

            $goods_ids=$arr["goods_ids"];

            if($goods_ids!=null){
                if(strstr($goods_ids, "；")){
                    echo -2;exit;
                }else if(strstr($goods_ids, "：")){
                    echo -1;exit;
                }else{
                    $bag=explode(";",$goods_ids);
                    //  var_dump($bag);
                    for($i=0;$i<count($bag);$i++){
                        $baGarr=$bag[$i];
                        $ru=explode(':',$baGarr);
                        if(count($ru)!=2){
                            echo -3;exit;
                        }else{
                            $Gstu[$i]["itemid"]=(int)$ru[0];
                            $Gstu[$i]["num"]=(int)$ru[1];
                        }
                    }
                }
//exit;

            }
            if($arr["money"]!=0){
                $Gstu[count($bag)]["itemid"]=(int)91000001;
                $Gstu[count($bag)]["num"]=(int)str_replace(",","", $arr["money"]) ;

            }
            if((int)$arr["acers"]!=0){
                $Gstu[count($bag)+1]["itemid"]=(int)91000002;
                $Gstu[count($bag)+1]["num"]=(int)str_replace(",","", $arr["acers"]);
            }
//var_dump( $Gstu);exit;
            $get_data["item"]=array_merge($Gstu);

            $jsData= json_encode($get_data,JSON_UNESCAPED_UNICODE);

            $ruSdata=base64_encode($jsData);
            $ruSdata = str_replace(array('+','/'),array('-','_'),$ruSdata);


            $ip=$data["ip"];
            $port=$data["db_port"];
$token=$_SESSION["token"];
            $md=md5($token);
            $url="http://$ip:$port/sendmail?context=$ruSdata&token=$md";
           // $url="http://$ip:$port/sendmail?context=$ruSdata";
//echo $url;exit;
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
                    $rus=D('admin')->where("id=$userid")->find();
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