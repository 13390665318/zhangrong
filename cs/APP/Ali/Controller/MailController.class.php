<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9 0009
 * Time: 上午 11:31
 */

namespace Ali\Controller;


use Think\Controller;

class MailController extends Controller
{
    public function setGroup(){
        if(isset($_POST["mailId"])) {

//$rst=json_encode($_POST, JSON_UNESCAPED_UNICODE);
              //      echo $rst;

            $mailId = I("post.mailId");

            $ruSdata=$_POST["ruSdata"];
//echo $ruSdata;exit;

                // 入库
                $stu["reward_id"]=(int)$_POST["mailId"];
            $stu["begin_time"]=$_POST["ruleStartTime"];
            $stu["end_time"]=$_POST["ruleEndTime"];
            $stu["goods_ids"]=$_POST["awardData"];
            $stu["title"]=$_POST["title"];
            $stu["content"]=$_POST["content"];
            $stu["clothes"]=$_POST["zoneId"];
            $stu["blevel"]=$_POST["roleLevelMin"];
            $stu["elevel"]=$_POST["roleLevelMax"];
            $stu["status"]=1;
            $stu["time"]=date("Y-m-d H:i:s",time());
            $stu["sender"]="GM";
            $stu["status2"]=0;

            $stu["creator"]=I("post.channelIds");
$zoneId=$_POST["zoneId"];

//echo $zoneId;exit;
if($zoneId!=0){
   
$dbid=explode(",",$zoneId);
    $dbid=array_filter($dbid);
    $stu["begin_clothes"]=$dbid[0];
    $stu["end_clothes"]=$dbid[count($dbid)-1];
    for($i=0;$i<count($dbid);$i++){
       $db_id=$dbid[$i];
       $db[$i]=D("db")->where("db_id=$db_id")->find();
    }
//echo 123;exit;
}else{
     $db=D("db")->order("db_id desc")->select();
    $stu["begin_clothes"]=$db[0]["db_id"];
    $stu["end_clothes"]=$db[count($db)-1]["db_id"];
//echo 321;exit;
}
// 入库
            $r=D("reward")->add($stu);
for($i=0;$i<count($db);$i++){
    $ip=$db[$i]["ip"];
    $port=$db[$i]["db_port"];
    $url = "http://$ip:$port/sendmail?context=$ruSdata";
//echo $url;exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $a = json_decode($output);

    if ($a->code == 0) {
        //改变状态
      //  $arr["status"]=1;
      //  $rs=D("reward")->where("reward_id=$r")->save($arr);
       $m= 0;
    }else{
        $m=-1;
 echo -1;exit;
    }

	
}

echo "OK";

           

        }
    }


}