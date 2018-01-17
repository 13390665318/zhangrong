<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11 0011
 * Time: 下午 4:32
 */

namespace Home\Controller;


class ACUController extends BaseController
{
    public function index(){
        $game_id = 1;
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
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);

        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

        }
        //$connection=db2($game_id,$db_id);
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time"];
            $data[$i]["num"]=0;
            $sum=M('period')->where("time='$Strtime'")->sum("num");
            $data[$i]["num"]=$sum;
            $data[$i]["nums"]=round($data[$i]["num"]/720,4);

        }
//dump($data);exit;
        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);




        $this->display();
    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        $game_id = 1;
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
       
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

        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }


        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

        }
        $connection=db2($game_id,$db_id);
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time"];
            $data[$i]["num"]=0;
            $sum=M('period','',$connection)->where("time='$Strtime'")->sum("num");
            $data[$i]["num"]=$sum;
            $data[$i]["nums"]=round($data[$i]["num"]/24,4);

        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','平均人数');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['nums']);




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