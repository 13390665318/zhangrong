<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:36
 */

namespace Home\Controller;


class PlayRMBController extends BaseController
{
    public function index(){

        $game_id = 1;
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            if(isset($_SESSION["db_id"])){

                $db_id= $_SESSION["db_id"];
            }else{
                $db_id=$clostu[0]["db_id"];
                $_SESSION["db_id"]=$db_id;
            }

        }
        $this->assign("db_id",$db_id);
        $connection=db($game_id,$db_id);
        $count      =M('san_recharge as a','',$connection)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $day=M('san_recharge as a','',$connection)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign("arr",$day);
        $this->display();
    }

    public function add(){
        if(isset($_POST["sub"])){

            $data["uid"]=I("post.uid");
            $db_id=I("post.db_id");
            $data["order"]=I("post.order");
            $uid=I("post.uid");
            $game_id=1;
            $connection=db($game_id,$db_id);
            $Account = M('San_account','',$connection);
            $Userbase = M('San_userbase','',$connection);
            $ru=$Account->where("uid=$uid")->find();
            $ru2=$Userbase->where("uid=$uid")->find();
            $data["account"]=$ru["account"];;
            $data["type"]=I("post.type");
            $data["money"]=I("post.money");
            $data["timestamp"]=time();
            $data["flag"]=0;
            $data["level"]=$ru2["level"];
//var_dump($data);exit;
            $Recharge = M('san_recharge ','',$connection);
            $r=$Recharge->add($data);
            if($r !=null){
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$russ["name"];
                $Rlog["account"]=$russ["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="增加id为".$r."的充值记录";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("PlayRMB/index"));
            }else{
                $this->error("新增失败", U("PlayRMB/add"));
            }
            // 新增
        }else if(isset($_GET["orderid"])){
            $orderid=I("get.orderid");

            $r=D("order")->where("cporderid=$orderid")->find();
//dump($r);exit;
            if($r){
                $game_id=1;
                $db_id=$r["db_id"];
                $connection=db($game_id,$db_id);
                $Recharge = M('san_recharge ','',$connection);

                $rs=$Recharge->where(" `order` = '$orderid' ")->select();
//
                if($rs){
                    echo -2; // 该订单在游戏服务器存在
                }else{
                    $arr=json_encode($r);
                    echo $arr;
                }
            }else{
                echo -1; // 订单不存在
            }
        }else{
            $clostu = D("db")->where("game_id=1")->order("db_id asc")->select();
            $this->assign("arr", $clostu);
            $this->display();

        }
    }

// 批量T
    public function T(){
        $game_id=1;
        $db_id=1;
        $connection=db($game_id,$db_id);
        $Account = M('San_account','',$connection);
        $data=$Account->where("creator='guest'")->select();
        $datas=D("db")->where("db_id=$db_id")->find();
        //  var_dump($data);
        $ip=$datas["ip"];
        $port=$datas["db_port"];
        $token="alibabawansui";
        $md=md5($token);
        for($i=0;$i<count($data);$i++){
            $uid=$data[$i]["uid"];
            $url="http://$ip:$port/tickplayer?uid=$uid&token=$md";
        }
    }

}