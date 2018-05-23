<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 下午 3:16
 */

namespace Home\Controller;


class TrendActivityController  extends BaseController
{
    // 日活趋势
    public function index(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        //查询渠道
         $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }      
	  $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["cid"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];

            $ru['_string']="start_time>='$Strtime' and start_time<='$Endtime'";
            $data[$i]["num"]=0;

           // for($j=0;$j<count($db);$j++)
               // $db_id=$db[$j]["db_id"];
              //  $connection=db2($game_id,$db_id);
                $sum=M('sign')->where($ru)->group('game_user_id')->select();

                $data[$i]["num"]=$data[$i]["num"]+count($sum);

                $user=$user+count($sum);

        }
        for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
        }
        //   var_dump($data);exit;
        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);

        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
        $this->display();
    }
// 周活跃趋势  上月  上周  本周
    public function index2(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);

        //查询渠道

        $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }
	        $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["cid"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);


        $data[0]["time"]="本周";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-0 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-6 day", strtotime($stime)));

        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";

        $data[0]["num"]=0;
       // for($j=0;$j<count($db);$j++){
         //   $db_id=$db[$j]["db_id"];
         //   $connection=db2($game_id,$db_id);
            $sum=M('sign')->where($ru)->field('game_user_id')->group("game_user_id")->select();

            //  echo M('sign','',$connection)->getLastSql();exit;
            $data[0]["num"]=$data[0]["num"]+count($sum);

            $user=$user+count($sum);
      //  }

      //  dump($data);

        $sum=0;

        $data[1]["time"]="上周";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-7 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-13 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[1]["num"]=0;


            $sum=M('sign')->where($ru)->field('game_user_id')->group("game_user_id")->select();

            $data[1]["num"]=$data[1]["num"]+count($sum);
            $user=$user+count($sum);


        $data[2]["time"]="上月同时";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-30 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-36 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[2]["num"]=0;


            $sum=M('sign')->where($ru)->field('game_user_id')->group("game_user_id")->select();
            $data[2]["num"]=$data[2]["num"]+count($sum);




        $this->assign("data",$data);
        $jsoBj=json_encode($data);

        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
    // 双周活趋势
    public function index3(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);

        //查询渠道

        $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }
	        $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["cid"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);


        $data[0]["time"]="本双周";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-0 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-13 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[0]["num"]=0;



            $sum=M('sign')->where($ru)->field('game_user_id')->group("game_user_id")->select();
            $data[0]["num"]=$data[0]["num"]+count($sum);
            $user=$user+count($sum);


        $sum=0;

        $data[1]["time"]="上双周";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-14 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-27 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[1]["num"]=0;

            $sum=M('sign')->where($ru)->group("game_user_id")->field('game_user_id')->select();
            $data[1]["num"]=$data[1]["num"]+count($sum);

            $user=$user+count($sum);;

        for($i=0;$i<count($data);$i++){
            $data[1]["nums"]=round($data[1]["num"]/$user,4)*100;
        }

        $data[2]["time"]="上月双周";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-30 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-33 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[2]["num"]=0;



            $sum=M('sign')->where($ru)->group("game_user_id")->field('game_user_id')->select();
            $data[2]["num"]=$data[2]["num"]+count($sum);;

            $user=$user+count($sum);




        $this->assign("data",$data);
        $jsoBj=json_encode($data);

        $this->assign("jsoBj",$jsoBj);

        $this->display();
    }
    //月活趋势
    public function index4(){
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])){
            $stime=I("get.stime");
        }else{
            // 默认 当天
            //  $stime=date("Y-m-d",strtotime("-6 day"));
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);

        $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }
        $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["cid"];
        }

        $con=substr($where,0,strlen($where)-3);


        $data[0]["time"]="本月";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-0 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-30 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[0]["num"]=0;
        for($j=0;$j<count($db);$j++){
            $db_id=$db[$j]["db_id"];
            $connection=db2($game_id,$db_id);
            $sum=M('sign')->where($ru)->group("game_user_id")->field('game_user_id')->select();
            //  echo M('sign','',$connection)->getLastSql();exit;
            $data[0]["num"]=$data[0]["num"]+count($sum);

            $user=$user+count($sum);
        }
        for($i=0;$i<count($data);$i++){
            $data[0]["nums"]=round($data[0]["num"]/$user,4)*100;
        }
        $sum=0;

        $data[1]["time"]="上月";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-31 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-60 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[1]["num"]=0;
        for($j=0;$j<count($db);$j++){
            $db_id=$db[$j]["db_id"];
            $connection=db2($game_id,$db_id);
            $sum=M('sign')->where($ru)->group("game_user_id")->field('game_user_id')->select();
            $data[1]["num"]=$data[1]["num"]+count($sum);

            $user=$user+count($sum);
        }
        for($i=0;$i<count($data);$i++){
            $data[1]["nums"]=round($data[1]["num"]/$user,4)*100;
        }

        $data[2]["time"]="去年本月";
        $Strtime=date('Y-m-d 00:00:00', strtotime ("-365 day", strtotime($stime)));
        $Endtime=date('Y-m-d 23:59:59', strtotime ("-395 day", strtotime($stime)));
        $user=0;
        $ru['_string']="start_time>='$Endtime' and start_time<='$Strtime'";
        $data[2]["num"]=0;
        for($j=0;$j<count($db);$j++){
            $db_id=$db[$j]["db_id"];
            $connection=db2($game_id,$db_id);
            $sum=M('sign')->where($ru)->group("game_user_id")->field('game_user_id')->select();
            $data[2]["num"]=$data[2]["num"]+count($sum);

            $user=$user+count($sum);
        }



        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);


        $this->display();
    }
}