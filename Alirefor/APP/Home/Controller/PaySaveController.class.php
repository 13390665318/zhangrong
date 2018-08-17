<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 2:37
 */

namespace Home\Controller;


class PaySaveController extends BaseController
{
    public function index(){

        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $ru['db_id'] = $db_id;
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

        // var_dump($connection);exit;
            //时间
            $arrs=array('日期','新增付费玩家','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','7日留存率','15日留存率','30日留存率');
            // 直接查表

                    // 更新数据
                    $db=D("db")->find();
                    $BeginTime=$db["start_time"];  // 查询开服时间
                    $etime=date("Y-m-d H:i:s",time());
                    $day=count_days($stime,$etime); //

                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
                        $Strtime=date('Y-m-d 00:00:00', strtotime("+$i day", strtotime($stime)));
                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));

                        // 付费总用户
                        //历史付费用户ID 对比今天付费用户ID取不同
                    $ru['_string']="pay_time>='$Strtime' and pay_time<='$Endtime'";
                    $adduser1=D("pay")->where($ru)->field("user_id")->group("user_id")->select();

			        $Endtimes=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)-1));
			        $ru['_string']="pay_time>='$BeginTime' and pay_time<='$Endtimes'";
                    $adduser2=D("pay")->where($ru)->field("user_id")->group("user_id")->select();
			        $arr["adduser"]=count($adduser1)-count($adduser2);
                    $addpay= array_diff(array_values(array_column($adduser1, 'user_id')),array_values(array_column($adduser2, 'user_id')));
                    $arr["adduser"]=count($addpay);
			        $addpay=array_values($addpay);
			$adduser=array();
                            for($j=0;$j<count($addpay);$j++){
                                $adduser[$j]["user_id"]=$addpay[$j];
                            }
                      // 2日留存

                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime2' and start_time<='$Endtime2' ";
                        $order2=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result2=array_intersect(array_column($adduser,'user_id'),array_column($order2, 'user_id'));
                        $arr["day2"]=count($result2);
                        if($arr["day2"]==0){
                            $arr["day2s"]=(int)round($arr["day2"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day2s"]=round($arr["day2"]/$arr["adduser"],4)*100;
                        }


                        //3日留存
                        $day2=$i+2;
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime3' and start_time<='$Endtime3' ";
                        $order3=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'user_id'),array_column($order3, 'user_id'));
                        $arr["day3"]=count($result3);
                        if($arr["day3"]==0){
                            $arr["day3s"]=(int)round($arr["day3"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day3s"]=round($arr["day3"]/$arr["adduser"],4)*100;
                        }
                        //4日
                        $day2=$i+3;
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime4' and start_time<='$Endtime4'  ";
                        $order4=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'user_id'),array_column($order4, 'user_id'));
                        $arr["day4"]=count($result4);
                        if($arr["day4"]==0){
                            $arr["day4s"]=(int)round($arr["day4"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day4s"]=round($arr["day4"]/$arr["adduser"],4)*100;
                        }
                        //5
                        $day2=$i+4;
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime5' and start_time<='$Endtime5'  ";
                        $order5=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'user_id'),array_column($order5, 'user_id'));
                        $arr["day5"]=count($result5);
                        if($arr["day5"]==0){
                            $arr["day5s"]=(int)round($arr["day5"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day5s"]=round($arr["day5"]/$arr["adduser"],4)*100;
                        }
                        //6
                        $day2=$i+5;
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime6' and start_time<='$Endtime6'";
                        $order6=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'user_id'),array_column($order6, 'user_id'));
                        $arr["day6"]=count($result6);
                        if($arr["day6"]==0){
                            $arr["day6s"]=(int)round($arr["day6"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day6s"]=round($arr["day6"]/$arr["adduser"],4)*100;
                        }
                        //7
                        $day7=$i+6;
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime7' and start_time<='$Endtime7' ";
                        $order7=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'user_id'),array_column($order7, 'user_id'));
                        $arr["day7"]=count($result7);
                        if($arr["day7"]==0){
                            $arr["day7s"]=(int)round($arr["day7"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day7s"]=round($arr["day7"]/$arr["adduser"],4)*100;
                        }
                        //15
                        $day15=$i+14;
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime15' and start_time<='$Endtime15'  ";
                        $order15=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'user_id'),array_column($order15, 'user_id'));
                        $arr["day15"]=count($result15);
                        if($arr["day15"]==0){
                            $arr["day15s"]=(int)round($arr["day15"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day15s"]=round($arr["day15"]/$arr["adduser"],4)*100;
                        }
                        //30
                        $day30=$i+29;
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                        $ru['_string']=" start_time>='$Strtime30' and start_time<='$Endtime30'  ";
                        $order30=D("sign")->where($ru)->group("user_id")->field("user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'user_id'),array_column($order30, 'user_id'));
                        $arr["day30"]=count($result30);
                        if($arr["day30"]==0){
                            $arr["day30s"]=(int)round($arr["day30"]/$arr["adduser"],4)*100;
                        }else{
                            $arr["day30s"]=round($arr["day30"]/$arr["adduser"],4)*100;
                        }

                        $arr1[$i]=$arr;//将数组循环添加
                    }

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

        $arr=D("paysave")->where("time>='$stime' and time <='$etime' ")->select();


        //时间
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','付费玩家');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','2日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','3日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','4日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','5日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','6日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','7日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','15日留存率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','30日留存率');







        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['adduser']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,'('.$value['day2'].')|'.$value['day2s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,'('.$value['day3'].')|'.$value['day3s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,'('.$value['day4'].')|'.$value['day4s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,'('.$value['day5'].')|'.$value['day5s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,'('.$value['day6'].')|'.$value['day6s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,'('.$value['day7'].')|'.$value['day7s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,'('.$value['day15'].')|'.$value['day15s'].'%');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$key,'('.$value['day30'].')|'.$value['day30s'].'%');

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