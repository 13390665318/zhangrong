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

        //   $_SESSION["db_id"]=1;
        //   $db_id=1;
        $this->assign("db_id",$db_id);

        $type=1;


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
        // var_dump($connection);exit;
        if($type==1){
            //时间
            $arrs=array('日期','新增付费玩家','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','7日留存率','15日留存率','30日留存率');
            //判定 是否 使用计时器  20 分钟刷新一次
            $time=time();// 当前时间
            $usersave=D("paysave")->where("db_id=$db_id")->find();
            $savetime=$usersave["savetime"];
            $chatime=floor(($time-$savetime)%86400);
//

//echo $chatime;exit;
            if($chatime<1200){


                // 直接查表
                $arr=D("paysave")->where("db_id=$db_id and time>='$stime' and time <='$etime' ")->select();
//
            }else{


                //计时器 计算
                $r=D("paysave")->where("db_id=$db_id")->find();

                if($r){

                    // 更新数据
                    $db=D("db")->where("db_id=$db_id")->find();

                    $BeginTime=$db["start_time"];  // 查询开服时间
                    $etime=date("Y-m-d H:i:s",time());
                    $day=count_days($BeginTime,$etime); //
//echo $day;exit;
                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($BeginTime)));
                        $addtime= $arr["time"];
                        // $arr["time2"]=strtotime ("+$i day", strtotime($BeginTime));
                        $arr["db_id"]=$db_id;
                        $Strtime=date('Y-m-d 00:00:00', strtotime($BeginTime));

                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($BeginTime)));
                        // 付费总用户
                        // 今天 - 昨天  = 今日新增付费用户
                            $adduser1=D("pay")->where("pay_time>='$Strtime' and pay_time<='$Endtime'")->field("game_user_id")->group("game_user_id")->select(); //今日;
			 $Endtimes=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($Strtime)-1));
                            $adduser2=D("pay")->where("pay_time>='$Strtime' and pay_time<='$Endtimes' ")->field("game_user_id")->group("game_user_id")->select();
			 $arr["adduser"]=count($adduser1)-count($adduser2);
                            $addpay= array_diff(array_values(array_column($adduser1, 'game_user_id')),array_values(array_column($adduser2, 'game_user_id')));

			$addpay=array_values($addpay);

			$adduser=array();
                            for($j=0;$j<count($addpay);$j++){
                                $adduser[$j]["game_user_id"]=$addpay[$j];
                            }
                        // 2日留存
                        $day2=$i+1;
                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+$day2 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order2=D("sign")->where(" start_time>='$Strtime2' and start_time<='$Endtime2' ")->group("game_user_id")->field("game_user_id")->select();
                        // echo D("sign")->getLastSql();
                        $result2=array_intersect(array_column($adduser,'game_user_id'),array_column($order2, 'game_user_id'));
                        //   var_dump($result2);
                        $arr["day2"]=count($result2);
                        $arr["day2s"]=(int)round($arr["day2"]/$arr["adduser"],4)*100;


                        //3日留存
                        $day2=$i+2;
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order3=D("sign")->where(" start_time>='$Strtime3' and start_time<='$Endtime3' ")->group("game_user_id")->field("game_user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'game_user_id'),array_column($order3, 'game_user_id'));
                        $arr["day3"]=count($result3);
                        $arr["day3s"]=(int)round($arr["day3"]/$arr["adduser"],4)*100;
                        //4日
                        $day2=$i+3;
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order4=D("sign")->where(" start_time>='$Strtime4' and start_time<='$Endtime4'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'game_user_id'),array_column($order4, 'game_user_id'));
                        $arr["day4"]=count($result4);
                        $arr["day4s"]=(int)round($arr["day4"]/$arr["adduser"],4)*100;
                        //5
                        $day2=$i+4;
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order5=D("sign")->where(" start_time>='$Strtime5' and start_time<='$Endtime5'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'game_user_id'),array_column($order5, 'game_user_id'));
                        $arr["day5"]=count($result5);
                        $arr["day5s"]=(int)round($arr["day5"]/$arr["adduser"],4)*100;
                        //6
                        $day2=$i+5;
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order6=D("sign")->where(" start_time>='$Strtime6' and start_time<='$Endtime6'")->group("game_user_id")->field("game_user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'game_user_id'),array_column($order6, 'game_user_id'));
                        $arr["day6"]=count($result6);
                        $arr["day6s"]=(int)round($arr["day6"]/$arr["adduser"],4)*100;
                        //7
                        $day7=$i+6;
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+$day7 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+$day7 day", strtotime($Strtime)));
                        $order7=D("sign")->where(" start_time>='$Strtime7' and start_time<='$Endtime7' ")->group("game_user_id")->field("game_user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'game_user_id'),array_column($order7, 'game_user_id'));
                        $arr["day7"]=count($result7);
                        $arr["day7s"]=(int)round($arr["day7"]/$arr["adduser"],4)*100;
                        //15
                        $day15=$i+14;
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+$day15 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+$day15 day", strtotime($Strtime)));
                        $order15=D("sign")->where(" start_time>='$Strtime15' and start_time<='$Endtime15'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'game_user_id'),array_column($order15, 'game_user_id'));
                        $arr["day15"]=count($result15);
                        $arr["day15s"]=(int)round($arr["day15"]/$arr["adduser"],4)*100;
                        //30
                        $day30=$i+29;
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+$day30 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+$day30 day", strtotime($Strtime)));
                        $order30=D("sign")->where(" start_time>='$Strtime30' and start_time<='$Endtime30'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'game_user_id'),array_column($order30, 'game_user_id'));
                        $arr["day30"]=count($result30);
                        $arr["day30s"]=(int)round($arr["day30"]/$arr["adduser"],4)*100;
                        $arr["savetime"]=time();

                        $res=D("paysave")->where("db_id=$db_id and time='$addtime'")->find();
                        if($res){

                            $ru=D("paysave")->where("db_id=$db_id and time='$addtime'")->save($arr);

                        }else{

                            $ru=D("paysave")->add($arr);
                        }



                    }

                }else{


                    // 新服 需要新增
                    $db=D("db")->where("db_id=$db_id")->find();
                    dump($db);
                    $BeginTime=$db["start_time"];  // 查询开服时间

                    $etime=date("Y-m-d H:i:s",time());
                    $day=count_days($BeginTime,$etime); //

                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($BeginTime)));
                        // $arr["time2"]=strtotime ("+$i day", strtotime($BeginTime));
                        $arr["db_id"]=$db_id;
                        $Strtime=date('Y-m-d 00:00:00', strtotime($BeginTime));

                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($BeginTime)));
                        // 付费总用户
                    
                            // 今天 - 昨天  = 今日新增付费用户
                            $adduser1=D("pay")->where("pay_time>='$Strtime' and pay_time<='$Endtime'")->field("game_user_id")->group("game_user_id")->select();
			 $Endtimes=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($Strtime)-1));
                            $adduser2=D("pay")->where("pay_time>='$Strtime' and pay_time<='$Endtimes'")->field("game_user_id")->group("game_user_id")->select();
			 $arr["adduser"]=count($adduser1)-count($adduser2);
                            $addpay= array_diff(array_values(array_column($adduser1, 'game_user_id')),array_values(array_column($adduser2, 'game_user_id')));

			$addpay=array_values($addpay);
			$adduser=array();
                            for($j=0;$j<count($addpay);$j++){
                                $adduser[$j]["game_user_id"]=$addpay[$j];
                            }
                       


                      // 2日留存
                        $day2=$i+1;
                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+$day2 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order2=D("sign")->where(" start_time>='$Strtime2' and start_time<='$Endtime2' ")->group("game_user_id")->field("game_user_id")->select();
                        $result2=array_intersect(array_column($adduser,'game_user_id'),array_column($order2, 'game_user_id'));
                        $arr["day2"]=count($result2);
                        $arr["day2s"]=(int)round($arr["day2"]/$arr["adduser"],4)*100;


                        //3日留存
                        $day2=$i+2;
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order3=D("sign")->where(" start_time>='$Strtime3' and start_time<='$Endtime3' ")->group("game_user_id")->field("game_user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'game_user_id'),array_column($order3, 'game_user_id'));
                        $arr["day3"]=count($result3);
                        $arr["day3s"]=(int)round($arr["day3"]/$arr["adduser"],4)*100;
                        //4日
                        $day2=$i+3;
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order4=D("sign")->where(" start_time>='$Strtime4' and start_time<='$Endtime4'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'game_user_id'),array_column($order4, 'game_user_id'));
                        $arr["day4"]=count($result4);
                        $arr["day4s"]=(int)round($arr["day4"]/$arr["adduser"],4)*100;
                        //5
                        $day2=$i+4;
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order5=D("sign")->where(" start_time>='$Strtime5' and start_time<='$Endtime5'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'game_user_id'),array_column($order5, 'game_user_id'));
                        $arr["day5"]=count($result5);
                        $arr["day5s"]=(int)round($arr["day5"]/$arr["adduser"],4)*100;
                        //6
                        $day2=$i+5;
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+$day2 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+$day2 day", strtotime($Strtime)));
                        $order6=D("sign")->where(" start_time>='$Strtime6' and start_time<='$Endtime6'")->group("game_user_id")->field("game_user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'game_user_id'),array_column($order6, 'game_user_id'));
                        $arr["day6"]=count($result6);
                        $arr["day6s"]=(int)round($arr["day6"]/$arr["adduser"],4)*100;
                        //7
                        $day7=$i+6;
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+$day7 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+$day7 day", strtotime($Strtime)));
                        $order7=D("sign")->where(" start_time>='$Strtime7' and start_time<='$Endtime7' ")->group("game_user_id")->field("game_user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'game_user_id'),array_column($order7, 'game_user_id'));
                        $arr["day7"]=count($result7);
                        $arr["day7s"]=(int)round($arr["day7"]/$arr["adduser"],4)*100;
                        //15
                        $day15=$i+14;
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+$day15 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+$day15 day", strtotime($Strtime)));
                        $order15=D("sign")->where(" start_time>='$Strtime15' and start_time<='$Endtime15'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'game_user_id'),array_column($order15, 'game_user_id'));
                        $arr["day15"]=count($result15);
                        $arr["day15s"]=(int)round($arr["day15"]/$arr["adduser"],4)*100;
                        //30
                        $day30=$i+29;
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+$day30 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+$day30 day", strtotime($Strtime)));
                        $order30=D("sign")->where(" start_time>='$Strtime30' and start_time<='$Endtime30'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'game_user_id'),array_column($order30, 'game_user_id'));
                        $arr["day30"]=count($result30);
                        $arr["day30s"]=(int)round($arr["day30"]/$arr["adduser"],4)*100;
                        $arr["savetime"]=time();

                      $ru=D("paysave")->add($arr);
                    }
              //   exit;
                }


               $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

                echo "<script>location.href='$url'</script>";
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

        $arr=D("paysave")->where("db_id=$db_id and time>='$stime' and time <='$etime' ")->select();


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