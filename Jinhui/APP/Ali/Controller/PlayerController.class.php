<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 下午 5:37
 */

namespace Ali\Controller;


use Think\Controller;

class PlayerController extends Controller
{
    public function getCurrencyChangeList(){
        if(isset($_POST["zoneIds"])){
            $db_id=I("post.zoneIds");
            $db=D("db")->where("db_id=$db_id")->find();
            if($db_id==0){
                echo -2;
            }else{
                $stu=explode(",",$db_id);
                $stu=array_filter($stu);
                if(count($stu)>1){
                    echo -3;
                }else{
                    $_POST=array_filter($_POST);
                    $game_id=1;
                    $connect=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connect);
                    $account = M('San_account','',$connect);
                    if(isset($_POST["roleId"])){
                        $arr["uid"]=$_POST["roleId"];
                    }

                    if(isset($_POST["roleName"])){
                        $uname=$_POST["roleName"];
                        $r=$Userbase->where("uname = '$uname'")->find();
                        $arr["uid"]=$r["uid"];
                    }
                    if(isset($_POST["accountId"])){
                        $arr["uid"]=$_POST["accountId"];
                    }

                    if(isset($_POST["accountName"])){
                        $acc=$_POST["accountName"];
                        $ru=$account->where("account='$acc'")->find();
                        $arr["uid"]=$ru["uid"];
                    }
                    if(!isset($_POST["changeStartTime"])){
                        echo -5;
                    }else if(!isset($_POST["changeEndTime"])){
                        echo -4;
                    }else{
                        $changeStartTime=strtotime($_POST["changeStartTime"]);
                        $changeEndTime=strtotime($_POST["changeEndTime"]);
                        $time="time>= $changeStartTime and time <= $changeEndTime";

                    }
                    if(isset($_POST["moneyId"])){
                        $type=$_POST["moneyId"];
                        $arr["_string"]="type=$type  and $time";

                    }else{
                        $arr["_string"]="(type=91000001 or type=91000002 or type=91000018 or type=91000020 or type=91000007 or type=91000021 or type=50000001 or type=50000002 or type=50000003) and $time";
                    }

                    if(isset($_POST["changeType"])){
                        $type=$_POST["changeType"];
                        if($type=="add"){
                            $arr['value']  = array('gt',0);
                        }else if($type=="sub"){
                            $arr['value']  = array('lt',0);
                        }
                    }
                    $size=(int)$_POST["size"];

                    $Log=M('San_log','',$connect);
                    $arr=array_filter($arr);
                    if(!isset($_POST["number"])){
                        $number=0;
                    }else{
                        $number=(int)$_POST["number"];
                    }
                   $num= $size*$number;
                    $data=$Log->where($arr)->limit("$num,$size")->select();
//echo $Log->getLastSql();exit;
                    for($i=0;$i<count($data);$i++){
                        $list[$i]["zoneId"]=$db_id;
                        $list[$i]["zoneName"]=$db["clothes"];
                        $uid=$data[$i]["uid"];
                        $a=$account->where("uid=$uid")->find();
                        $list[$i]["accountName"]=$a["account"];
                        $list[$i]["accountId"]=$uid;
                        $list[$i]["roleId"]=$uid;
                        $u=$Userbase->where("uid=$uid")->find();
                        $list[$i]["roleName"]=$u["uname"];
                        $list[$i]["moneyId"]=$data[$i]["type"];
                        $moneyId=$data[$i]["type"];
                        $Gstu=D("goods")->where("itemid=$moneyId")->find();
                        $list[$i]["moneyName"]=$Gstu["itemname"];
                        if($data[$i]["value"]>0){
                            $list[$i]["changeType"]="增加";
                        }else{
                            $list[$i]["changeType"]="减少";
                        }
                        $list[$i]["changeTime"]=date("Y-m-d H:i:s",$data[$i]["time"]);
                        $list[$i]["changeReason"]=$data[$i]["dec"];
                        $list[$i]["changeNum"]=abs($data[$i]["value"]);
                        $list[$i]["balance"]=$data[$i]["cur"];

                    }
                    $lists["totalCount"]=$Log->where($arr)->count();
                    $lists["list"]=$list;
                    $rst=json_encode($lists, JSON_UNESCAPED_UNICODE);
                    echo $rst;
                }
            }
        }
    }

}