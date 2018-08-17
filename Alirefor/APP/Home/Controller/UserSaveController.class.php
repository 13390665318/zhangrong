<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 2:37
 */

namespace Home\Controller;


class UserSaveController extends BaseController
{
    public function index(){
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        // 图标 默认 最新服
        if ($db_id != 0) {
            $string='and db_id='.$db_id;
        }


        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-5 day"));
            $etime=date("Y-m-d",strtotime("-1 day"));
        }

        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $day=count_days($stime,$etime);


                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
                       // $arr["time2"]=strtotime ("+$i day", strtotime($BeginTime));
                        $arr["db_id"]=$db_id;
                        $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
                        //总用户
                        $where="register_time>='$Strtime' and register_time<='$Endtime'";
                        $where=$where.$string;
                        $adduser=D("user")->group('game_id')->having($where)->select();
                        $arr["adduser"]=count($adduser);
                        // 2日留存
                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime2' and start_time<='$Endtime2' ";
                        $sign2=D("sign")->where($ru)->group("user_id")->field("user_id")->select();

                        $result2=array_intersect(array_column($adduser, 'game_id'),array_column($sign2, 'user_id'));
                        $arr["day2"]=count($result2);
                        if($arr["day2"]===0){
                            $arr["day2s"]=(int)round($arr["day2"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day2s"] =round($arr["day2"] / $arr["adduser"], 4) * 100;
                        }


                        //3日留存
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime3' and start_time<='$Endtime3' ";
                        $sign3=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'game_id'),array_column($sign3, 'user_id'));
                        $arr["day3"]=count($result3);
                        if($arr["day3"]==0){
                            $arr["day3s"]=(int)round($arr["day3"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day3s"] =round($arr["day3"] / $arr["adduser"], 4) * 100;
                        }
                        //4日
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime4' and start_time<='$Endtime4'  ";
                        $sign4=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'game_id'),array_column($sign4, 'user_id'));
                        $arr["day4"]=count($result4);
                        if($arr["day4"]==0){
                            $arr["day4s"]=(int)round($arr["day4"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day4s"] =round($arr["day4"] / $arr["adduser"], 4) * 100;
                        }
                        //5
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime5' and start_time<='$Endtime5' ";
                        $sign5=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'game_id'),array_column($sign5, 'user_id'));
                        $arr["day5"]=count($result5);
                        if($arr["day5"]==0){
                            $arr["day5s"]=(int)round($arr["day5"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day5s"] =round($arr["day5"] / $arr["adduser"], 4) * 100;
                        }
                        //6
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime6' and start_time<='$Endtime6' ";
                        $sign6=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'game_id'),array_column($sign6, 'user_id'));
                        $arr["day6"]=count($result6);
                        if($arr["day6"]==0){
                            $arr["day6s"]=(int)round($arr["day6"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day6s"] =round($arr["day6"] / $arr["adduser"], 4) * 100;
                        }
                        //7
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime7' and start_time<='$Endtime7'";
                        $sign7=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'game_id'),array_column($sign7, 'user_id'));
                        $arr["day7"]=count($result7);
                        if($arr["day7"]==0){
                            $arr["day7s"]=(int)round($arr["day7"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day7s"] =round($arr["day7"] / $arr["adduser"], 4) * 100;
                        }
                        //15
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime15' and start_time<='$Endtime15'  ";
                        $sign15=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'game_id'),array_column($sign15, 'user_id'));
                        $arr["day15"]=count($result15);
                        if($arr["day15"]==0){
                            $arr["day15s"]=(int)round($arr["day15"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day15s"] =round($arr["day15"] / $arr["adduser"], 4) * 100;
                        }
                        //30
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime30' and start_time<='$Endtime30'  ";
                        $sign30=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'game_id'),array_column($sign30, 'user_id'));
                        $arr["day30"]=count($result30);
                        if($arr["day30"]==0){
                            $arr["day30s"]=(int)round($arr["day30"]/$arr["adduser"],4)*100;
                        }else {
                            $arr["day30s"] =round($arr["day30"] / $arr["adduser"], 4) * 100;
                        }
                        $arr1[$i]=$arr;
                    }

        $arrs=array('日期','新玩家','1日留存率','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','15日留存率','30日留存率');
        $this->assign("arrs",$arrs);
        $this->assign("arr",$arr1);
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
        $arr=D("usersave")->where("time>='$stime' and time <='$etime' ")->select();

        $day=count_days($stime,$etime);

            //时间
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','运营平台');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','日期');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','新玩家');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','2日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','3日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','4日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','5日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','6日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','7日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','15日留存率');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','30日留存率');





        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["name"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['time']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['adduser']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,'('.$value['day2'].')|'.$value['day2s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,'('.$value['day3'].')|'.$value['day3s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,'('.$value['day4'].')|'.$value['day4s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,'('.$value['day5'].')|'.$value['day5s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,'('.$value['day6'].')|'.$value['day6s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,'('.$value['day7'].')|'.$value['day7s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$key,'('.$value['day15'].')|'.$value['day15s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$key,'('.$value['day30'].')|'.$value['day30s'].'%');

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


    public function index2(){

    }

}