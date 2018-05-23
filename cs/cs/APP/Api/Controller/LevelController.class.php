<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 5:19
 */

namespace Api\Controller;


class LevelController extends BaseController
{
    /**
     * 用户等级
     */
    public function level(){
        $need_params     = array('game_user_id','game_id','clothes','level');
        $optional_params = array('mobile_model');
        $data            = get_param($need_params, $optional_params);
        $arr=array();
        $arr["game_user_id"]=$data["game_user_id"];
        $arr["game_id"]=$data["game_id"];
        $arr["db_id"]=$data["clothes"];
	$strRUS=D("user")->where($arr)->find();
        if($strRUS==null){
            echo_error(1009,"用户不存在");
        }else{
            $stu=array();
            $stu["level"]=$data["level"];

            $rus=D("user")->where($arr)->save($stu);
            if($rus==null){
                echo_error(1,"接口奔溃，请联系后台程序猿");
            }else{
                $list["list"]="操作成功,success";
                echo_success($list);
            }
        }


    }

}