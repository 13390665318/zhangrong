<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10 0010
 * Time: 上午 11:34
 */

namespace Home\Controller;


class LossWeekController extends BaseController
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
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-7 day"));
            $etime=date("Y-m-d",strtotime("-1 day"));
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $day=count_days($stime,$etime);
        $connection=db2($game_id,$db_id);
        //  var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','二周流失率','三周流失率','四周流失率','五周流失率','六周流失率','七周流失率',);
        for($i=0;$i<=$day;$i++){
            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
            //2ri
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("+7 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+13 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
           if(count($day2)!=0){
               $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
               $arr[$i]["day2s"]=round($arr[$i]["day2"]/count($day2),4)*100;
           }else{
               $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
               $arr[$i]["day2s"]=100;
           }

            //3ri
            $Strtime3=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
            $Endtime3=date('Y-m-d 23:59:59', strtotime ("+20 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime3' and b.start_time<='$Endtime3' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day3)!=0){
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=round($arr[$i]["day3"]/count($day3),4)*100;
            }else{
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=100;
            }


            //4ri
            $Strtime4=date('Y-m-d 00:00:00', strtotime ("+21 day", strtotime($Strtime)));
            $Endtime4=date('Y-m-d 23:59:59', strtotime ("+27 day", strtotime($Strtime)));
            $day4=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime4' and b.start_time<='$Endtime4' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day4)!=0){
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=round($arr[$i]["day4"]/count($day4),4)*100;
            }else{
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=100;
            }


            //5
            $Strtime5=date('Y-m-d 00:00:00', strtotime ("+28 day", strtotime($Strtime)));
            $Endtime5=date('Y-m-d 23:59:59', strtotime ("+34 day", strtotime($Strtime)));
            $day5=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime5' and b.start_time<='$Endtime5' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day5)!=0){
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=round($arr[$i]["day5"]/count($day5),4)*100;
            }else{
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=100;
            }

            //6
            $Strtime6=date('Y-m-d 00:00:00', strtotime ("+35 day", strtotime($Strtime)));
            $Endtime6=date('Y-m-d 23:59:59', strtotime ("+41 day", strtotime($Strtime)));
            $day6=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime6' and b.start_time<='$Endtime6' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=round($arr[$i]["day6"]/count($day6),4)*100;
            }else{
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=100;
            }


            //7
            $Strtime7=date('Y-m-d 00:00:00', strtotime ("+42 day", strtotime($Strtime)));
            $Endtime7=date('Y-m-d 23:59:59', strtotime ("+48 day", strtotime($Strtime)));
            $day7=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime7' and b.start_time<='$Endtime7' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=round($arr[$i]["day7"]/count($day7),4)*100;
            }else{
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=100;
            }
        }
        $this->assign("arrs",$arrs);
        $this->assign("arr",$arr);
        $this->display();
    }
    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
         $game_id = 1;
         $db_id=I("get.db_id");
         $stime=I("get.stime");
         $etime=I("get.etime");
        $day=count_days($stime,$etime);
        $connection=db2($game_id,$db_id);
        //  var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','二周流失率','三周流失率','四周流失率','五周流失率','六周流失率','七周流失率',);
        for($i=0;$i<=$day;$i++){
            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
            //2ri
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("+7 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+13 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day2)!=0){
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=round($arr[$i]["day2"]/count($day2),4)*100;
            }else{
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=100;
            }

            //3ri
            $Strtime3=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
            $Endtime3=date('Y-m-d 23:59:59', strtotime ("+20 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime3' and b.start_time<='$Endtime3' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day3)!=0){
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=round($arr[$i]["day3"]/count($day3),4)*100;
            }else{
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=100;
            }


            //4ri
            $Strtime4=date('Y-m-d 00:00:00', strtotime ("+21 day", strtotime($Strtime)));
            $Endtime4=date('Y-m-d 23:59:59', strtotime ("+27 day", strtotime($Strtime)));
            $day4=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime4' and b.start_time<='$Endtime4' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day4)!=0){
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=round($arr[$i]["day4"]/count($day4),4)*100;
            }else{
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=100;
            }


            //5
            $Strtime5=date('Y-m-d 00:00:00', strtotime ("+28 day", strtotime($Strtime)));
            $Endtime5=date('Y-m-d 23:59:59', strtotime ("+34 day", strtotime($Strtime)));
            $day5=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime5' and b.start_time<='$Endtime5' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day5)!=0){
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=round($arr[$i]["day5"]/count($day5),4)*100;
            }else{
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=100;
            }

            //6
            $Strtime6=date('Y-m-d 00:00:00', strtotime ("+35 day", strtotime($Strtime)));
            $Endtime6=date('Y-m-d 23:59:59', strtotime ("+41 day", strtotime($Strtime)));
            $day6=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime6' and b.start_time<='$Endtime6' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=round($arr[$i]["day6"]/count($day6),4)*100;
            }else{
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=100;
            }


            //7
            $Strtime7=date('Y-m-d 00:00:00', strtotime ("+42 day", strtotime($Strtime)));
            $Endtime7=date('Y-m-d 23:59:59', strtotime ("+48 day", strtotime($Strtime)));
            $day7=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime7' and b.start_time<='$Endtime7' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=round($arr[$i]["day7"]/count($day7),4)*100;
            }else{
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=100;
            }

        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','新玩家');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','2周流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','3周流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','4周流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','5周流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','6周流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','7周流失率');



        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value["num"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['day2'].'('.$value['day2s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value["day3"].'('.$value['day3s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value['day4'].'('.$value['day4s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value["day5"].'('.$value['day5s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value['day6'].'('.$value['day6s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,$value["day7"].'('.$value['day7s'].'%)');





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

    // 月流失

    public function index2(){
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
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-7 day"));
            $etime=date("Y-m-d",strtotime("-1 day"));
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $day=count_days($stime,$etime);
        $connection=db2($game_id,$db_id);
        //  var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','二月流失率','三月流失率','四月流失率','五月流失率','六月流失率','七月流失率',);
        for($i=0;$i<=$day;$i++){
            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
            //2ri
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("+30 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+60 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day2)!=0){
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=round($arr[$i]["day2"]/count($day2),4)*100;
            }else{
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=100;
            }

            //3ri
            $Strtime3=date('Y-m-d 00:00:00', strtotime ("+61 day", strtotime($Strtime)));
            $Endtime3=date('Y-m-d 23:59:59', strtotime ("+90 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime3' and b.start_time<='$Endtime3' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day3)!=0){
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=round($arr[$i]["day3"]/count($day3),4)*100;
            }else{
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=100;
            }


            //4ri
            $Strtime4=date('Y-m-d 00:00:00', strtotime ("+91 day", strtotime($Strtime)));
            $Endtime4=date('Y-m-d 23:59:59', strtotime ("+120 day", strtotime($Strtime)));
            $day4=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime4' and b.start_time<='$Endtime4' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day4)!=0){
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=round($arr[$i]["day4"]/count($day4),4)*100;
            }else{
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=100;
            }


            //5
            $Strtime5=date('Y-m-d 00:00:00', strtotime ("+121 day", strtotime($Strtime)));
            $Endtime5=date('Y-m-d 23:59:59', strtotime ("+150 day", strtotime($Strtime)));
            $day5=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime5' and b.start_time<='$Endtime5' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day5)!=0){
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=round($arr[$i]["day5"]/count($day5),4)*100;
            }else{
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=100;
            }

            //6
            $Strtime6=date('Y-m-d 00:00:00', strtotime ("+151 day", strtotime($Strtime)));
            $Endtime6=date('Y-m-d 23:59:59', strtotime ("+180 day", strtotime($Strtime)));
            $day6=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime6' and b.start_time<='$Endtime6' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=round($arr[$i]["day6"]/count($day6),4)*100;
            }else{
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=100;
            }


            //7
            $Strtime7=date('Y-m-d 00:00:00', strtotime ("+181 day", strtotime($Strtime)));
            $Endtime7=date('Y-m-d 23:59:59', strtotime ("+210 day", strtotime($Strtime)));
            $day7=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime7' and b.start_time<='$Endtime7' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=round($arr[$i]["day7"]/count($day7),4)*100;
            }else{
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=100;
            }

        }

        // var_dump($arr);exit;
        //var_dump($arr);
        $this->assign("arrs",$arrs);
        $this->assign("arr",$arr);
        $this->display();
    }
    public function exl2(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        $db_id=I("get.db_id");
          $stime=I("get.stime");
            $etime=I("get.etime");

        $day=count_days($stime,$etime);
        $connection=db2($game_id,$db_id);
        //  var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','二月流失率','三月流失率','四月流失率','五月流失率','六月流失率','七月流失率',);
        for($i=0;$i<=$day;$i++){
            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
            //2ri
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("+30 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+60 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day2)!=0){
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=round($arr[$i]["day2"]/count($day2),4)*100;
            }else{
                $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
                $arr[$i]["day2s"]=100;
            }

            //3ri
            $Strtime3=date('Y-m-d 00:00:00', strtotime ("+61 day", strtotime($Strtime)));
            $Endtime3=date('Y-m-d 23:59:59', strtotime ("+90 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime3' and b.start_time<='$Endtime3' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day3)!=0){
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=round($arr[$i]["day3"]/count($day3),4)*100;
            }else{
                $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
                $arr[$i]["day3s"]=100;
            }


            //4ri
            $Strtime4=date('Y-m-d 00:00:00', strtotime ("+91 day", strtotime($Strtime)));
            $Endtime4=date('Y-m-d 23:59:59', strtotime ("+120 day", strtotime($Strtime)));
            $day4=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime4' and b.start_time<='$Endtime4' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day4)!=0){
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=round($arr[$i]["day4"]/count($day4),4)*100;
            }else{
                $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
                $arr[$i]["day4s"]=100;
            }


            //5
            $Strtime5=date('Y-m-d 00:00:00', strtotime ("+121 day", strtotime($Strtime)));
            $Endtime5=date('Y-m-d 23:59:59', strtotime ("+150 day", strtotime($Strtime)));
            $day5=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime5' and b.start_time<='$Endtime5' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day5)!=0){
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=round($arr[$i]["day5"]/count($day5),4)*100;
            }else{
                $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
                $arr[$i]["day5s"]=100;
            }

            //6
            $Strtime6=date('Y-m-d 00:00:00', strtotime ("+151 day", strtotime($Strtime)));
            $Endtime6=date('Y-m-d 23:59:59', strtotime ("+180 day", strtotime($Strtime)));
            $day6=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime6' and b.start_time<='$Endtime6' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=round($arr[$i]["day6"]/count($day6),4)*100;
            }else{
                $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
                $arr[$i]["day6s"]=100;
            }


            //7
            $Strtime7=date('Y-m-d 00:00:00', strtotime ("+181 day", strtotime($Strtime)));
            $Endtime7=date('Y-m-d 23:59:59', strtotime ("+210 day", strtotime($Strtime)));
            $day7=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime7' and b.start_time<='$Endtime7' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            if(count($day6)!=0){
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=round($arr[$i]["day7"]/count($day7),4)*100;
            }else{
                $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
                $arr[$i]["day7s"]=100;
            }
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','新玩家');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','2月流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','3月流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','4月流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','5月流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','6月流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','7月流失率');



        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value["num"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['day2'].'('.$value['day2s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value["day3"].'('.$value['day3s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value['day4'].'('.$value['day4s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value["day5"].'('.$value['day5s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value['day6'].'('.$value['day6s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,$value["day7"].'('.$value['day7s'].'%)');





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