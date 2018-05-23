<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3 0003
 * Time: 下午 3:58
 */

namespace Home\Controller;


class CheckpointLossController extends BaseController
{

    public function index(){
        if (isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
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
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d H:i:s",strtotime("-7 day"));
            $etime=date("Y-m-d H:i:s",strtotime("-1 day"));
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
if($_GET){


        if(isset($_GET["cesa"])){
            $cesa=I("get.cesa");
            if($cesa==24){
                $day=1;
            }else if($cesa==48){
                $day=2;
            }else{
                $day=3;
            }
        }else{
            $day=1;
        }
        $begin=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($stime)));
        $end=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($etime)));
        $con["_string"]="a.regtime>='$stime' and a.regtime<='$etime'  and a.lastlogintime < a.lastupdtime and a.lastupdtime>='$begin' and a.lastupdtime<='$end'"; // 注册时间



        $connection=db($game_id,$db_id);
        $con = mysql_connect($connection["db_host"],$connection["db_user"],$connection["db_pwd"]);
        mysql_select_db($connection["db_name"], $con);
        // var_dump($con);exit;
        $Userbase = M('San_userbase as a','',$connection);
        $San_log = M('San_log','',$connection);
        // 计算 总人数，流失总人数
        $sum=$Userbase->where("a.regtime>='$stime' AND a.regtime<='$etime'  and a.lastlogintime < a.lastupdtime and a.lastupdtime>='$begin' and a.lastupdtime<='$end'")->count();

        $datas=$San_log->where("type=1")->order("value desc")->field("value")->group("value")->select();



        for($j=0;$j<count($datas);$j++){
            $zhang= substr($datas[$j]["value"],2,2)+1;
            $jie= substr($datas[$j]["value"],4,2);
            $arr[$j]["pass"]="第".$zhang."章 第".$jie."节"; // 当前章节 san_pass
            $value=$datas[$j]["value"];
            $sql="select count(a.uid)  from san_userbase a join san_log b on a.uid = b.uid  where b.value=$value and  b.type=1 and a.regtime>='$stime' AND a.regtime<='$etime'  and a.lastlogintime < a.lastupdtime and a.lastupdtime>='$begin' and a.lastupdtime<='$end'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $arr[$j]["num"]=(int)$row["count(a.uid)"];

        }
        $num=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["pass"]=$arr[$i]["pass"];
            $data[$i]["num"]=$arr[$i]["num"]-$num;
            $num=$arr[$i]["num"];
            $data[$i]["nums"]=round($data[$i]["num"]/$sum,4)*100;

        }

        // var_dump($data);exit;

        $this->assign("arr",$data);
        $this->display();
}else{
$this->display();
}
    }

    public function exl(){

        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $game_id = 1;
      $db_id=I("get.db_id");
      $stime=I("get.stime");
       $etime=I("get.etime");
      if(isset($_GET["cesa"])){
            $cesa=I("get.cesa");
            if($cesa==24){
                $day=1;
            }else if($cesa==48){
                $day=2;
            }else{
                $day=3;
            }
        }else{
            $day=1;
        }
        $begin=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($stime)));
        $end=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($etime)));


        $connection=db($game_id,$db_id);
        $con = mysql_connect($connection["db_host"],$connection["db_user"],$connection["db_pwd"]);
        mysql_select_db($connection["db_name"], $con);
        // var_dump($con);exit;
        $Userbase = M('San_userbase as a','',$connection);
        $San_log = M('San_log','',$connection);
        // 计算 总人数，流失总人数
        $sum=$Userbase->where("a.regtime>='$stime' AND a.regtime<='$etime'  and a.lastlogintime < a.lastupdtime and a.lastupdtime>='$begin' and a.lastupdtime<='$end'")->count();

        $datas=$San_log->where("type=1")->order("value desc")->field("value")->group("value")->select();



        for($j=0;$j<count($datas);$j++){
            $zhang= substr($datas[$j]["value"],2,2)+1;
            $jie= substr($datas[$j]["value"],4,2);
            $arr[$j]["pass"]="第".$zhang."章 第".$jie."节"; // 当前章节 san_pass
            $value=$datas[$j]["value"];
            $sql="select count(a.uid)  from san_userbase a join san_log b on a.uid = b.uid  where b.value=$value and  b.type=1 and a.regtime>='$stime' AND a.regtime<='$etime'  and a.lastlogintime < a.lastupdtime and a.lastupdtime>='$begin' and a.lastupdtime<='$end'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $arr[$j]["num"]=(int)$row["count(a.uid)"];

        }
        $num=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["pass"]=$arr[$i]["pass"];
            $data[$i]["num"]=$arr[$i]["num"]-$num;
            $num=$arr[$i]["num"];
            $data[$i]["nums"]=round($data[$i]["num"]/$sum,4)*100;

        }

        $objPHPExcel=new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','关卡名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','流失人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','比例');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["pass"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['nums']);


        }
        //导出代码
        $name=time();
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;




    }


}