<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3 0003
 * Time: 下午 3:13
 */

namespace Home\Controller;

// 新手引导流失
class GuideController extends BaseController
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
        if(isset($_GET["time"])){
            $time=I("get.time");
        }else{
            $time=date("Y-m-d",time());
        }
        $this->assign("time",$time);

        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $con["_string"]="regtime>='$stime' AND regtime<='$etime'  and lastlogintime < lastupdtime"; // 注册时间

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
        $endtime=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($time)));
        $con["zhiyinid"]=array('lt',99900900);

        $con["lastupdtime"]=array('lt',"$endtime");
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        // 计算 总人数，流失总人数


        $loss=$Userbase->where($con)->count();
        $this->assign("loss",$loss);

        $stu = $Userbase->where($con)->distinct(true)->field('zhiyinid')->order("zhiyinid desc")->select();
//echo $Userbase->getLastSql();
//var_dump($stu);
        for($i=0;$i<count($stu);$i++){
            $arr[$i]["zhiyinid"]=$stu[$i]["zhiyinid"];
            // 等级所有人数

            $con["zhiyinid"]=$stu[$i]["zhiyinid"];

            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$loss,4)*100;
        }

        $this->assign("arr",$arr);
        $this->display();
}

    public function exl(){

        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        $game_id = 1;
       $db_id=I("get.db_id");
       $time=I("get.time");

        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $con["_string"]="regtime>='$stime' AND regtime<='$etime'  and lastlogintime < lastupdtime"; // 注册时间

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
        $endtime=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($time)));
        $con["zhiyinid"]=array('lt',99900900);

        $con["lastupdtime"]=array('lt',"$endtime");
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        // 计算 总人数，流失总人数


        $loss=$Userbase->where($con)->count();
        $this->assign("loss",$loss);
$stu = $Userbase->where($con)->distinct(true)->field('zhiyinid')->order("zhiyinid desc")->select();
for($i=0;$i<count($stu);$i++){
            $arr[$i]["zhiyinid"]=$stu[$i]["zhiyinid"];
            // 等级所有人数

            $con["zhiyinid"]=$stu[$i]["zhiyinid"];

            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$loss,4)*100;
        }


        $objPHPExcel=new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','指引阶段');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','流失人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','注册时间');


        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["zhiyinid"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['loss']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['loss_num']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$time);

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