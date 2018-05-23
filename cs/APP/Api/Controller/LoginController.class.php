<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 上午 11:52
 */

namespace Api\Controller;


class LoginController extends BaseController
{
    public function login(){
        $need_params     = array('clothes');
        $optional_params = array('mobile_model','mobile_type','source');
        $data            = get_param($need_params, $optional_params);
        $arr=array();

            $arr["game_id"]=1;
            $arr["clothes"]=$data["clothes"];
            $arr["mobile_type"]=$data["mobile_type"];
            $arr["mobile_model"]=$data["mobile_model"];
            $arr["qudao"]=$data["source"];
            $arr["time"]=date("Y-m-d H:i:s",time());
            $ru=D("zhuce")->add($arr);
            if($ru!=null){
                $list["list"]="success，成功";
                echo_success($list);
            }else{
                echo_error(1003,"数据库插入失败，请联系后台工程师");
            }
    }
    public function qidong(){
        $need_params     = array();
        $optional_params = array('mobile_model','mobile_type','source');
        $data            = get_param($need_params, $optional_params);
        $arr=array();


        $arr["mobile_type"]=$data["mobile_type"];
        $arr["mobile_model"]=$data["mobile_model"];
        $arr["qudao"]=$data["source"];
        $arr["time"]=date("Y-m-d H:i:s",time());
        $ru=D("zhuce2")->add($arr);
        if($ru!=null){
            $list["list"]="success，成功";
            echo_success($list);
        }else{
            echo_error(1003,"数据库插入失败，请联系后台工程师");
        }
    }

    /**
     * 退出
     */
    public function quits(){
        $need_params     = array('sign_id','game_id','clothes');
        $optional_params = array();
        $data            = get_param($need_params, $optional_params);
        $arr["sign_id"]=$data["sign_id"];
        $arr["game_id"]=$data["game_id"];
        $arr["db_id"]=$data["clothes"];
        $rs=D("sign")->where($arr)->find();
        if($rs==null){
            echo_error(1007,"无登录记录");
        }else{
            $ar["end_time"]=date("Y-m-d H:i:s",time());
            // 记录使用时长
            $a = strtotime($rs["start_time"]);
            $b = strtotime($ar["end_time"]);
            $c = $b - $a;
            $is = date("i", $c);
            $ss = date("s", $c);
            $ar["duration"]=($is * 60 + $ss);
            $sign_id=$rs["sign_id"];
            $ru=D("sign")->where("sign_id=$sign_id")->save($ar);
            if($ru==1){
                // 记录使用累计时长
                $rus=D("sign")->where("sign_id=$sign_id")->find();
                $user_id=$rus["user_id"];
                $rus=D("user")->where("user_id=$user_id")->find();
                $stu["user_time"]=$rus["user_time"]+$ar["duration"];
                $stu["end_time"]=date("Y-m-d H:i:s",time());
                $ru=D("user")->where("user_id=$user_id")->save($stu);
                //  在线人数减一
                $time=date("Y-m-d",time());
                $f_time=date("H:")."00";
                $kusd=D("period")->where("time='$time' and f_time='$f_time'")->find();
                $kus["num"]=(int)$kusd["num"]-1;
                if($kus["num"]<0){
                    $kus["num"]=0;
                }
                $period_id=$kusd["period_id"];
                $aesd=D("period")->where("period_id=$period_id")->save($kus);



                $list["list"]="success，退出成功";
                echo_success($list);
            }else{
                echo_error(1008,"退出失败");
            }

        }
        echo_success($rs);
    }

}