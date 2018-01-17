<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 下午 1:50
 */

namespace Api\Model;


use Think\Model;

class UserModel extends Model
{
    public function _initialize(){
       // var_dump($_POST);
        $need_params     = array('game_id','clothes');
        $optional_params = array();
        $data            = get_param($need_params, $optional_params);
        $game_id=$data["game_id"];
        $db_id=$data["clothes"];
        $ABUS=D("db")->where("game_id='$game_id' and db_id='$db_id'")->find();
        if($ABUS==null){
            
            echo_error(1013,"该产品区/服尚未增加，请联系后台管理人员");
        }else{
            $db_name=$ABUS["localhost_db_name"];
            $this->dbName=$db_name;
        }

    }
}