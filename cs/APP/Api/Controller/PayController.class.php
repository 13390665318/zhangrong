<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 7:16
 */

namespace Api\Controller;


class PayController extends  BaseController
{
    public function pay(){
        $need_params     = array('game_id','clothes','game_user_id','game_user_name','source','pay_type','pay_number','money','acer','level');
        $optional_params = array();
        $data            = get_param($need_params, $optional_params);
        $game_user_id=$data["game_user_id"];
        $stu=D("user")->where("game_user_id=$game_user_id")->find();
        $arr=array();
        if($stu==null){
            echo_error(1009,"用户不存在");
        }else {
            $game_id=$data["game_id"];
            $rus=D("game")->where("game_id ='$game_id'")->find();
            if($rus==null){
                echo_error(1010,"产品不存在或者尚未记录");
            }else{
                $arr["game_user_id"]=$data["game_user_id"];
                $arr["game_name"]=$rus["game_name"];
                $arr["user_id"]=$stu["user_id"];
                $arr["db_id"]=$data["clothes"];
                $arr["source"]=$data["source"];
                $arr["game_user_name"]=$data["game_user_name"];
                $arr["pay_type"]=$data["pay_type"];
                $arr["money"]=$data["money"];
                $arr["pay_time"]=date("Y-m-d H:i:s", time());
                $arr["pay_number"]=$data["pay_number"];
                $arr["acer"]=$data["acer"];
                $arr["game_id"]=$data["game_id"];
                $arr["level"]=$data["level"];
                $ru=D("pay")->add($arr);
                if($ru!=null){
                    $list["list"]="success,操作成功";

                    echo_success($list);
                }else{
                    echo_error(1003,"数据库插入失败，请联系后台工程师");
                }
            }
        }

    }

}