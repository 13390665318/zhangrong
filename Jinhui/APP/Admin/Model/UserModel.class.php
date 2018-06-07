<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 上午 10:45
 */

namespace Admin\Model;


use Think\Model;

class UserModel extends Model
{

    public function _initialize(){
        $game_id=$_SESSION["game_id"];
        $db_id=$_SESSION["db_id"];
        $rus=D("db")->where("game_id=$game_id and  db_id='$db_id'")->find();

        if($rus==null){
            echo "<script>alert('数据错误，请重新选择');location.href='./index.php?m=Admin&c=Index&a=index'</script>";
        }else{
            $db_game=$rus["localhost_db_name"];
            $this->dbName=$db_game;
        }

    }
}