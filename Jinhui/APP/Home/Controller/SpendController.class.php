<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 12:14
 */

namespace Home\Controller;


class SpendController extends BaseController
{
    public function index(){

        if (isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;

        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            if(isset($_SESSION["db_id"])){

                $db_id= $_SESSION["db_id"];
            }else{
                $db_id=$clostu[0]["db_id"];
                $_SESSION["db_id"]=$db_id;
            }

        }
        $this->assign("db_id",$db_id);
        // 时间
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");
        }else{
            $Stime=date("Y-m-d 00:00:00",time());
            $Etime=date("Y-m-d H:i:s",time());
        }
        $Betime=strtotime($Stime);
        $Entime=strtotime($Etime);
        $con["_string"]="time>=$Betime AND time<=$Entime";
        $this->assign("Stime",$Stime);
        $this->assign("Etime",$Etime);
if($_GET){
        if(isset($_GET["money_type"])){
            $con["money_type"]=I("get.money_type");
        }else{
            $con["money_type"]="91000001";// 默认金币消费
        }
$money_type=$con["money_type"];
//echo $money_type;
        $this->assign("money_type",$money_type);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        if(isset($_GET["game_user_name"])){
            $game_user_name=I("get.game_user_name");
            if($game_user_name!=null){
                $where["uname"]=array('like', "%$game_user_name%");

                $uname=$Userbase->where($where)->find();
                $con["uid"]=$uname["uid"];
            }else{
                if(isset($_GET["game_user_id"])){
                    if($_GET["game_user_id"]==null){
                        $con["uid"]=null;
                    }else{
                        $con["uid"]=I("get.game_user_id");
                    }

                }else{
                    $con["uid"]=null;
                }
            }
        }else{
            if(isset($_GET["game_user_id"])){
                if($_GET["game_user_id"]==null){
                    $con["uid"]=null;
                }else{
                    $con["uid"]=I("get.game_user_id");
                }

            }else{
                $con["uid"]=null;
            }
        }
        $con["value"]=array('lt',0);

        $San_log = M('San_log','',$connection);
        if(isset($_GET["type"])){
            $type=I("get.type");
        }else{
            $type=1;
        }
        $con=array_filter($con);
//var_dump($con);exit;
        if($type==1){
            // 总计查询
            $TyStu=$San_log->where($con)->distinct(true)->field('dec')->select();
 //echo $San_log->getLastSql();
            $sum=$San_log->where($con)->sum("value");
            $sum=abs($sum);
            for($i=0;$i<count($TyStu);$i++){
                $arr[$i]["name"]=$TyStu[$i]["dec"];
                $con["dec"]=$TyStu[$i]["dec"];
                $arr[$i]["count"]=$San_log->where($con)->sum("value");
                $arr[$i]["count"]=abs($arr[$i]["count"]);
                if($arr[$i]["count"]==null){
                    $arr[$i]["count"]=0;
                }
                $arr[$i]["num"]=round($arr[$i]["count"]/$sum,4)*100;
            }

        }
   
        
        $this->assign("arr",$arr);
        $this->assign("sum",$sum);
        $this->display();
}else{
 $this->display();
}
       
         


        



    }

    /** c查询请求
     *  num:num,
    clothes:clothes,
    game_user_id:game_user_id,
    start_time:start_time,
    end_time:end_time,
    type:type,
     */
    public function money_type(){
        if(isset($_GET["num"])){
            if (isset($_SESSION["game_id"]) ) {
                $game_id = $_SESSION["game_id"];
            } else {
                $game_id = 1;
            }
            $money_type=I("get.num");
            $db_id=I("get.db_id");
            $game_user_id=I("get.game_user_id");
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");
            $type=I("get.type");
            $connection=db($game_id,$db_id);
            $San_log = M('San_log','',$connection);
            if($game_user_id==null){
                if($type==1){
                    $stime=strtotime("$Stime 00:00:00");
                    $etime=strtotime("$Etime 23:59:59");
                    $TyStu=$San_log->where("type=$money_type and time>='$stime' and time<='$etime' and value<0")->distinct(true)->field('dec')->select();
                    if(count($TyStu)==0){
                        // $arr[0]["name"]="";
                        //  $arr[0]["count"]=0;
                        //  $arr[0]["num"]=0;
                        $arr=null;
                    }else{
                        $sum=$San_log->where("type=$money_type and time>='$stime' and time <='$etime' and value<0 ")->sum("value");
                        $sum=abs($sum);
                        for($i=0;$i<count($TyStu);$i++){
                            $arr[$i]["name"]=$TyStu[$i]["dec"];
                            $dec=$TyStu[$i]["dec"];
                            $arr[$i]["count"]=$San_log->where("type=$money_type and time>='$stime' and time <='$etime'and `dec` = '$dec' and value< 0 ")->sum("value");
                            $arr[$i]["count"]=abs($arr[$i]["count"]);
                            if($arr[$i]["count"]==null){
                                $arr[$i]["count"]=0;
                            }

                            $arr[$i]["num"]=round($arr[$i]["count"]/$sum,4)*100;
                        }
                    }

                }else {
                }
            }else{
                if($type==1){
                    $stime=strtotime("$Stime 00:00:00");
                    $etime=strtotime("$Etime 23:59:59");
                    $TyStu=$San_log->where("type=$money_type and time>='$stime' and time<='$etime' and uid=$game_user_id")->distinct(true)->field('dec')->select();
                    if(count($TyStu)==0){
                        $arr=null;
                    }else {
                        $sum = $San_log->where("type=$money_type and uid=$game_user_id and time>='$stime' and time <='$etime' and value<0 ")->sum("value");
                        $sum=abs($sum);
                        for ($i = 0; $i < count($TyStu); $i++) {
                            $arr[$i]["name"] = $TyStu[$i]["dec"];
                            $dec = $TyStu[$i]["dec"];
                            $arr[$i]["count"] = $San_log->where("type=$money_type and uid=$game_user_id and time>='$stime' and time <='$etime'and `dec` = '$dec' and value< 0 ")->sum("value");
                            $arr[$i]["count"]=abs($arr[$i]["count"]);
                            if($arr[$i]["count"]==null){
                                $arr[$i]["count"]=0;
                            }
                            $arr[$i]["num"] = round($arr[$i]["count"] / $sum, 4) * 100;
                        }
                    }
                }else {
                    $day = count_days($Stime, $Etime);
                    $stime = strtotime("$Stime 00:00:00");
                    $etime = strtotime("$Etime 23:59:59");
                    $TyStu = $San_log->where("type=$money_type and time>='$stime' and time<='$etime' and uid=$game_user_id")->distinct(true)->field('dec')->select();
                    if (count($TyStu) == 0) {
                        $arr=null;
                    } else {
                        $STtime = date("Y-m-d", $stime);
                        for ($i = 0; $i <= $day; $i++) {
                            for ($j = 0; $j < count($TyStu); $j++) {
                                $arr[$i][$j]["time"] = date('Y-m-d', strtotime("+$i day", strtotime($STtime)));
                                $arr[$i][$j]["name"] = $TyStu[$j]["dec"];
                                $dec = $TyStu[$j]["dec"];
                                $Betime = strtotime(date('Y-m-d 00:00:00', strtotime("+$i day", strtotime($STtime))));
                                $Entime = strtotime(date('Y-m-d 23:59:59', strtotime("+$i day", strtotime($STtime))));
                                $arr[$i][$j]["count"] = $San_log->where("type=$money_type and uid=$game_user_id and time>='$Betime' and time <='$Entime'and `dec` = '$dec' and value< 0 ")->sum("value");
                                $arr[$i][$j]["count"]=abs($arr[$i][$j]["count"]);
                                if($arr[$i][$j]["count"]==null){
                                    $arr[$i][$j]["count"]=0;
                                }
                                $sum = $San_log->where("type=$money_type and uid=$game_user_id and time>='$Betime' and time <='$Entime' and value<0 ")->sum("value");
                                $sum=abs($sum);
                                $arr[$i][$j]["num"] = round($arr[$i][$j]["count"] / $sum, 4) * 100;
                            }
                        }
                        $arr2 = array();
                        foreach ($arr as $value) {
                            foreach ($value as $v) {
                                $arr2[] = $v;
                                unset($arr, $value, $v);
                            }
                        }
                        $arr = $arr2;
                    }
                }
            }
            $data=json_encode($arr);
            echo $data;
        }
    }
}