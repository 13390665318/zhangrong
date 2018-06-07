<?php
namespace Home\Controller;

use Think\Controller;
class BaseController extends Controller
{



    public function __construct()
    {
        parent::__construct();



//  菜单筛选


// 登录判断





    }
/**
    private $qudao3=array();//这里声明
    public function _initialize(){
$game_id=1;
        $qudao2=array();
        $db=D("db")->select();
        for($i=0;$i<count($db);$i++){
            $db_ids=$db[$i]["db_id"];
            $connection=db2($game_id,$db_ids);
            $qudao[$i]=M('user','',$connection)->where("source!=' ' and source!='DS' and source!='guest ' ")->field("source")->group("source")->select();
        }

        $qudao2=arr_foreach($qudao);
        for($i=0;$i<count($qudao2);$i++){
            $cid=$qudao2[$i];
            $qudao3[$i]["cid"]=$cid;
            $ras=D("qudao")->where("cid=$cid")->find();
            $qudao3[$i]["name"]=$ras["name"];
        }

        $this->qudao3=$qudao3;//这里赋值
        
    }
**/

}

?>