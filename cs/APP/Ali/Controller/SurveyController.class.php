<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/30 0030
 * Time: 下午 3:23
 */

namespace Ali\Controller;


use Think\Controller;

class SurveyController extends Controller
{
    public function set(){
        if (isset($_POST["surveyid"])){
		$surveyid=I("post.surveyid");
		$ru=D("survey")->where("surveyid=$surveyid")->find();
	if($ru!=null){
	$list=json_encode($_POST,JSON_UNESCAPED_UNICODE);
           $num=D("survey")->where("surveyid=$surveyid")->save($_POST);
	}else{
	$list=json_encode($_POST,JSON_UNESCAPED_UNICODE);
           $num=D("survey")->add($_POST);
	}	

            
           if($num!=0){
               echo $num;
           }else{
               echo 1;
           }
        }
    }
}