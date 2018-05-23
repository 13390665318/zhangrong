<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 3:12
 */

namespace Admin\Controller;


class TopController extends BaseController
{
    public function index(){
        if(isset($_SESSION["game_id"])) {
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
            $db_id=$clostu[0]["db_id"];
            $_SESSION["db_id"]=$db_id;
        }
var_dump($_SESSION);exit;
        $this->assign("db_id",$db_id);
        // 剧情关卡进度TOP10
        $stu[0]["title"]="剧情关卡进度TOP10";
        $stu[0]["num"]="玩家编号";
        $stu[0]["name"]="玩家名称";
        $stu[0]["type"]="进度";
        //名仕寻访开启进度TOP10
        $stu[1]["title"]="名仕寻访开启进度TOP10";
        $stu[1]["num"]="玩家编号";
        $stu[1]["name"]="玩家名称";
        $stu[1]["type"]="进度";
        //上阵战力TOP10
        /**   $stu[2]["title"]="上阵战力TOP10";
        $stu[2]["num"]="玩家编号";
        $stu[2]["name"]="玩家名称";
        $stu[2]["type"]="战力";**/
        //总战力TOP10
        $stu[3]["title"]="总战力TOP10";
        $stu[3]["num"]="玩家编号";
        $stu[3]["name"]="玩家名称";
        $stu[3]["type"]="战力";
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        $Zrus=$Userbase->field("uid,uname,fight")->order("fight desc")->limit("0,10")->select();
        for($i=0;$i<count($Zrus);$i++){
            $arr[3][$i]["num"]=$Zrus[$i]["uid"];
            $arr[3][$i]["name"]=$Zrus[$i]["uname"];
            $arr[3][$i]["type"]=$Zrus[$i]["fight"];
        }
        //玩家等级TOP10
        $stu[4]["title"]="玩家等级TOP10";
        $stu[4]["num"]="玩家编号";
        $stu[4]["name"]="玩家名称";
        $stu[4]["type"]="等级";
        $Lrus=$Userbase->field("uid,uname,level")->order("level desc")->limit("0,10")->select();
        for($i=0;$i<count($Lrus);$i++){
            $arr[4][$i]["num"]=$Lrus[$i]["uid"];
            $arr[4][$i]["name"]=$Lrus[$i]["uname"];
            $arr[4][$i]["type"]=$Lrus[$i]["level"];
        }
       //官职竞技场TOP10
        $stu[5]["title"]="官职竞技场TOP10";
        $stu[5]["num"]="玩家编号";
        $stu[5]["name"]="玩家名称";
        $stu[5]["type"]="称号";
        // $connection=db($game_id,$clothes);
        $Office1 = M('San_office1','',$connection);
        $Office2 = M('San_office2','',$connection);
        $Office3 = M('San_office3','',$connection);
        $rus1=$Office1->field("uid,name,class")->order("class desc")->limit("0,10")->select();
        for($i=0;$i<count($rus1);$i++){
            $arr1[$i]["num"]=$rus1[$i]["uid"];
            $arr1[$i]["name"]=$rus1[$i]["name"];
            $arr1[$i]["class"]=$rus1[$i]["class"];
            $class=$rus1[$i]["class"];
            $ru =D("office")->where("id=$class")->field("nameshu")->find();
            $arr1[$i]["type"]=$ru["nameshu"];
        }

        $rus2=$Office2->field("uid,name,class")->order("class desc")->limit("0,10")->select();
        for($i=0;$i<count($rus2);$i++){
            $arr2[$i]["num"]=$rus2[$i]["uid"];
            $arr2[$i]["name"]=$rus2[$i]["name"];
            $arr2[$i]["class"]=$rus2[$i]["class"];
            $class=$rus2[$i]["class"];
            $ru =D("office")->where("id=$class")->field("namewei")->find();
            $arr2[$i]["type"]=$ru["namewei"];
        }
        $rus3=$Office3->field("uid,name,class")->order("class desc")->limit("0,10")->select();
        for($i=0;$i<count($rus3);$i++){
            $arr3[$i]["num"]=$rus3[$i]["uid"];
            $arr3[$i]["name"]=$rus3[$i]["name"];
            $arr3[$i]["class"]=$rus3[$i]["class"];
            $class=$rus3[$i]["class"];
            $ru =D("office")->where("id=$class")->field("namewu")->find();
            $arr3[$i]["type"]=$ru["namewu"];
        }
        $Grus=array_merge($arr1,$arr2,$arr3);
        // var_dump($rus);

        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'class',       //排序字段
        );
        $arrSort = array();
        foreach($Grus AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $Grus);
        }
        $arr[5]=array_slice($Grus,0,10);
         //步兵竞技场TOP10
        $stu[6]["title"]="步兵竞技场TOP10";
        $stu[6]["num"]="玩家编号";
        $stu[6]["name"]="玩家名称";
        $stu[6]["type"]="排名";

        $San_armsarena3 = M('San_armsarena3','',$connection);
        $Bjstu=$San_armsarena3->field("uid,name,rankid")->order("rankid asc")->limit("0,10")->select();
        for($i=0;$i<count($Bjstu);$i++){
            $arr[6][$i]["num"]=$Bjstu[$i]["uid"];
            $arr[6][$i]["name"]=$Bjstu[$i]["name"];
            $arr[6][$i]["type"]=$Bjstu[$i]["rankid"];

        }
