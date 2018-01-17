<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/1 0001
 * Time: 上午 10:07
 */

namespace Doc\Controller;


use Think\Controller;

class SurveyController extends Controller
{
        public function set(){
            if(isset($_GET["uid"]) && isset($_GET["db_id"])) {

                $uid = I("get.uid");
                $db_id = I("get.db_id");
                $time = date("Y-m-d H:i:s", time());

                $arr = D("survey")->where("starttime<='$time' and endtime>='$time'")->order("id desc")->select();
              
                for ($i = 0; $i < count($arr); $i++) {
                    $clothes = $arr[$i]["zoneids"];
                    if ($clothes == 0) {
                        $data[$i]["surveyid"] = $arr[$i]["surveyid"];
			$data[$i]["id"] = $arr[$i]["id"];
                    } else {
                        $stu = explode(",", $clothes);
                        $con = array_filter($stu);
                        if (in_array($db_id, $con) != null) {
                            $data[$i]["surveyid"] = $arr[$i]["surveyid"];
			$data[$i]["id"] = $arr[$i]["id"];
                        }
                    }
                }
                $data=array_merge($data);
		// var_dump($data);
                for ($i = 0; $i <count($data); $i++) {
                    $mailid = $data[$i]["surveyid"];
		  $id-$data[$i]["id"];
                    $ru = D("ereward")->where("uid=$uid and mailid=$mailid and zoneid=$db_id")->find();
                    if ($ru == null) {
                        $ku[$i] = $mailid;
                    }
                }
               $pos = array_search(max($ku), $ku);
// var_dump($pos);
                if ($ku[$pos] != null) {
		//var_dump($ku[$pos]);
                    $id = $ku[$pos];
                    $Emai = D("survey")->where("surveyid=$id")->find();
//var_dump($Emai);
                    $surveyurl = $Emai["surveyurl"];
                    $zone_id = $db_id;
                    $role_id = $uid;
                    $game_id = 1;
                    $connect = db($game_id,$db_id);
                    $Userbase = M('San_userbase', '', $connect);
                    $uname = $Userbase->where("uid=$uid")->find();
                   // $role_name =urlencode($uname["uname"]);
 $role_name=$uname["uname"];
                    $url = "$surveyurl?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";
			//$url2="https://gm-api.aligames.com/api/gm/end_survey?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";
			$url2="https://gm-api.aligames.com/api/gm/end_survey?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";

                    $redirect = urlencode($url2);
                    $list["code"] = 0;
                    $list["list"] = "$surveyurl?zone_id=$zone_id&role_id=$role_id&role_name=$role_name&redirect=$redirect";

		//  $list["list"]=$url ;
                    }else{
                    $list["code"] = 500;
                    $list["list"] = null;
                }
		//$list = json_encode($list,JSON_UNESCAPED_SLASHES);
$list=json_encode($list,JSON_UNESCAPED_UNICODE);
$ruselt = str_replace("\\/", "/", $list);
                    echo $ruselt ;

            }
        }

 public function suyset(){
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $uid=I("get.uid");
            $level=I("get.level");
            $time = date("Y-m-d H:i:s", time());
            $arr = D("survey")->where("starttime<='$time' and endtime>='$time' and startRolelevel<=$level and $level<=endRolelevel")->select();
//var_dump($arr);
 for ($i = 0; $i < count($arr); $i++) {
                    $clothes = $arr[$i]["zoneids"];
                    if ($clothes == 0) {
                        $data[$i]["surveyid"] = $arr[$i]["surveyid"];
			$data[$i]["id"] = $arr[$i]["id"];
                    } else {
                        $stu = explode(",", $clothes);
                        $con = array_filter($stu);
                        if (in_array($db_id, $con) != null) {
                            $data[$i]["surveyid"] = $arr[$i]["surveyid"];
			$data[$i]["id"] = $arr[$i]["id"];
                        }
                    }
                }
                $data=array_merge($data);
		// var_dump($data);
                for ($i = 0; $i <count($data); $i++) {
                    $mailid = $data[$i]["surveyid"];
		  $id-$data[$i]["id"];
                    $ru = D("ereward")->where("uid=$uid and mailid=$mailid and zoneid=$db_id")->find();
                    if ($ru == null) {
                        $ku[$i] = $mailid;
                    }
                }
               $pos = array_search(max($ku), $ku);
// var_dump($pos);
                if ($ku[$pos] != null) {
		//var_dump($ku[$pos]);
                    $id = $ku[$pos];
                    $Emai = D("survey")->where("surveyid=$id")->find();
//var_dump($Emai);
                    $surveyurl = $Emai["surveyurl"];
                    $zone_id = $db_id;
                    $role_id = $uid;
                    $game_id = 1;
                    $connect = db($game_id,$db_id);
                    $Userbase = M('San_userbase', '', $connect);
                    $uname = $Userbase->where("uid=$uid")->find();
                   // $role_name =urlencode($uname["uname"]);
 $role_name=$uname["uname"];
                    $url = "$surveyurl?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";
			//$url2="https://gm-api.aligames.com/api/gm/end_survey?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";
			$url2="https://gm-api.aligames.com/api/gm/end_survey?zone_id=$zone_id&role_id=$role_id&role_name=$role_name";

                    $redirect = urlencode($url2);
                    $list["code"] = 0;
                    $list["list"] = "$surveyurl?zone_id=$zone_id&role_id=$role_id&role_name=$role_name&redirect=$redirect";

		//  $list["list"]=$url ;
                    }else{
                    $list["code"] = 500;
                    $list["list"] = null;
                }
		//$list = json_encode($list,JSON_UNESCAPED_SLASHES);
$list=json_encode($list,JSON_UNESCAPED_UNICODE);
$ruselt = str_replace("\\/", "/", $list);
                    echo $ruselt ;

           

        }
    }

}