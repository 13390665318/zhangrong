<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31 0031
 * Time: 上午 10:42
 */

namespace Api\Controller;


class CustomController extends BaseController
{
    public function custon(){
        $need_params     = array('game_id','clothes','mobile_type','game_user_id');
        $optional_params = array('param1','param2','param3','param4');
        $data            = get_param($need_params, $optional_params);
        $arr=array();
        $game_user_id=$data["game_user_id"];
        $stu=D("user")->where("game_user_id=$game_user_id")->find();

        if($stu==null){
            echo_error(1009,"用户不存在");
        }else{
            $game_id=$data["game_id"];
            $rus=D("game")->where("game_id ='$game_id'")->find();
            if($rus==null){
                echo_error(1010,"产品不存在或者尚未记录");
            }else{
                $arr["game_id"]=$data["game_id"];
                $arr["game_name"]=$rus["game_name"];
                $arr["db_id"]=$data["clothes"];
                $arr["mobile_type"]=$data["mobile_type"];
                $arr["game_user_id"]=$data["game_user_id"];
                $arr["user_id"]=$stu["user_id"];
                $arr["param1"]=$data["param1"];
                $arr["param2"]=$data["param2"];
                $arr["param3"]=$data["param3"];
                $arr["param4"]=$data["param4"];
                $arr["time"]=date("Y-m-d H:i:s", time());
                $ru=D("custom")->add($arr);
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