<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 上午 10:06
 */

namespace Home\Controller;


class LossLevelController extends BaseController
{
    public function index(){
        $game_id = 1;

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
        if(isset($_GET["stime"])){
            $time=I("get.stime");
        }else{
            $time=date("Y-m-d",time());
        }
        $this->assign("stime",$time);
        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";

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
           $cesa=24;
            $day=1;
        }
        $this->assign("cesa",$cesa);
        $endtime=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($time)));

        $con["_string"]="regtime>='$stime' AND regtime<='$etime' and lastupdtime < '$endtime'"; // 注册时间
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        $loss=$Userbase->where($con)->count(); // 流失总人数
        $stu = $Userbase->where($con)->distinct(true)->field('level')->order("level desc")->select();
        $ru=null;
        for($i=0;$i<count($stu);$i++){
            $level=$stu[$i]["level"];
            $ru='" '.$level.'" '.",".$ru;
            $arr[$i]["level"]=$stu[$i]["level"];

            $con["level"]=$stu[$i]["level"];
            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$loss,4)*100;
        }

        $jsoBj=json_encode($arr);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("stu",$ru);
$arr=array_reverse($arr);
        $this->assign("arr",$arr);
        $this->display();
    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        $db_id=I("get.db_id");
        $time=I("get.stime");


        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        if(isset($_GET["cesa"])){
            $cesa=I("get.cesa");
            if($cesa==24){
                $day=1;
            }else if($cesa==48){
                $day=2;
            }else{
                $day=3;
            }
        }

        $endtime=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($time)));

        $con["_string"]="regtime>='$stime' AND regtime<='$etime' and lastupdtime < '$endtime'"; // 注册时间
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        $loss=$Userbase->where($con)->count(); // 流失总人数
        $stu = $Userbase->where($con)->distinct(true)->field('level')->order("level desc")->select();
        $ru=null;
        for($i=0;$i<count($stu);$i++){
            $level=$stu[$i]["level"];
            $ru='" '.$level.'" '.",".$ru;
            $arr[$i]["level"]=$stu[$i]["level"];

            $con["level"]=$stu[$i]["level"];
            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$loss,4)*100;
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','人数(占比)');


        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["level"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['loss'].'('.$value['loss_num'].'%)');




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