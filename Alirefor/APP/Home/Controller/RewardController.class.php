<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 下午 7:20
 */

namespace Home\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class RewardController extends BaseController
{
    public function index(){
        if(isset($_GET["status"])){
            $com["status2"]=array('neq',10);
        }else{
            $com["status2"]=array('eq',0);
        }
        $count      =D("reward")->where($com)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("reward")->where($com)->order("reward_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        for($i=0;$i<count($arr);$i++){
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
        if(isset($_POST["sub"])){
//var_dump($_POST);exit;
            $game_id = 1;

            $data["begin_clothes"]=I("post.begin_clothes");
            $data["end_clothes"]=I("post.end_clothes");
 $data["creator"]=I("post.creator");

            $data["begin_time"]=I("post.begin_time");
            $data["end_time"]=I("post.end_time");
            $data["title"]=I("post.title");
            $data["content"]=$_POST["content"];
            $data["money"]=I("post.money");
            $data["blevel"]=I("post.blevel");
            $data["sender"]=I("post.sender");
            $data["elevel"]=I("post.elevel");
            $data["acers"]=I("post.acers");
            $data["goods_ids"]=I("post.goods_ids");
            $begin_clothes=I("post.begin_clothes");
            $end_clothes=I("post.end_clothes");
            $clostu=D("db")->where("game_id=$game_id and clothes_num>=$begin_clothes and clothes_num<=$end_clothes ")->order("db_id asc")->select();
            if($clostu==null){
                $stu=null;
            }else{
                $stu=null;
                for($i=0;$i<count($clostu);$i++){
                    $stu=$clostu[$i]["db_id"].','.$stu;
                }
            }
            $data["clothes"]=$stu;
            $data["time"]=date("Y-m-d H:i:s",time());
            $data["status"]=0;
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
                    }
                }
            }
}


            // var_dump($data);exit;
            $rus=D("reward")->add($data);
            if($rus !=0){
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("id=$userid")->find();
                $Rlog["user"]=$russ["name"];
                $Rlog["account"]=$russ["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="增加id为".$rus."的全服补偿奖励";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("增加成功",U("Reward/index"));
            }else{
                $this->error("增加失败", U("Reward/add"));
            }
        }else{
            // 查找所有区/服

            $game_id = 1;
 $qudao=D("qudao")->select();
        $this->assign("qudao",$qudao);
            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("clostu", $clostu);
            $this->display();

        }
    }

    public function edit(){
        $game_id = 1;

        if(isset($_POST["reward_id"])){
            $reward_id=I("post.reward_id");
            $data["begin_clothes"]=I("post.begin_clothes");
 $data["creator"]=I("post.creator");
            $data["end_clothes"]=I("post.end_clothes");
            $data["begin_time"]=I("post.begin_time");
            $data["end_time"]=I("post.end_time");
            $data["title"]=I("post.title");
            $data["content"]=$_POST["content"];
            $data["money"]=I("post.money");
            $data["blevel"]=I("post.blevel");
            $data["sender"]=I("post.sender");
            $data["elevel"]=I("post.elevel");
            $data["acers"]=I("post.acers");
            $data["goods_ids"]=I("post.goods_ids");
            $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            for($i=0;$i<count($clostu);$i++){
                if(isset($_POST["clothes".$i])){
                    $data["clothes"]=$_POST["clothes".$i].','.$data["clothes"];
                }
            }

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
                    }
                }
            }
		}
            $rus=D("reward")->where("reward_id=$reward_id")->save($data);
            if($rus ==1){
                //  $this->redirect('Reward/index');
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("id=$userid")->find();
                $Rlog["user"]=$russ["name"];
                $Rlog["account"]=$russ["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="修改id为".$reward_id."的全服补偿奖励";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Reward/index"));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("修改失败", U("Reward/edit",array("id"=>$reward_id)));
            }
        }else if(isset($_GET["id"])){

            $reward_id = I("get.id");

            $arr = D("reward")->where("reward_id=$reward_id")->find();
            $this->assign("arr", $arr);
            $clothes = $arr["clothes"];
            $data = explode(',', $clothes);
            $data = array_filter($data);
            $data = json_encode($data);
            $this->assign("data", $data);

            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("clostu", $clostu);
$qudao=D("qudao")->select();
        $this->assign("qudao",$qudao);

            $this->display();


        }
    }

    public function del(){

        if (isset($_GET["ids"])) {
            $ids = I("get.ids");
            $arr = array();
            $str = explode(',', $ids);
            $M = D("reward"); // 实例化User对象
            for ($i = 0; $i < count($str); $i++) {
                $id = $str[$i];
                if ($id != null) {
                    if ($id == 1) {
                        $nums = -1;
                    } else {
                        $arr["status2"]=-1;
                        $num = $M->where("reward_id=$id")->save($arr);
                        if ($num == 1) {
                            $userid=$_SESSION["userID"];
                            $rus=D('admin')->where("id=$userid")->find();
                            $Rlog["user"]=$rus["name"];
                            $Rlog["account"]=$rus["user_name"];
                            $Rlog["ip"]=get_client_ip();
                            $Rlog["doc"]="删除id为".$id."的全服补偿奖励";
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
public function show(){
        if(isset($_GET["reward_id"])){
            $reward_id=I("get.reward_id");
            $arr=D("reward")->where("reward_id=$reward_id")->find();
            $clothes=$arr["clothes"];
            if($clothes==null){
                $did=D("db")->select();
            }else{
                $did=explode(',',$clothes);
                $did=array_filter($did);
            }
            for($i=0;$i<count($did);$i++) {
                $db_id = $did[$i]["db_id"];
                $data = D("db")->where("db_id=$db_id")->find();
                $stu[$i]["name"]=$data["clothes"];
            }
            $r=json_encode($stu);
            echo $r;
        }
}



    // 发送邮件
    public function send(){
        if(isset($_GET["reward_id"])){
            $reward_id=I("get.reward_id");
            $arr=D("reward")->where("reward_id=$reward_id")->find();
            $Uarr=array();
            $get_data["uid"]=$Uarr;
            $get_data["title"]=$arr["title"];
            $get_data["body"]=$arr["content"];
            $get_data["sender"]=$arr["sender"];
            $get_data["MinLevel"]=(int)$arr["blevel"];
            $get_data["MaxLevel"]=(int)$arr["elevel"];
$time=date("Y-m-d H:i:s",time());
            $end_time=$arr["end_time"];
            if($time>$end_time){
                echo -10;exit;
            }
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
	$creator=$arr["creator"];
            $creator=explode(",",$creator);
            $creator=array_filter($creator);
$get_data["creator"]=$creator;
//var_dump($get_data);exit;

            $jsData= json_encode($get_data,JSON_UNESCAPED_UNICODE);
           
            $ruSdata=base64_encode($jsData);
            $ruSdata = str_replace(array('+','/'),array('-','_'),$ruSdata);
            $clothes=$arr["clothes"];
            if($clothes==null){
                $did=D("db")->select();
                for($i=0;$i<count($did);$i++) {
                    $db_id = $did[$i]["db_id"];
                    $data = D("db")->where("db_id=$db_id")->find();
                    $ip = $data["ip"];
                    $port = $data["db_port"];
$token=$_SESSION["token"];
                    $md=md5($token);
                    $url = "http://$ip:$port/sendmail?context=$ruSdata&token=$md";
                //   $url = "http://$ip:$port/sendmail?context=$ruSdata";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $a = json_decode($output);

                    if ($a->code == 0) {
                        //改变状态
                        $userid=$_SESSION["userID"];
                        $rus=D('admin')->where("$userid=$userid")->find();
                        $Rlog["user"]=$rus["name"];
                        $Rlog["account"]=$rus["user_name"];
                        $Rlog["ip"]=get_client_ip();
                        $Rlog["doc"]="发送id为".$reward_id."的全服补偿奖励";
                        $Rlog["time"]=date("Y-m-d H:i:s",time());
                        $r=D("rlog")->add($Rlog);
                        $where["status"] = 1;
                        $rus = D("reward")->where("reward_id=$reward_id")->save($where);

                    }else{
                        $num=-1;
                    }
                }
            }else{
                $did=explode(',',$clothes);
                $did=array_filter($did);
                for($i=0;$i<count($did);$i++) {
                    $db_id = $did[$i];
                    $data = D("db")->where("db_id=$db_id")->find();
                    $ip = $data["ip"];
                    $port = $data["db_port"];
$token=$_SESSION["token"];
                    $md=md5($token);
                    $url = "http://$ip:$port/sendmail?context=$ruSdata&token=$md";
                  //  $url = "http://$ip:$port/sendmail?context=$ruSdata";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $a = json_decode($output);

                    if ($a->code == 0) {
                        //改变状态
                        $userid=$_SESSION["userID"];
                        $rus=D('admin')->where("id=$userid")->find();
                        $Rlog["user"]=$rus["name"];
                        $Rlog["account"]=$rus["user_name"];
                        $Rlog["ip"]=get_client_ip();
                        $Rlog["doc"]="发送id为".$reward_id."的全服补偿奖励";
                        $Rlog["time"]=date("Y-m-d H:i:s",time());
                        $r=D("rlog")->add($Rlog);
                        $where["status"] = 1;
                        $rus = D("reward")->where("reward_id=$reward_id")->save($where);

                    }else{
                        $num=-1;
                    }
                }
            }

            if($num==-1){
                echo 0;
            }else{
                echo 1;
            }










        }
    }

}