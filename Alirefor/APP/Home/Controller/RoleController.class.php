<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:35
 */

namespace Home\Controller;
header("Content-type:text/html;charset=utf-8");

class RoleController extends BaseController
{
    /**
     **2018/02/03
     **auth by Zhang
     **
     */

    public function index()
    {

        $clostu=D("db")->order("id")->group('db_id')->select();
        $this->assign("clostu",$clostu);


        // 图标 默认 最新服

        $nowtime = date("Y-m-d H:i:s", time());
        //获取传参角色id
       if ($_GET['roleid']) {
            $roleid = I('get.roleid');

        }
        if ($_GET['rolename']){
            $rolename=I('get.rolename');
        }
        //判断是否选服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
        } else {
            $db_id=$clostu[0]['db_id'];
        }
        $this->assign('db_id',$db_id);
        $game_id="loong_game";
        $connection = db($game_id, $db_id);//连接数据库
        $model = M('t_roles', '', $connection);
        $record = $model->where("rid='$roleid'or rname='$rolename'")->select();//查询相关信息
        //$banghui=$model->field('bhname')->where("t_roles.rid='$roleid'or rname='$rolename'")->join('t_banghui as b on b.rid = t_roles.rid')->find();
        $record[0]['position']=explode(':',$record[0]['position']);
        if ($record[0]['lasttime'] <= $nowtime && $record[0]['lasttime'] > $record[0]['logofftime']) {
            $record[0]['online'] = 1;
        } else {
            $record[0]['online'] = 0;
        }
        $userid = $record[0]['userid'];
        $model = M('t_money', '', $connection);
        $zuan = $model->where("userid='$userid'")->find();
        //dump($record);exit;
        $this->assign("record", $record);
        $this->assign("zuan", $zuan);


