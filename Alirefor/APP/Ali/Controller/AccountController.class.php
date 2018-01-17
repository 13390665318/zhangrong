<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 下午 7:10
 */

namespace Ali\Controller;


use Think\Controller;

class AccountController extends Controller
{


public function getList(){
        if(isset($_POST["zoneIds"])){
            $db_id=I("post.zoneIds");
            if($db_id==0){
                echo -2;exit;
            }else{
                $stu=explode(",",$db_id);
                $stu=array_filter($stu);
                if(count($stu)>1){
                    echo -3;
                }else{
                    $game_id=1;
                    $connect=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connect);
                    $account = M('San_account','',$connect);
                    $data=array_filter($_POST);
                    if(isset($data["accountIds"])){ //echo 1;exit;
                        $user=$data["accountIds"];
                        $user_id=$stu=explode(",",$user);
                        $user_id=array_filter($user_id);
                        if($user_id){
                            $where=null;
                            for($i=0;$i<count($user_id);$i++){
                                $where = "uid = $user_id[$i] or ".$where;
                            }
                            $con["_string"]="(".substr($where,0,strlen($where)-3).")";
                        }else{
                            $con["uid"]=$user_id;
                        }
                    }
                    $con=array_filter($con);
                    $size=$data["size"];
                    if(!isset($data["number"])){
                        $number=0;
                    }else{
                        $number=(int)$data["number"];
                    }
                    $num= $size*$number;
                    $arr=$account ->where($con)->limit("$num,$size")->select();
		 //echo $account->getLastSql();exit;
                    $db=D("db")->where("db_id=$db_id")->find();
                    for($i=0;$i<count($arr);$i++){
                        $list[$i]["accountId"]=$arr[$i]["uid"];
                        $list[$i]["accountName"]=$arr[$i]["account"];
                        $list[$i]["channelId"]=$arr[$i]["uname"];
                        $list[$i]["channelName"]=$arr[$i]["creator"];
                        $list[$i]["zoneId"]=$db_id;//服务器 id
                        $list[$i]["zoneName"]=$db["clothes"];//服务器名称
                        $list[$i]["createTime"]=date("Y-m-d H:i:s",$arr[$i]["time"]);//角色创建时间
                        $list[$i]["payAmount"]=$arr[$i]["uname"];//累计充值金额
                        $list[$i]["status"]=1;//当前状态
                        $list[$i]["extInfo"]=$arr[$i]["uname"];//扩展信息
                    }
                    $totalCount=$Userbase->where($con)->count();
		  $lists["list"]=$list;
                    $lists["totalCount"]=$totalCount;
                    $rst=json_encode($lists, JSON_UNESCAPED_UNICODE);
                    echo $rst;
                }
            }
        }
    }




        public function getRoleList(){
//$_POST["zoneIds"]=2;
            if(isset($_POST["zoneIds"])){
                $db_id=I("post.zoneIds");
                if($db_id==0){
                    echo -2;exit;
                }else{
                    $stu=explode(",",$db_id);
                    $stu=array_filter($stu);
                    if(count($stu)>1){
                        echo -3;
                    }else{
                        $game_id=1;
                        $connect=db($game_id,$db_id);
                        $Userbase = M('San_userbase','',$connect);
                        $account = M('San_account','',$connect);
                        $data=array_filter($_POST);
                        if(isset($data["roleIds"])){
                                $user=$data["roleIds"];
                                $user_id=$stu=explode(",",$user);
                                $user_id=array_filter($user_id);
                                if($user_id){
                                    $where=null;
                                    for($i=0;$i<count($user_id);$i++){
                                        $where = "uid = $user_id[$i] or "  .$where;
                                    }
                                    $con["_string"]="(".substr($where,0,strlen($where)-3).")";
                                }else{
                                    $con["uid"]=$user_id;
                                }
                        }
                        $con=array_filter($con);
                        $size=$data["size"];
                        if(!isset($data["number"])){
                            $number=0;
                        }else{
                            $number=(int)$data["number"];
                        }
                        $num= $size*$number; 
                        $arr=$Userbase ->where($con)->limit("$num,$size")->select();// 
                        $db=D("db")->where("db_id=$db_id")->find();//echo D("db")->getLastSql();exit;
	
                        for($i=0;$i<count($arr);$i++){
                            $list[$i]["roleId"]=$arr[$i]["uid"];
                            $list[$i]["roleName"]=$arr[$i]["uname"];
                            $list[$i]["level"]=$arr[$i]["level"];
                            $list[$i]["accountId"]=$arr[$i]["uid"];
                            $list[$i]["platformId"]=$arr[$i]["uid"];
                            $list[$i]["channelId"]=$arr[$i]["uname"];
                            $list[$i]["channelName"]=$arr[$i]["uname"];
                            $list[$i]["zoneId"]=$db_id;//服务器 id
                            $list[$i]["zoneName"]=$db["clothes"];//服务器名称
                            $list[$i]["createTime"]=$arr[$i]["regtime"];//角色创建时间
                            $list[$i]["payAmount"]=$arr[$i]["uname"];//累计充值金额
                            $list[$i]["status"]=1;//当前状态
                            $list[$i]["extInfo"]=$arr[$i]["uname"];//扩展信息
                        }
 		$totalCount=$Userbase->where($con)->count();
                    $lists["list"]=$list;
                    $lists["totalCount"]=$totalCount;
                    $rst=json_encode($lists, JSON_UNESCAPED_UNICODE);
                    echo $rst;
                    }
                }
            }
        }
	//推送账号角色状态
    public function setState(){
        if(isset($_POST["zoneId"])){
            $db_id=I("post.zoneId");
//echo $db_id;exit;
            if($db_id==0){
                echo -2;exit;
            }else{
                $stu=explode(",",$db_id);
                $stu=array_filter($stu);
                if(count($stu)>1){
                    echo -3;
                }else{
                    $type=I("post.opType");
                    $uid=I("post.accountId");
                    $game_id=1;
                    $db=D("db")->where("db_id=$db_id")->find();
                    $ip=$db["ip"];
                    $port=$db["db_port"];
                    $stime=I("post.startTime");
                    $etime=I("post.endTime");
                    $day=count_days($stime,$etime);
                 //   2下线 11冻结、12解冻 21禁言、22解禁 31封IP、32解封IP 41封设备、42解封设备
                    if($type==2){
                        $url="http://$ip:$port/tickplayer?uid=$uid";
                    }else if($type==11){
                        $url="http://$ip:$port/blockplayer?uid=$uid&block=1&day=$day";
                    }else if($type==12){
                        $url="http://$ip:$port/blockplayer?uid=$uid&block=0&day=0";
                    }else if($type==21){
                        $url="http://$ip:$port/gagplayer?uid=$uid";
                    }else if($type==22){
                        $url="http://$ip:$port/ungagplayer?uid=$uid";
                    }else if($type==31){
                        echo  -1;exit;
                    }else if($type==32){
                        echo  -1;exit;
                    }else if($type==41){
                        echo  -1;exit;
                    }else if($type==42){
                        echo  -1;exit;
                    }
//echo $url;exit;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $a=json_decode($output);
                    echo $output;

                }
            }
        }
    }

}
