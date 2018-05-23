<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:16
 */

namespace Api\Controller;


class CountryController extends BaseController
{
    public function add(){
        /*$need_params     = array('game_user_id','game_id','clothes','country');
        $optional_params = array('mobile_model');
        $data            = get_param($need_params, $optional_params);
        $game_id = $data["game_id"];
        $Rus = D("game")->where("game_id='$game_id'")->find();
        if ($Rus==null)
        {
            echo_error(1011, "该游戏尚未入库，请联系后台管理人员");
        } else {
            if ($Rus["status" == 0]) {
                echo_error(1012, "该游戏已经下线");
            } else {
                // 判断区/服是否添加
                $db_id = $data["clothes"];
                $Kus = D("db")->where("game_id=$game_id and db_id=$db_id")->find();
                if ($Kus == null) {
                    echo_error(1013, "该产品区\服尚未增加，请联系后台管理人员");
                } else {
                    $game_user_id = $data["game_user_id"];
                    $Lus = D("user")->where("game_user_id=$game_user_id")->find();
                    if ($Lus == null) {
                        echo_error(1009, "	用户不存在");
                    } else {
                        $arr["country"] = $data["country"];
                        $Lus = D("user")->where("game_user_id=$game_user_id")->save($arr);
                         if ($Lus == 1) {
                            $list["list"] = "修改成功,success";
                            echo_success($list);
                        } else {
                            echo_error(1, "接口奔溃，请联系后台程序猿");
                        }
                    }
                }
            }
        }*/
    }

}