        //dump($userid);exit;
        /*$Userbase = M('San_userbase','',$connection);
        $Account = M('San_account','',$connection);
        $User=M("User",'',$connection2);

        if($_GET["game_user_name"]!=null) {
            $uname = I("get.game_user_name");
            $where["uname"] = array('like', "%$uname%");
        }
        if(isset($_GET["game_user_id"])){
            if(I("get.game_user_id")!=null){
                $where["uid"]=I("get.game_user_id");
            }else{
                $where["uid"]=null;
            }
        }else{
            $where["uid"]=null;
        }
        $where['_string'] = "regtime>='$stime' AND regtime<='$etime'";
        $con=array_filter($where);
//var_dump($con);exit;
        $count      =$Userbase->where($con)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=$Userbase->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();

        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $Rus=$User->where("user_id=$uid")->find();
            $ac=$Account->where("uid=$uid")->find();
            $arr[$i]["account"]=$ac["account"];
            $arr[$i]["creator"]=$Rus["source"];
            $arr[$i]["Summoney"]=D("order")->where("uid=$uid and db_id=$db_id")->sum("amount")/100;
        }


//var_dump($arr);

        $this->assign("arr",$arr);*/
        $this->display();
    }


    public function detial()
    {
        // $_GET["uid"]=530;

        if (isset($_GET["uid"])) {

            $uid = I("get.uid");
            $game_id = 1;
            $db_id = I("get.db_id");

            $connection2 = db3($game_id, $db_id);


            $redis = new \Redis();
            $redis->connect($connection2["redis_ip"], $connection2["redis_port"]);
            $redis->auth($connection2["redis_psw"]);
            $redis->select($connection2["redis_db"]);
//var_dump($redis->select($connection2["redis_db"]));
            //    $account=  $redis->get("san_account_$uid");
            //    var_dump($account);exit;
            //   $account=json_decode($account);

            $connection = db($game_id, $db_id);
            $Userbase = M('San_userbase', '', $connection);
            $san_account = M('San_account', '', $connection);
            $arr2 = $Userbase->where("uid=$uid")->find();
            $account = $san_account->where("uid=$uid")->find();
            // var_dump($arr2);exit;
            $data["0"] = $account["creator"];
            $data["1"] = $account["account"];
            $data["2"] = $arr2["uname"];
            $data["3"] = $arr2["uid"];
            $data["4"] = $arr2["level"];

            // 公会
            $gonghui = $redis->get("san_userunioninfo_$uid");
            $gonghui = json_decode($gonghui);
            if ($gonghui != null) {
                if ($gonghui->Unionid == 0) {
                    $data["5"] = "";
                } else {


                    $id = $gonghui->Unionid;
                    $gStu = $redis->get("san_unioninfo_$id");
                    $gStu = json_decode($gStu);
                    $data["5"] = $gStu->unionname;
                }
            } else {
                $data["5"] = "";
            }

            $data["6"] = $arr2["regtime"];
            $data["7"] = " ";


            $data["8"] = $arr2["lastlogintime"];
            $data["9"] = $arr2["ip"]; // IP
            $data["10"] = $arr2["vip"];
            $data["11"] = "";// vip 等级经验
            $data["12"] = $arr2["gem"];
            $data["13"] = $arr2["gold"];
            $data["14"] = $arr2["tili"]; // 体力
            $data["15"] = $arr2["exp"];// exp
            // $data["16"]=$arr2["uid"]; // 武将数量   san_userhero2


            $sun_wujiang = $redis->get("san_userhero2_$uid");

            $sun_wujiang = json_decode($sun_wujiang, true);//var_dump($sun_wujiang["Info"]);
            $data["16"] = count(json_decode($sun_wujiang["Info"], true));

            //  $data["17"]=$arr2["gold"]; // 道具数量   bag
            $San_bag = $redis->get("san_bag_$uid");
            $San_bag = json_decode($San_bag, true);
//var_dump($San_bag["Info"]);
            $data["17"] = count(json_decode($San_bag["Info"], true));


            $zjStu = $redis->get("san_pass_$uid");

            $zjStu = json_decode($zjStu, true);

            $passinfo = $zjStu["passinfo"];
            $passinfo = json_decode($passinfo);
            $len = count($passinfo) - 1;
            $paStu = $passinfo[$len]->id;
            $zhang = substr($paStu, 2, 2) + 1;
            $jie = substr($paStu, 4, 2);
            $data["18"] = "第" . $zhang . "章 第" . $jie . "节"; // 当前章节 san_pass
            //var_dump($data[7]);
            $ruselt = json_encode($data);
            // var_dump($data);
            echo $ruselt;
        }
    }

    public function show()
    {
        //    $_GET["uid"]=531;
        if (isset($_GET["uid"])) {

            $uid = I("get.uid");
            $type = I("get.type");

            $game_id = 1;
            $db_id = I("get.db_id");

            $connection2 = db3($game_id, $db_id);
            $redis = new \Redis();
            $redis->connect($connection2["redis_ip"], $connection2["redis_port"]);
            $redis->auth($connection2["redis_psw"]);
            $redis->select($connection2["redis_db"]);


            $connection = db($game_id, $db_id);
            if ($type == 16) {
//echo 1;
                $sun_wujiang = $redis->get("san_userhero2_$uid");
                $data = json_decode($sun_wujiang, true);
                $data = json_decode($data["Info"], true);

                $data = array_values($data);
                for ($i = 0; $i < count($data); $i++) {
                    $arr[$i]["id"] = $data[$i]["heroid"];
                    $heroid = $data[$i]["heroid"];
                    $Nstu = D("hero")->where("heroid=$heroid")->find();
                    $arr[$i]["name"] = $Nstu["heroname"];
                    $arr[$i]["stars"] = $data[$i]["stars"];
                    $arr[$i]["levels"] = $data[$i]["levels"];
                    $arr[$i]["exp"] = $data[$i]["exp"];
                }

            } else {
                //  $data["17"]=$arr2["gold"]; // 道具数量   bag
                $San_bag = $redis->get("san_bag_$uid");
                $data = json_decode($San_bag, true);

                $data = json_decode($data["Info"], true);
//var_dump($data);exit;
                $data = array_values($data);
                //   var_dump($data);exit;
                for ($i = 0; $i < count($data); $i++) {
                    $arr[$i]["id"] = $data[$i]["itemid"];
                    $itemid = $data[$i]["itemid"];
                    $Nstu = D("goods")->where("itemid=$itemid")->find();
                    $arr[$i]["name"] = $Nstu["itemname"];
                    $arr[$i]["stars"] = $data[$i]["num"];
                    $arr[$i]["levels"] = $data[$i]["itemfescription"];
                    $arr[$i]["exp"] = $Nstu["spiritexp"];
                }
            }
            $ruselt = json_encode($arr);
            echo $ruselt;
        }
    }


    // 封号
    public function block()
    {
        if (isset($_GET["uid"])) {
            $uid = I("get.uid");
            $type = I("get.type");
            $db_id = I("get.db_id");
            if ($type == 1) {
                $day = (int)I("get.day");
                $data = D("db")->where("db_id=$db_id")->find();
                //  var_dump($data);
                $ip = $data["ip"];
                $port = $data["db_port"];
                //  http://IP:port/blockplayer?uid=xxxx&block=1&ay=1
                $token = $_SESSION["token"];
                $md = md5($token);
                //  http://IP:port/blockplayer?uid=xxxx&block=1&ay=1
                $url = "http://$ip:$port/blockplayer?uid=$uid&block=1&day=$day&token=$md";
                //  $url="http://$ip:$port/blockplayer?uid=$uid&block=1&day=$day";
                //  echo $url;exit;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $output = curl_exec($ch);
                curl_close($ch);
                $a = json_decode($output);
                echo $output;
            } else {
                //解封
                $data = D("db")->where("db_id=$db_id")->find();
                //  var_dump($data);
                $ip = $data["ip"];
                $port = $data["db_port"];
                //  http://IP:port/blockplayer?uid=xxxx&block=1&ay=1
                $token = $_SESSION["token"];
                $md = md5($token);
                $url = "http://$ip:$port/blockplayer?uid=$uid&block=0&day=0&token=$md";
                //   $url="http://$ip:$port/blockplayer?uid=$uid&block=0&day=0";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $output = curl_exec($ch);
                curl_close($ch);
                $a = json_decode($output);
                echo $output;
            }
        }
    }


// 禁言
    public function gag()
    {
        //http://IP:port/saveplayer?uid=X  //! 入库
        // http://IP:port/gagplayer?uid=X  //! 禁言
        //  http://IP:port/ungagplayer?uid=X  //! 禁言
//http://IP:port/block?uid=X&blocktime=X
        if (isset($_GET["uid"])) {
            $uid = I("get.uid");
            $db_id = I("get.db_id");
            $type = I("get.type");
            $data = D("db")->where("db_id=$db_id")->find();
            //  var_dump($data);
            $ip = $data["ip"];
            $port = $data["db_port"];
            $token = $_SESSION["token"];
            $md = md5($token);
            if ($type == 0) {
                //解禁
                $url = "http://$ip:$port/ungagplayer?uid=$uid&token=$md";
            } else if ($type == 1) {
                // 禁言
                $url = "http://$ip:$port/gagplayer?uid=$uid&token=$md";
            } else if ($type == -1) {
                //入库
                $url = "http://$ip:$port/saveplayer?uid=$uid&token=$md";
            } else if ($type == -2) {
                $url = "http://$ip:$port/tickplayer?uid=$uid&token=$md";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $a = json_decode($output);
            echo $output;
            //  print_r($a->code);
        }
    }

}