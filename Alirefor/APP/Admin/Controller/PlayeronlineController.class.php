<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:34
 */

namespace Admin\Controller;


class PlayeronlineController extends BaseController
{
    public function index(){
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
         }
        // 游戏区/服
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
        $Userbase = M('San_userbase','',$connection);
        $San_account = M('San_account','',$connection);
        $arr=$Userbase->where("lastlogintime>lastupdtime")->select();
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $Rus=$San_account->where("uid=$uid")->find();
            $arr[$i]["account"]=$Rus["account"];
            $arr[$i]["creator"]=$Rus["creator"];
            $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
        }
        $sum=count($arr);
        $this->assign("sum",$sum);
        $this->assign("arr",$arr);
        $this->display();
    }
    public function playselect(){
        if (isset($_GET["db_id"])){
            if(isset($_SESSION["game_id"])) {
                $game_id = $_SESSION["game_id"];
            } else {
                $game_id = 1;
             }
            $db_id=I("get.db_id");
            $game_user_name=I("get.game_user_name");
            $game_user_id=I("get.game_user_id");
            $account=I("get.account");
            if($account==null){
                if($game_user_name==null){
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);
                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>lastupdtime and  uid = $game_user_id")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }else if($game_user_id==null){
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);
                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>lastupdtime  and uname like '%$game_user_name%' ")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }else{
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);
                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>lastupdtime  and uname like '%$game_user_name%' and  uid = $game_user_id")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }

            }else{
                $connection=db($game_id,$db_id);
                $Userbase = M('San_userbase','',$connection);
                $San_account = M('San_account','',$connection);
                $Rus=$San_account->where("account='$account'")->find();
                if($Rus!=null){
                    $uid=$Rus["uid"];
                    $arr[0]=$Userbase->where("lastlogintime>lastupdtime  and   uid = $uid")->find();
                    $arr[0]["account"]=$account;
                    $arr[0]["creator"]=$Rus["creator"];
                    $arr[0]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
                }else{
                    $arr[0]=null;
                }

            }
           $data=json_encode($arr);
           echo $data;
        }
    }
}