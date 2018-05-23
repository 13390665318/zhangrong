<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 下午 7:20
 */

namespace Admin\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class RewardController extends BaseController
{
    public function index(){
        $count      =D("reward")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("reward")->order("reward_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if(isset($_POST["sub"])){

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
            $data["time"]=date("Y-m-d H:i:s",time());
            $data["status"]=0;
            //  var_dump($data);exit;
            $rus=D("reward")->add($data);
            if($rus !=0){
		 $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$rus."的全服补偿奖励";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->redirect('Reward/index');
            }else{
                $this->redirect('Reward/index');
            }
        }else{
            // 查找所有区/服
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["reword1"];
            if($user2==1) {
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
                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("clostu", $clostu);
                $this->display();
            }else{
                $this->error('没有操作权限',U("Reward/index"));
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
        if(isset($_POST["reward_id"])){
            $reward_id=I("post.reward_id");
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
            $rus=D("reward")->where("reward_id=$reward_id")->save($data);
            if($rus ==1){
                //  $this->redirect('Reward/index');
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
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
            $id=$_SESSION["userID"];
            $rus=D('admin')->where("id=$id")->find();
            $user2=$rus["reword3"];
            if($user2==1) {
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
                $this->display();
            }else{
                $this->error('没有操作权限',U("Reward/index"));
            }

        }
    }

    public function del(){
        $id=$_SESSION["userID"];
        $rus=D('admin')->where("id=$id")->find();
        $user2=$rus["reword2"];
        if($user2==1) {
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
                            $num = $M->where("reward_id=$id")->delete();
                            if ($num == 1) {
$userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
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
        }else{
            echo -2;
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
         //   $get_data["item"]=$Gstu;
            $jsData= json_encode($get_data,JSON_UNESCAPED_UNICODE);
		//echo $jsData; exit;
            $ruSdata=base64_encode($jsData);
            $ruSdata = str_replace(array('+','/'),array('-','_'),$ruSdata);
            $clothes=$arr["clothes"];
            $did=explode(',',$clothes);
            $did=array_filter($did);
            for($i=0;$i<count($did);$i++) {
                $db_id = $did[$i];
                $data = D("db")->where("db_id=$db_id")->find();
                $ip = $data["ip"];
                $port = $data["db_port"];
                $url = "http://$ip:$port/sendmail?context=$ruSdata";
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

                }
            }

            echo 1;









        }
    }

}