<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 2:37
 */

namespace Home\Controller;


class TopController extends BaseController
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
        //var_dump($connection);exit;
        if($type==1){
            //时间
            $arrs=array('日期','新玩家','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','7日留存率','15日留存率','30日留存率');
            //判定 是否 使用计时器  20 分钟刷新一次
            $time=time();// 当前时间
            $usersave=D("usersave")->where("db_id=$db_id")->find();
            $savetime=$usersave["savetime"];
            $chatime=floor(($time-$savetime)%86400);
//echo $chatime;
            if($chatime<1200){
                // 直接查表
                $arr=D("usersave")->where("db_id=$db_id and time>='$stime' and time <='$etime' ")->select();
            }else{
                //计时器 计算
                $r=D("usersave")->where("db_id=$db_id")->find();

                if($r){
                    // 更新数据
                    $db=D("db")->where("db_id=$db_id")->find();

                    $BeginTime=$db["start_time"];  // 查询开服时间
                    $etime=date("Y-m-d H:i:s",time());
                    $day=count_days($BeginTime,$etime); //
//echo $day;exit;
                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($BeginTime)));
                        $addtime=$arr["time"];
                        $arr["time2"]=strtotime ("+$i day", strtotime($BeginTime));
                        $arr["db_id"]=$db_id;
                        $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($BeginTime)));
                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($BeginTime)));
