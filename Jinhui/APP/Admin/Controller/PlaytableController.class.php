<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:35
 */

namespace Admin\Controller;
header ( "Content-type:text/html;charset=utf-8" );

class PlaytableController extends BaseController
{
    public function index(){

        if(isset($_SESSION["game_id"]) ) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
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
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $stime=I("get.start_time");
            $etime=I("get.end_time");
        }else{
            $stime=date("Y-m-01 00:00:00",time());
            $etime=date("Y-m-d H:i:s",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $Userbase = M('San_userbase','',$connection);
        $San_account = M('San_account','',$connection);
//var_dump($_GET["game_user_name"]);
        if($_GET["game_user_name"]!=1){


                $uname=I("get.game_user_name");
                $where["uname"]=array('like',"%$uname%");

            if(isset($_GET["game_user_id"])){
                if(I("get.game_user_id")!=1){
                    $where["uid"]=I("get.game_user_id");
                }else{
                    $where["uid"]=null;
                }
            }else{
                $where["uid"]=null;
            }
            $where['_string'] = "regtime>='$stime' AND regtime<='$etime'";
            $con=array_filter($where);
//var_dump( $con);
            $count      =$Userbase->where($con)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
            $show       = $Page->show();// 分页显示输出
            $this->assign("page",$show);// 赋值分页输出
            $arr=$Userbase->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
//var_dump($arr);
            for($i=0;$i<count($arr);$i++){
                $uid=$arr[$i]["uid"];
                $Rus=$San_account->where("uid=$uid")->find();
                $arr[$i]["account"]=$Rus["account"];
                $arr[$i]["creator"]=$Rus["creator"];
                $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
            }
        }else {
		if(I("get.account")!=1){
            $where["account"]=I("get.account");
		}
            if(I("get.creator")==0){
                $where["creator"]=null;
            }else{
                $where["creator"]=I("get.creator");
            }
            $where['regtime'] = array(array('gt',$stime),array('lt',$etime)) ;
            if($_GET["game_user_id"]!=1){
                $where["uid"]=I("get.game_user_id");
            }else{
                $where["uid"]=null;
            }
            $con=array_filter($where);
            $count      =$San_account->where($con)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
            $show       = $Page->show();// 分页显示输出
            $this->assign("page",$show);// 赋值分页输出
            $stu=$San_account->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
            for($i=0;$i<count($stu);$i++){
                $uid=$stu[$i]["uid"];
                $arr[$i]=$Userbase->where("uid=$uid")->find();
                $arr[$i]["account"]=$stu[$i]["account"];
                $arr[$i]["uid"]=$stu[$i]["uid"];
                $arr[$i]["creator"]=$stu[$i]["creator"];
                $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
                if($arr[$i]["Summoney"]==null){
                    $arr[$i]["Summoney"]=0;
                }
            }
     }

        $Pingtai=$San_account->distinct(true)->field('creator')->select();
        $this->assign("Pingtai",$Pingtai);

        $this->assign("arr",$arr);
        $this->display();
    }
public function detial(){
  // $_GET["uid"]=530;

        if(isset($_GET["uid"])){

            $uid=I("get.uid");
            $game_id = $_SESSION["game_id"];
            $db_id=I("get.db_id");
            $connection=db($game_id,$db_id);
            $Userbase = M('San_userbase','',$connection);
            $San_account = M('San_account','',$connection);
            $San_pass=M('San_pass','',$connection);
            $San_userunioninfo=M('San_userunioninfo','',$connection);
            $San_unioninfo=M('San_unioninfo','',$connection);
            $San_pass=M('San_pass','',$connection);
            $arr1=$San_account->where("uid=$uid")->find();
            $arr2=$Userbase->where("uid=$uid")->find();
            $data["0"]=$arr1["creator"];
            $data["1"]=$arr1["account"];
            $data["2"]=$arr2["uname"];
            $data["3"]=$arr2["uid"];
            $data["4"]=$arr2["level"];
              // 公会
            $gonghui=$San_userunioninfo->where("uid=$uid")->find();
            if($gonghui!=null){
                if($gonghui["unionid"]==0){
                    $data["5"]="";
                }else{
                    $id=$gonghui["unionid"];
                    $gStu=$San_unioninfo->where("id=$id")->find();
                    $data["5"]=$gStu["unionname"];
                }
            }else{
                $data["5"]="";
            }
            $data["6"]=$arr2["regtime"];
            $data["7"]=$arr2["uid"]; // 在线时间
            $onStu=D("user")->where("game_user_id=$uid")->find();
            $onTime=$onStu["user_time"];
            $data["7"]=round($onTime/3600,4);
            $data["8"]=$arr2["lastlogintime"];
            $data["9"]=$arr2["ip"]; // IP
            $data["10"]=$arr2["vip"];
            $data["11"]="";// vip 等级经验
            $data["12"]=$arr2["gem"];
            $data["13"]=$arr2["gold"];
            $data["14"]=$arr2["tili"]; // 体力
            $data["15"]=$arr2["exp"];// exp
           // $data["16"]=$arr2["uid"]; // 武将数量   san_userhero2
            $San_userhero2=M('San_userhero2','',$connection);
            $data["16"]=$San_userhero2->where("uid=$uid")->count();
          //  $data["17"]=$arr2["gold"]; // 道具数量   bag
            $San_bag=M('San_bag','',$connection);
            $data["17"]=$San_bag->where("uid=$uid")->count();
            $zjStu=$San_pass->where("uid=$uid")->find();
            $passinfo=$zjStu["passinfo"];
            $passinfo=json_decode($passinfo);
            $len=count($passinfo)-1;
            $paStu=$passinfo[$len]->id;
            $zhang= substr($paStu,2,2)+1;
            $jie= substr($paStu,4,2);
            $data["18"]="第".$zhang."章 第".$jie."节"; // 当前章节 san_pass

            $ruselt=json_encode($data);
            echo $ruselt;
        }
}

    public function show(){
    //    $_GET["uid"]=531;
    if (isset($_GET["uid"])){
        $uid=I("get.uid");
        $type=I("get.type");
      //  $type=17;
        $game_id = $_SESSION["game_id"];
        $db_id=I("get.db_id");
      //  $db_id=2;
        $connection=db($game_id,$db_id);
        if($type==16){
            // $data["16"]=$arr2["uid"]; // 武将数量   heroequ2
            $San_userhero2=M('San_userhero2','',$connection);
            $data=$San_userhero2->where("uid=$uid")->select();
            for($i=0;$i<count($data);$i++){
                $arr[$i]["id"]=$data[$i]["heroid"];
                $heroid=$data[$i]["heroid"];
                $Nstu=D("hero")->where("heroid=$heroid")->find();
                $arr[$i]["name"]=$Nstu["heroname"];
                $arr[$i]["stars"]=$data[$i]["stars"];
                $arr[$i]["levels"]=$data[$i]["levels"];
                $arr[$i]["exp"]=$data[$i]["exp"];
            }

        }else{
            //  $data["17"]=$arr2["gold"]; // 道具数量   bag
            $San_bag=M('San_bag','',$connection);
            $data=$San_bag->where("uid=$uid")->select();
            for($i=0;$i<count($data);$i++){
                $arr[$i]["id"]=$data[$i]["itemid"];
                $itemid=$data[$i]["itemid"];
                $Nstu=D("goods")->where("itemid=$itemid")->find();
                $arr[$i]["name"]=$Nstu["itemname"];
                $arr[$i]["stars"]=$data[$i]["num"];
                $arr[$i]["levels"]=$data[$i]["itemfescription"];
                $arr[$i]["exp"]=$Nstu["spiritexp"];
            }
        }
        $ruselt=json_encode($arr);
        echo $ruselt;
    }
    }

// 禁言
public function gag(){
        //http://IP:port/saveplayer?uid=X  //! 入库
        // http://IP:port/gagplayer?uid=X  //! 禁言
       //  http://IP:port/ungagplayer?uid=X  //! 禁言

        if(isset($_GET["uid"])){
            $uid=I("get.uid");
            $db_id=I("get.db_id");
            $type=I("get.type");
            $data=D("db")->where("db_id=$db_id")->find();
         //  var_dump($data);
           $ip=$data["ip"];
           $port=$data["db_port"];
            if($type==0){
                //解禁
                $url="http://$ip:$port/ungagplayer?uid=$uid";
            }else if($type==1){
                // 禁言
                $url="http://$ip:$port/gagplayer?uid=$uid";
            }else if(type==-1){
                //入库
                $url="http://$ip:$port/saveplayer?uid=$uid";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
           $a=json_decode($output);
            echo $output;
          //  print_r($a->code);
        }
}

}