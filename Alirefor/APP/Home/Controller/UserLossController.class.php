<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 下午 5:10
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class UserLossController extends BaseController
{
    // 流失漏斗
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


        //选择汇总方式
        if(isset($_GET["type"])){
            $type=I("get.type");
        }else{
            $type=1;
        }
        $this->assign("type",$type);
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
        $time=date("Y-m-d",time());
        $arrs=array('日期','新玩家','2日流失率','3日流失率','4日流失率','5日流失率','6日流失率','7日流失率');
        $arr=D("usersave")->where("time>='$stime' and time <='$etime' ")->order("time asc")->select();
        //dump($arr);exit;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $data[$i]["newuser"]=$arr[$i]["adduser"];

            if($data[$i]["time"]  <date("Y-m-d",strtotime("-1 day")) ){
                $data[$i]["day2"]=$arr[$i]["adduser"]-$arr[$i]["day2"];
                $data[$i]["day2s"]=round($data[$i]["day2"]/$data[$i]["newuser"],4)*100;
            }
            if($data[$i]["time"]  <date("Y-m-d",strtotime("-2 day")) ){
                $data[$i]["day3"]=$arr[$i]["adduser"]-$arr[$i]["day3"];
                $data[$i]["day3s"]=round($data[$i]["day3"]/$data[$i]["newuser"],4)*100;
            }
            if($data[$i]["time"]  <date("Y-m-d",strtotime("-3 day")) ){
                $data[$i]["day4"]=$arr[$i]["adduser"]-$arr[$i]["day4"];
                $data[$i]["day4s"]=round($data[$i]["day4"]/$data[$i]["newuser"],4)*100;
            }
             if($data[$i]["time"]  <date("Y-m-d",strtotime("-4 day")) ){
                $data[$i]["day5"]=$arr[$i]["adduser"]-$arr[$i]["day5"];
                $data[$i]["day5s"]=round($data[$i]["day5"]/$data[$i]["newuser"],4)*100;

            }
            if($data[$i]["time"]  <date("Y-m-d",strtotime("-5 day")) ){
                $data[$i]["day6"]=$arr[$i]["adduser"]-$arr[$i]["day6"];
                $data[$i]["day6s"]=round($data[$i]["day6"]/$data[$i]["newuser"],4)*100;
            }
             if($data[$i]["time"]  <date("Y-m-d",strtotime("-6 day")) ){
                $data[$i]["day7"]=$arr[$i]["adduser"]-$arr[$i]["day7"];
                $data[$i]["day7s"]=round($data[$i]["day7"]/$data[$i]["newuser"],4)*100;
            }

        }


        $this->assign("arrs",$arrs);
        $this->assign("arr",$data);
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
        for($i=0;$i<=$day;$i++){

            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'")->count();
            //2ri
            $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day", strtotime($Strtime)));
            $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
            $day2=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime2' and b.start_time<='$Endtime2' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day2"]=$arr[$i]["num"]-count($day2);
            $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
            //3ri
            $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
            $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
            $day3=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime3' and b.start_time<='$Endtime3' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day3"]=$arr[$i]["num"]-count($day3);
            $arr[$i]["day3s"]=round($arr[$i]["day3"]/$arr[$i]["num"],4)*100;

            //4ri
            $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
            $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
            $day4=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime4' and b.start_time<='$Endtime4' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            // var_dump(count($day4));exit;
            $arr[$i]["day4"]=$arr[$i]["num"]-count($day4);
            $arr[$i]["day4s"]=round($arr[$i]["day4"]/$arr[$i]["num"],4)*100;

            //5
            $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
            $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
            $day5=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime5' and b.start_time<='$Endtime5' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day5"]=$arr[$i]["num"]-count($day5);
            $arr[$i]["day5s"]=round($arr[$i]["day5"]/$arr[$i]["num"],4)*100;
            //6
            $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
            $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
            $day6=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime6' and b.start_time<='$Endtime6' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day6"]=$arr[$i]["num"]-count($day6);
            $arr[$i]["day6s"]=round($arr[$i]["day6"]/$arr[$i]["num"],4)*100;

            //7
            $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day", strtotime($Strtime)));
            $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
            $day7=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime7' and b.start_time<='$Endtime7' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day7"]=$arr[$i]["num"]-count($day7);
            $arr[$i]["day7s"]=round($arr[$i]["day7"]/$arr[$i]["num"],4)*100;
            //15
            $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
            $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
            $day15=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime15' and b.start_time<='$Endtime15' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day15"]=$arr[$i]["num"]-count($day15);
            $arr[$i]["day15s"]=round($arr[$i]["day15"]/$arr[$i]["num"],4)*100;
            //30
            $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
            $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
            $day30=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$Strtime30' and b.start_time<='$Endtime30' and a.register_time>='$Strtime' and a.register_time<='$Endtime'")->distinct(true)->field('a.game_user_id')->select();
            $arr[$i]["day30"]=$arr[$i]["num"]-count($day30);
            $arr[$i]["day30s"]=round($arr[$i]["day30"]/$arr[$i]["num"],4)*100;

        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','新玩家');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','2日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','3日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','4日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','5日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','6日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','7日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','15日流失率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','30日流失率');


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
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,$value['day15'].'('.$value['day15s'].'%)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$key,$value['day30'].'('.$value['day30s'].'%)');




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