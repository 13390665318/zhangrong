<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 5:43
 */

namespace Admin\Controller;


class LevellossController extends BaseController
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
        $con["_string"]="regtime>='$stime' AND regtime<='$etime'"; // 注册时间
        $where["_string"]="regtime>='$stime' AND regtime<='$etime'"; // 注册时间
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

        $con["lastupdtime"]=array('gt','$endtime');
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        // 计算 总人数，流失总人数

        $num=$Userbase->where($where)->count();
//$num=count($num);
//var_dump($num);
        $loss=$Userbase->where($con)->count();
//$loss=count($loss);
        $loss_num=round($loss/$num, 4)*100;
        $this->assign("num",$num);
        $this->assign("loss",$loss);
        $this->assign("loss_num",$loss_num);
        $stu = $Userbase->where($where)->distinct(true)->field('level')->order("level desc")->select();
        for($i=0;$i<count($stu);$i++){
            $arr[$i]["level"]=$stu[$i]["level"];
            // 等级所有人数
            $where["level"]=$stu[$i]["level"];
            $con["level"]=$stu[$i]["level"];
            $arr[$i]["num"]=$Userbase->where($where)->count();
            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$num,4)*100;
        }

        $this->assign("arr",$arr);
        $this->display();
    }

    public function exl(){

        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        $game_id=1;
        // 图标 默认 最新服
         $db_id=I("get.db_id");
          $time=I("get.time");
        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $con["_string"]="regtime>='$stime' AND regtime<='$etime'"; // 注册时间
        $where["_string"]="regtime>='$stime' AND regtime<='$etime'"; // 注册时间
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

        $con["lastupdtime"]=array('gt','$endtime');
        $con=array_filter($con);
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        $num=$Userbase->where($where)->count();
        $loss=$Userbase->where($con)->count();
        $loss_num=round($loss/$num, 4)*100;
        $stu = $Userbase->where($where)->distinct(true)->field('level')->order("level desc")->select();
        for($i=0;$i<count($stu);$i++){
            $arr[$i]["level"]=$stu[$i]["level"];
            // 等级所有人数
            $where["level"]=$stu[$i]["level"];
            $con["level"]=$stu[$i]["level"];
            $arr[$i]["num"]=$Userbase->where($where)->count();
            //等级流失人数
            $arr[$i]["loss"]=$Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"]=round($arr[$i]["loss"]/$num,4)*100;
        }


        $objPHPExcel=new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','等级人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','流失人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','比例');


        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["level"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['loss']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['loss_num']);

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