//var_dump($arr);
                        //总用户
                        $adduser=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and source!=' ' ")->field("game_user_id")->select();

                        $arr["adduser"]=count($adduser);
                        // 2日留存
                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                        $sign2=D("sign")->where(" start_time>='$Strtime2' and start_time<='$Endtime2' ")->group("game_user_id")->field("game_user_id")->select();

                        $result2=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign2, 'game_user_id'));
                        $arr["day2"]=count($result2);
                        $arr["day2s"]=round($arr["day2"]/$arr["adduser"],4)*100;


                        //3日留存
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                        $sign3=D("sign")->where(" start_time>='$Strtime3' and start_time<='$Endtime3' ")->group("game_user_id")->field("game_user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign3, 'game_user_id'));
                        $arr["day3"]=count($result3);
                        $arr["day3s"]=round($arr["day3"]/$arr["adduser"],4)*100;
                        //4日
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                        $sign4=D("sign")->where(" start_time>='$Strtime4' and start_time<='$Endtime4'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign4, 'game_user_id'));
                        $arr["day4"]=count($result4);
                        $arr["day4s"]=round($arr["day4"]/$arr["adduser"],4)*100;
                        //5
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                        $sign5=D("sign")->where(" start_time>='$Strtime5' and start_time<='$Endtime5' ")->group("game_user_id")->field("game_user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign5, 'game_user_id'));
                        $arr["day5"]=count($result5);
                        $arr["day5s"]=round($arr["day5"]/$arr["adduser"],4)*100;
                        //6
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                        $sign6=D("sign")->where(" start_time>='$Strtime6' and start_time<='$Endtime6' ")->group("game_user_id")->field("game_user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign6, 'game_user_id'));
                        $arr["day6"]=count($result6);
                        $arr["day6s"]=round($arr["day6"]/$arr["adduser"],4)*100;
                        //7
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                        $sign7=D("sign")->where(" start_time>='$Strtime7' and start_time<='$Endtime7'")->group("game_user_id")->field("game_user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign7, 'game_user_id'));
                        $arr["day7"]=count($result7);
                        $arr["day7s"]=round($arr["day7"]/$arr["adduser"],4)*100;
                        //15
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                        $sign15=D("sign")->where(" start_time>='$Strtime15' and start_time<='$Endtime15'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign15, 'game_user_id'));
                        $arr["day15"]=count($result15);
                        $arr["day15s"]=round($arr["day15"]/$arr["adduser"],4)*100;
                        //30
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                        $sign30=D("sign")->where(" start_time>='$Strtime30' and start_time<='$Endtime30'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign30, 'game_user_id'));
                        $arr["day30"]=count($result30);
                        $arr["day30s"]=round($arr["day30"]/$arr["adduser"],4)*100;
                        $arr["savetime"]=time();

	$res=D("usersave")->where("db_id=$db_id and time='$addtime'")->find();
	if($res){
$ru=D("usersave")->where("db_id=$db_id and time='$addtime'")->save($arr);
	}else{
$ru=D("usersave")->add($arr);
	}
	
			

				

                      // 
//echo $ru;
//echo D("usersave")->getLastSql();exit;

		}

//dump($ru);
//exit;











                }else{
                    // 新服 需要新增
                    $db=D("db")->where("db_id=$db_id")->find();

                    $BeginTime=$db["start_time"];  // 查询开服时间
                    $etime=date("Y-m-d H:i:s",time());
                    $day=count_days($BeginTime,$etime); //
                    for($i=0;$i<=$day;$i++){
                        $arr["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($BeginTime)));
                        $arr["time2"]=strtotime ("+$i day", strtotime($BeginTime));
                        $arr["db_id"]=$db_id;
                        $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($BeginTime)));
                        $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($BeginTime)));
                        //总用户
                        $adduser=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and source!=' ' ")->field("game_user_id")->select();

                        $arr["adduser"]=count($adduser);
                        // 2日留存
                        $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day",strtotime($Strtime)));
                        $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                        $sign2=D("sign")->where(" start_time>='$Strtime2' and start_time<='$Endtime2' ")->group("game_user_id")->field("game_user_id")->select();

                        $result2=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign2, 'game_user_id'));
                        $arr["day2"]=count($result2);
                        $arr["day2s"]=round($arr["day2"]/$arr["adduser"],4)*100;


                        //3日留存
                        $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                        $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                        $sign3=D("sign")->where(" start_time>='$Strtime3' and start_time<='$Endtime3' ")->group("game_user_id")->field("game_user_id")->select();
                        $result3=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign3, 'game_user_id'));
                        $arr["day3"]=count($result3);
                        $arr["day3s"]=round($arr["day3"]/$arr["adduser"],4)*100;
                        //4日
                        $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                        $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                        $sign4=D("sign")->where(" start_time>='$Strtime4' and start_time<='$Endtime4'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result4=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign4, 'game_user_id'));
                        $arr["day4"]=count($result4);
                        $arr["day4s"]=round($arr["day4"]/$arr["adduser"],4)*100;
                        //5
                        $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                        $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                        $sign5=D("sign")->where(" start_time>='$Strtime5' and start_time<='$Endtime5' ")->group("game_user_id")->field("game_user_id")->select();
                        $result5=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign5, 'game_user_id'));
                        $arr["day5"]=count($result5);
                        $arr["day5s"]=round($arr["day5"]/$arr["adduser"],4)*100;
                        //6
                        $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                        $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                        $sign6=D("sign")->where(" start_time>='$Strtime6' and start_time<='$Endtime6' ")->group("game_user_id")->field("game_user_id")->select();
                        $result6=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign6, 'game_user_id'));
                        $arr["day6"]=count($result6);
                        $arr["day6s"]=round($arr["day6"]/$arr["adduser"],4)*100;
                        //7
                        $Strtime7=date('Y-m-d 00:00:00', strtotime ("+7 day",strtotime($Strtime)));
                        $Endtime7=date('Y-m-d 23:59:59', strtotime ("+7 day", strtotime($Strtime)));
                        $sign7=D("sign")->where(" start_time>='$Strtime6' and start_time<='$Endtime7'")->group("game_user_id")->field("game_user_id")->select();
                        $result7=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign7, 'game_user_id'));
                        $arr["day7"]=count($result7);
                        $arr["day7s"]=round($arr["day7"]/$arr["adduser"],4)*100;
                        //15
                        $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                        $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                        $sign15=D("sign")->where(" start_time>='$Strtime15' and start_time<='$Endtime15'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result15=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign15, 'game_user_id'));
                        $arr["day15"]=count($result15);
                        $arr["day15s"]=round($arr["day15"]/$arr["adduser"],4)*100;
                        //30
                        $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                        $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                        $sign30=D("sign")->where(" start_time>='$Strtime30' and start_time<='$Endtime30'  ")->group("game_user_id")->field("game_user_id")->select();
                        $result30=array_intersect(array_column($adduser, 'game_user_id'),array_column($sign30, 'game_user_id'));
                        $arr["day30"]=count($result30);
                        $arr["day30s"]=round($arr["day30"]/$arr["adduser"],4)*100;
                        $arr["savetime"]=time();
                        $ru=D("usersave")->add($arr);



                        // dump($arr);

                    }

                }

                $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                echo "<script>location.href='$url'</script>";
            }





        }
        //var_dump($arr);
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

        // 选择运营平台
        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=D("user")->where("game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["source"]=$stu[$i];
                }
            }

        }else{
            //默认全渠道
            $rus=D("user")->where("game_id=$game_id and db_id=$db_id")->field("source")->group("source")->select();
        }

        //选择汇总方式
        if(isset($_GET["type"])){
            $type=I("get.type");
        }else{
            $type=1;
        }
        $stime=I("get.stime");
        $etime=I("get.etime");


        $day=count_days($stime,$etime);
        if($type==1){
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

            $arrs=array('运营平台','日期','新玩家','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','7日留存率','15日留存率','30日留存率');
            for($i=0;$i<=$day;$i++){
                $arr[$i]["name"]="--";
                $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
                $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
                $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
                $arr[$i]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'  and game_id=$game_id and db_id=$db_id")->count();
                //2ri
                $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day", strtotime($Strtime)));
                $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));
                $arr[$i]["day2"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and end_time>='$Strtime2' and end_time<='$Endtime2' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
                //3ri
                $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                $arr[$i]["day3"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and end_time>='$Strtime3' and end_time<='$Endtime3' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day3s"]=round($arr[$i]["day3"]/$arr[$i]["num"],4)*100;
                //4ri
                $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                $arr[$i]["day4"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and end_time>='$Strtime4' and end_time<='$Endtime4' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day4s"]=round($arr[$i]["day4"]/$arr[$i]["num"],4)*100;
                //5
                $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                $arr[$i]["day5"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and end_time>='$Strtime5' and end_time<='$Endtime5' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day5s"]=round($arr[$i]["day5"]/$arr[$i]["num"],4)*100;
                //6
                $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                $arr[$i]["day6"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and end_time>='$Strtime6' and end_time<='$Endtime6' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day6s"]=round($arr[$i]["day6"]/$arr[$i]["num"],4)*100;
                //7
                $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day", strtotime($Strtime)));
                $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                $arr[$i]["day7"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and end_time>='$Strtime7' and end_time<='$Endtime7' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day7s"]=round($arr[$i]["day7"]/$arr[$i]["num"],4)*100;
                //15
                $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                $arr[$i]["day15"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and end_time>='$Strtime15' and end_time<='$Endtime15' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day15s"]=round($arr[$i]["day15"]/$arr[$i]["num"],4)*100;
                //30
                $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                $arr[$i]["day30"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and end_time>='$Strtime30' and end_time<='$Endtime30' and game_id=$game_id and db_id=$db_id")->count();
                $arr[$i]["day30s"]=round($arr[$i]["day30"]/$arr[$i]["num"],4)*100;

            }
        }else if($type==2){
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
            $arrs=array('运营平台','日期','新玩家','2日留存率','3日留存率','4日留存率','5日留存率','6日留存率','7日留存率','15日留存率','30日留存率');
            for($i=0;$i<=$day;$i++){
                for($j=0;$j<count($rus);$j++){
                    $arr[$i][$j]["name"]=$rus[$j]["source"];
                    $arr[$i][$j]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime)));
                    $source=$rus[$j]["source"];
                    $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
                    $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
                    $arr[$i][$j]["num"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime' and source='$source'  and game_id=$game_id and db_id=$db_id")->count();
                    //2ri
                    $Strtime2=date('Y-m-d 00:00:00', strtotime ("+1 day", strtotime($Strtime)));
                    $Endtime2=date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime)));

                    $arr[$i][$j]["day2"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source' and end_time>='$Strtime2' and end_time<='$Endtime2' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day2s"]=round($arr[$i][$j]["day2"]/$arr[$i][$j]["num"],4)*100;
                    //3ri
                    $Strtime3=date('Y-m-d 00:00:00', strtotime ("+2 day", strtotime($Strtime)));
                    $Endtime3=date('Y-m-d 23:59:59', strtotime ("+2 day", strtotime($Strtime)));
                    $arr[$i][$j]["day3"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source'and end_time>='$Strtime3' and end_time<='$Endtime3' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day3s"]=round($arr[$i][$j]["day3"]/$arr[$i][$j]["num"],4)*100;
                    //4ri
                    $Strtime4=date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($Strtime)));
                    $Endtime4=date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime)));
                    $arr[$i][$j]["day4"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source'and end_time>='$Strtime4' and end_time<='$Endtime4' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day4s"]=round($arr[$i][$j]["day4"]/$arr[$i][$j]["num"],4)*100;
                    //5
                    $Strtime5=date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($Strtime)));
                    $Endtime5=date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime)));
                    $arr[$i][$j]["day5"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source'and end_time>='$Strtime5' and end_time<='$Endtime5' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day5s"]=round($arr[$i][$j]["day5"]/$arr[$i][$j]["num"],4)*100;
                    //6
                    $Strtime6=date('Y-m-d 00:00:00', strtotime ("+5 day", strtotime($Strtime)));
                    $Endtime6=date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime)));
                    $arr[$i][$j]["day6"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source'and end_time>='$Strtime6' and end_time<='$Endtime6' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day6s"]=round($arr[$i][$j]["day6"]/$arr[$i][$j]["num"],4)*100;
                    //7
                    $Strtime7=date('Y-m-d 00:00:00', strtotime ("+6 day", strtotime($Strtime)));
                    $Endtime7=date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime)));
                    $arr[$i][$j]["day7"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source'and end_time>='$Strtime7' and end_time<='$Endtime7' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day7s"]=round($arr[$i][$j]["day7"]/$arr[$i][$j]["num"],4)*100;
                    //15
                    $Strtime15=date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($Strtime)));
                    $Endtime15=date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($Strtime)));
                    $arr[$i][$j]["day15"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source' and end_time>='$Strtime15' and end_time<='$Endtime15' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day15s"]=round($arr[$i][$j]["day15"]/$arr[$i][$j]["num"],4)*100;
                    //30
                    $Strtime30=date('Y-m-d 00:00:00', strtotime ("+29 day", strtotime($Strtime)));
                    $Endtime30=date('Y-m-d 23:59:59', strtotime ("+29 day", strtotime($Strtime)));
                    $arr[$i][$j]["day30"]=D("user")->where("register_time>='$Strtime' and register_time<='$Endtime'and source='$source' and end_time>='$Strtime30' and end_time<='$Endtime30' and game_id=$game_id and db_id=$db_id")->count();
                    $arr[$i][$j]["day30s"]=round($arr[$i][$j]["day30"]/$arr[$i][$j]["num"],4)*100;
                }
            }
            $arr2=array();
            foreach($arr as $value)
            {
                foreach($value as $v){
                    $arr2[]=$v;
                    unset($arr,$value,$v);
                }
            }
            $arr=$arr2;


        }




        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["name"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['time']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['num']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,'('.$value['day2'].')|'.$value['day2s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,'('.$value['day3'].')|'.$value['day3s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,'('.$value['day4'].')|'.$value['day4s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,'('.$value['day5'].')|'.$value['day5s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,'('.$value['day6'].')|'.$value['day6s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,'('.$value['day7'].')|'.$value['day7s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$key,'('.$value['day15'].')|'.$value['day15s']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$key,'('.$value['day30'].')|'.$value['day30s']);

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