//骑兵竞技场TOP10
        $stu[7]["title"]="骑兵竞技场TOP10";
        $stu[7]["num"]="玩家编号";
        $stu[7]["name"]="玩家名称";
        $stu[7]["type"]="排名";
        $San_armsarena2 = M('San_armsarena2','',$connection);
        $Qjstu=$San_armsarena2->field("uid,name,rankid")->order("rankid asc")->limit("0,10")->select();
        for($i=0;$i<count($Qjstu);$i++){
            $arr[7][$i]["num"]=$Qjstu[$i]["uid"];
            $arr[7][$i]["name"]=$Qjstu[$i]["name"];
            $arr[7][$i]["type"]=$Qjstu[$i]["rankid"];

        }


        //弓兵竞技场TOP10
        $stu[8]["title"]="弓兵竞技场TOP10";
        $stu[8]["num"]="玩家编号";
        $stu[8]["name"]="玩家名称";
        $stu[8]["type"]="排名";
        $San_armsarena1 = M('San_armsarena1','',$connection);
        $Gjstu=$San_armsarena1->field("uid,name,rankid")->order("rankid asc")->limit("0,10")->select();
        for($i=0;$i<count($Gjstu);$i++){
            $arr[8][$i]["num"]=$Gjstu[$i]["uid"];
            $arr[8][$i]["name"]=$Gjstu[$i]["name"];
            $arr[8][$i]["type"]=$Gjstu[$i]["rankid"];

        }

       //今日充值TOP10
        $stu[9]["title"]="今日充值TOP10";
        $stu[9]["num"]="玩家编号";
        $stu[9]["name"]="玩家名称";
        $stu[9]["type"]="金额";
        $stime=date("Y-m-d 00:00:00",time());
        $etime=date("Y-m-d 23:59:59",time());
     /**   $Rmoney=D("pay")->where("pay_time>='$stime'and pay_time<='$etime'")->distinct(true)->field('game_user_id,game_user_name')->select();

        for($i=0;$i<count($Rmoney);$i++){
            $MStu[$i]["num"]=$Rmoney[$i]["game_user_id"];
            $game_user_id=$Rmoney[$i]["game_user_id"];
            $MStu[$i]["name"]=$Rmoney[$i]["game_user_name"];
            $MStu[$i]["type"]=D("pay")->where("pay_time>='$stime'and pay_time<='$etime'and game_user_id=$game_user_id")->sum("money");
        }
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'type',       //排序字段
        );
        $arrSort = array();
        foreach($MStu AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $MStu);
        }
        $arr[9]=$MStu;
	**/
     //今日钻石消耗TOP10  91000002
        $stu[10]["title"]="今日钻石消耗TOP10";
        $stu[10]["num"]="玩家编号";
        $stu[10]["name"]="玩家名称";
        $stu[10]["type"]="数量";
        $stime=strtotime(date("Y-m-d 00:00:00"),time());
	$etime=strtotime(date("Y-m-d 23:59:59"),time());
	  $San_log = M('San_log','',$connection);
             $ZSmoney=$San_log->field('uid,sum(value)')->where("time>='$stime'and time<='$etime' and type=91000002 and value<0")->group("uid")->order("sum(value) asc ")->limit(0,10)->select();
    
    for($i=0;$i<count($ZSmoney);$i++){
            $uid=$ZSmoney[$i]["uid"];
            $ZSTtu[$i]["num"]=$uid;
 	   $ZSTtu[$i]["type"]=$ZSmoney[$i]["sum(value)"];
            $Uname=$Userbase->where("uid=$uid")->find();
            $ZSTtu[$i]["name"]=$Uname["uname"];
        }
               $arr[10]=$ZSTtu;
       //今日金币消耗TOP10  910000001
        $stu[11]["title"]="今日金币消耗TOP10";
        $stu[11]["num"]="玩家编号";
        $stu[11]["name"]="玩家名称";
        $stu[11]["type"]="数量";
        $stime=strtotime(date("Y-m-d 00:00:00"),time());
        $etime=strtotime(date("Y-m-d 23:59:59"),time());
        $JSTtu=$San_log->field('uid,sum(value)')->where("time>='$stime'and time<='$etime' and type=91000001 and value<0")->group("uid")->order("sum(value) asc ")->limit(0,10)->select();
        for($i=0;$i<count($JSTtu);$i++){
            $uid=$JSTtu[$i]["uid"];
            $JSTtu[$i]["num"]=$uid;
	   $JSTtu[$i]["type"]=$JSTtu[$i]["sum(value)"];
            $Uname=$Userbase->where("uid=$uid")->find();;
            $JSTtu[$i]["name"]=$Uname["uname"];
           
        }
        $arr[11]=$JSTtu;
      //今日金币产出TOP10
        $stu[12]["title"]="今日金币产出TOP10";
        $stu[12]["num"]="玩家编号";
        $stu[12]["name"]="玩家名称";
        $stu[12]["type"]="数量";
        $stime=strtotime(date("Y-m-d 00:00:00"),time());
        $etime=strtotime(date("Y-m-d 23:59:59"),time());
    	$JPMmoney=$San_log->field('uid,sum(value)')->where("time>='$stime'and time<='$etime' and type=91000001 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();
        for($i=0;$i<count($JPMmoney);$i++){
            $uid=$JPMmoney[$i]["uid"];
            $JPMmoney[$i]["num"]=$uid;
	   $JPMmoney[$i]["type"]=$JPMmoney[$i]["sum(value)"];
            $Uname=$Userbase->where("uid=$uid")->find();;
            $JPMmoney[$i]["name"]=$Uname["uname"];
            
        }
        
        $arr[12]=$JPMmoney;

//过关斩将层数TOP10
        $stu[13]["title"]="过关斩将层数TOP10";
        $stu[13]["num"]="玩家编号";
        $stu[13]["name"]="玩家名称";
        $stu[13]["type"]="层数";
        //今日宝箱开启数量TOP10
        $stu[14]["title"]="今日宝箱开启数量TOP10";
        $stu[14]["num"]="玩家编号";
        $stu[14]["name"]="玩家名称";
        $stu[14]["type"]="数量";
        $btime=date("Y-m-d 00:00:00",time());
        $etime=date("Y-m-d H:i:s",time());
        $cjStu=D("custom")->where("time>='$btime' and time <='$etime' and param1='抽奖'")->distinct(true)->field("game_user_id")->select();
        for($i=0;$i<count($cjStu);$i++){
            $cjArr[$i]["num"]=$cjStu[$i]["game_user_id"];
            $uid=$cjStu[$i]["game_user_id"];
            $cjArr[$i]["type"]=D("custom")->where("time>='$btime' and time <='$etime' and param1='抽奖' and game_user_id=$uid")->sum("param3");
            $cjname=D("user")->where("game_user_id=$uid")->find();
            $cjArr[$i]["name"]=$cjname["game_user_name"];
        }
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'type',       //排序字段
        );
        $arrSort = array();
        foreach($cjArr AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $cjArr);
        }

        $arr[14]=array_slice($cjArr,0,10);





       //产业步兵排行TOP10  50000001
        $stu[15]["title"]="产业步兵排行TOP10";
        $stu[15]["num"]="玩家编号";
        $stu[15]["name"]="玩家名称";
        $stu[15]["type"]="数量";
      //  $San_log = M('San_log','',$connection);
      //  $BBstu=$San_log->where("type=50000001 and value>0")->distinct(true)->field('uid')->select();
	$BBstu=$San_log->field('uid,sum(value)')->where(" type=50000001 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();
        for($i=0;$i<count($BBstu);$i++){
            $uid=$BBstu[$i]["uid"];
            $bbtu[$i]["num"]=$uid;
	 $bbtu[$i]["type"]=$BBstu[$i]["sum(value)"];
            $Uname=$Userbase->where("uid=$uid")->find();;
            $bbtu[$i]["name"]=$Uname["uname"];
           
        }
        
        $arr[15]=$bbtu;





        //产业骑兵排行TOP10 50000003
        $stu[16]["title"]="产业骑兵排行TOP10";
        $stu[16]["num"]="玩家编号";
        $stu[16]["name"]="玩家名称";
        $stu[16]["type"]="数量";
     //   $San_log = M('San_log','',$connection);
       // $QBstu=$San_log->where("type=50000003 and value>0")->distinct(true)->field('uid')->select();
	//$BBstu=$San_log->field('uid,sum(value)')->where("type=50000001 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();
	$QBstu=$San_log->field('uid,sum(value)')->where("type=50000003 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();

        for($i=0;$i<count($QBstu);$i++){
            $uid=$QBstu[$i]["uid"];
            $qbtu[$i]["num"]=$uid;
	   $qbtu[$i]["type"]=$QBstu[$i]["sum(value)"];
            $Uname=$Userbase->where("uid=$uid")->find();
            $qbtu[$i]["name"]=$Uname["uname"];
          
        }

        $arr[16]=$qbtu;


        //产业弓兵排行TOP10 50000002
        $stu[17]["title"]="产业弓兵排行TOP10";
        $stu[17]["num"]="玩家编号";
        $stu[17]["name"]="玩家名称";
        $stu[17]["type"]="数量";
      //  $San_log = M('San_log','',$connection);
      //  $GBstu=$San_log->where("type=50000002 and value>0")->distinct(true)->field('uid')->select();
	$GBstu=$San_log->field('uid,sum(value)')->where("type=50000002 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();
        for($i=0;$i<count($GBstu);$i++){
            $uid=$GBstu[$i]["uid"];
            $gbtu[$i]["num"]=$uid;
            $Uname=$Userbase->where("uid=$uid")->find();;
            $gbtu[$i]["name"]=$Uname["uname"];
             $gbtu[$i]["type"]=$GBstu[$i]["sum(value)"];
        }
        $arr[17]=$gbtu;

        //今日荣誉点获得TOP10 91000018
        $stu[18]["title"]="今日荣誉点获得TOP10";
        $stu[18]["num"]="玩家编号";
        $stu[18]["name"]="玩家名称";
        $stu[18]["type"]="数量";
        $stime=strtotime(date("Y-m-d 00:00:00"),time());
        $etime=strtotime(date("Y-m-d 23:59:59"),time());
       // $San_log = M('San_log','',$connection);
      //  $RYstu=$San_log->where("time>='$stime'and time<='$etime' and type=91000018 and value>0")->distinct(true)->field('uid')->select();
	$GBstu=$San_log->field('uid,sum(value)')->where("time>='$stime'and time<='$etime' and type=91000018 and value>0")->group("uid")->order("sum(value) desc ")->limit(0,10)->select();
        for($i=0;$i<count($RYstu);$i++){
            $uid=$RYstu[$i]["uid"];
            $rytu[$i]["num"]=$uid;
            $Uname=$Userbase->where("uid=$uid")->find();;
            $rytu[$i]["name"]=$Uname["uname"];
           $rytu[$i]["type"]=$RYstu[$i]["sum(value)"];
        }
       
        $arr[18]=$rytu;


       

 
        //今日荣誉点消耗TOP10
        $stu[19]["title"]="今日荣誉点消耗TOP10";
        $stu[19]["num"]="玩家编号";
        $stu[19]["name"]="玩家名称";
        $stu[19]["type"]="数量";
        $stime=strtotime(date("Y-m-d 00:00:00"),time());
        $etime=strtotime(date("Y-m-d 23:59:59"),time());
       // $San_log = M('San_log','',$connection);
        //$RYSstu=$San_log->where("time>='$stime'and time<='$etime' and type=91000018 and value<0")->distinct(true)->field('uid')->select();
	$GBstu=$San_log->field('uid,sum(value)')->where("time>='$stime'and time<='$etime' and type=91000018 and value<0")->group("uid")->order("sum(value) asc ")->limit(0,10)->select();
        for($i=0;$i<count($RYSstu);$i++){
            $uid=$RYSstu[$i]["uid"];
            $ryStu[$i]["num"]=$uid;
            $Uname=$Userbase->where("uid=$uid")->find();;
            $ryStu[$i]["name"]=$Uname["uname"];
            $ryStu[$i]["type"]=$GBstu[$i]["sum(value)"];
        }
       
        $arr[19]=$ryStu;

        //今日武将产出（数量）及其来源TOP10
        $stu[20]["title"]="今日武将产出（数量）及其来源TOP10";
        $stu[20]["num"]="玩家编号";
        $stu[20]["name"]="玩家名称";
        $stu[20]["type"]="数量";
        $btime=date("Y-m-d 00:00:00",time());
        $etime=date("Y-m-d H:i:s",time());
        $wjStu=D("custom")->where("time>='$btime' and time <='$etime' and param2 like'%抽到武将%'")->distinct(true)->field("game_user_id")->select();
        for($i=0;$i<count($wjStu);$i++){
            $wjArr[$i]["num"]=$wjStu[$i]["game_user_id"];
            $uid=$wjStu[$i]["game_user_id"];
            $wjArr[$i]["type"]=D("custom")->where("time>='$btime' and time <='$etime' and param2 like'%抽到武将%' and game_user_id=$uid")->count();
            $wjname=D("user")->where("game_user_id=$uid")->find();
            $wjArr[$i]["name"]=$wjname["game_user_name"];
        }
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'type',       //排序字段
        );
        $arrSort = array();
        foreach($wjArr AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $wjArr);
        }
        $arr[20]=$wjArr;





        $this->assign("stu",$stu);
        $this->assign("arr",$arr);
        $this->display();
    }
}