<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/30 0030
 * Time: 下午 4:08
 */

namespace Home\Controller;


use Think\Controller;

class MailController extends Controller
{
    public function setSingle(){
        if(isset($_POST["mailId"])) {
            $mailId = I("post.mailId");
            $rus = D("survey")->where("surveyid=$mailId")->find();
            $ruSdata=I("post.ruSdata");
            if($rus!=null){
                // 入库
                $stu["mailId"]=$mailId;
                $stu["zoneId"]=I("post.zoneId");
                $uStu=I("uid");
                $uid=substr($uStu, 0, -1);
                $uid=substr($uid,1);
                $user=explode(",",$uid);
	  for($i=0;$i<count($user);$i++){
                    $stu["uid"]=$user[$i];
                    $num=D("ereward")->add($stu);
                }
            }
	   $db_id=I("post.zoneId");
            $data=D("db")->where("db_id=$db_id")->find();
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
            echo $output;


        }
    }
}