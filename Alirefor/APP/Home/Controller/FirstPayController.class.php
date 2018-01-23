<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 下午 4:51
 */

namespace Home\Controller;


class FirstPayController extends BaseController
{
    //首付周期分布（人数和比例）
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
        $connection=db($game_id,$db_id);
        //   var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','首日充值','2日充值','3日充值','4日充值','5日充值','6日充值','7日充值');
        for($i=0;$i<=$day;$i++){

            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime))); //日期
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            $arr[$i]["num"]=M('san_userbase as a','',$connection)->where("regtime>='$Strtime' and regtime<='$Endtime'")->count(); // 新玩家
            //首日
            $Strtime2=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime2=strtotime(date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($Strtime))));
            $day2=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime2 and b.timestamp<=$Endtime2 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            //  echo M('san_userbase as a','',$connection)->getLastSql();exit;
            $arr[$i]["day2"]=count($day2);
            $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
            //2ri
            // $Strtime3=date('Y-m-d 00:00:00', strtotime ("+1 day", strtotime($Strtime)));
            $Strtime3=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime3=strtotime(date('Y-m-d 23:59:59', strtotime ("+1 day", strtotime($Strtime))));
            $day3=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime3 and b.timestamp<=$Endtime3 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day3"]=count($day3)-count($day2);
            $arr[$i]["day3s"]=round($arr[$i]["day3"]/$arr[$i]["num"],4)*100;
            //3ri
            $Strtime4=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime4=strtotime(date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($Strtime))));
            $day4=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime4 and b.timestamp<=$Endtime4 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day4"]=count($day4)-count($day3);
            $arr[$i]["day4s"]=round($arr[$i]["day4"]/$arr[$i]["num"],4)*100;

            //4日
            $Strtime5=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime5=strtotime(date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($Strtime))));
            $day5=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime5 and b.timestamp<=$Endtime5 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day5"]=count($day5)-count($day4);
            $arr[$i]["day5s"]=round($arr[$i]["day5"]/$arr[$i]["num"],4)*100;
            //5日
            $Strtime6=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime6=strtotime(date('Y-m-d 23:59:59', strtotime ("+5 day", strtotime($Strtime))));
            $day6=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime6 and b.timestamp<=$Endtime6 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day6"]=count($day6)-count($day5);
            $arr[$i]["day6s"]=round($arr[$i]["day6"]/$arr[$i]["num"],4)*100;

            //6日
            $Strtime7=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime7=strtotime(date('Y-m-d 23:59:59', strtotime ("+6 day", strtotime($Strtime))));
            $day7=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime7 and b.timestamp<=$Endtime7 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day7"]=count($day7)-count($day6);
            $arr[$i]["day7s"]=round($arr[$i]["day7"]/$arr[$i]["num"],4)*100;
            //7
            $Strtime15=strtotime(date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($Strtime))));
            $Endtime15=strtotime(date('Y-m-d 23:59:59', strtotime ("+7 day", strtotime($Strtime))));
            $day8=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.timestamp>=$Strtime15 and b.timestamp<=$Endtime15 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->select();
            $arr[$i]["day15"]=count($day8)-count($day7);
            $arr[$i]["day15s"]=round($arr[$i]["day15"]/$arr[$i]["num"],4)*100;


        }

        // var_dump($arr);exit;
        //var_dump($arr);
        $this->assign("arrs",$arrs);
        $this->assign("arr",$arr);
        $this->display();
    }

    //首付金额分布（人数和比例）
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
        $connection=db($game_id,$db_id);
        //   var_dump($connection);
        //时间
        $arrs=array('日期','新玩家','6元','30元','98','198','328','648',"25月卡","50月卡");
        for($i=0;$i<=$day;$i++){

            $arr[$i]["time"]=date('Y-m-d', strtotime ("+$i day", strtotime($stime))); //日期
            $Strtime=date('Y-m-d 00:00:00', strtotime ("+$i day", strtotime($stime)));
            $Endtime=date('Y-m-d 23:59:59', strtotime ("+$i day", strtotime($stime)));
            //所有充值人数
            $day2=D("order")->where("time>='$Strtime' and time<='$Endtime'")->field("type")->group("uid")->select();
            $arr[$i]["num"]=count($day2);
            for($j=0;$j<count($day2);$j++){
                    if($day2[$j]["type"]==6){
                        $arr[$i]["day2"]=$arr[$i]["day2"]+1;
                    }else if($day2[$j]["type"]==5){
                        $arr[$i]["day3"]=$arr[$i]["day3"]+1;
                    }else if($day2[$j]["type"]==4){
                        $arr[$i]["day4"]=$arr[$i]["day4"]+1;
                    }else if($day2[$j]["type"]==3){
                        $arr[$i]["day5"]=$arr[$i]["day5"]+1;
                    }else if($day2[$j]["type"]==2){
                        $arr[$i]["day6"]=$arr[$i]["day6"]+1;
                    }else if($day2[$j]["type"]==1){
                        $arr[$i]["day7"]=$arr[$i]["day7"]+1;
                    }else if($day2[$j]["type"]==101){
                        $arr[$i]["day8"]=$arr[$i]["day8"]+1;
                    }else if($day2[$j]["type"]==102){
                        $arr[$i]["day9"]=$arr[$i]["day9"]+1;
                    }
            }
            $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day3s"]=round($arr[$i]["day3"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day4s"]=round($arr[$i]["day4"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day5s"]=round($arr[$i]["day5"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day6s"]=round($arr[$i]["day6"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day7s"]=round($arr[$i]["day7"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day8s"]=round($arr[$i]["day8"]/$arr[$i]["num"],4)*100;
            $arr[$i]["day9s"]=round($arr[$i]["day9"]/$arr[$i]["num"],4)*100;
            // 判断6元档
         
            /**        $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
            $day2=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=6 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            //  echo M('san_userbase as a','',$connection)->getLastSql();exit;
            $arr[$i]["day2"]=count($day2);
            $arr[$i]["day2s"]=round($arr[$i]["day2"]/$arr[$i]["num"],4)*100;
            //2ri

            $day3=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=5 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            $arr[$i]["day3"]=count($day3);
            $arr[$i]["day3s"]=round($arr[$i]["day3"]/$arr[$i]["num"],4)*100;
            //3ri

            $day4=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=4 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            $arr[$i]["day4"]=count($day4);
            $arr[$i]["day4s"]=round($arr[$i]["day4"]/$arr[$i]["num"],4)*100;

            //4日

            $day5=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=3 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            $arr[$i]["day5"]=count($day5);
            $arr[$i]["day5s"]=round($arr[$i]["day5"]/$arr[$i]["num"],4)*100;
            //5日

            $day6=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=2 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            $arr[$i]["day6"]=count($day6);
            $arr[$i]["day6s"]=round($arr[$i]["day6"]/$arr[$i]["num"],4)*100;


            $day7=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->where("b.type=1 and a.regtime>='$Strtime' and a.regtime<='$Endtime'")->group("a.uid")->select();
            $arr[$i]["day7"]=count($day7);
            $arr[$i]["day7s"]=round($arr[$i]["day7"]/$arr[$i]["num"],4)*100;

             **/

        }

      //  var_dump($arr);exit;
        //var_dump($arr);
        $this->assign("arrs",$arrs);
        $this->assign("arr",$arr);
        $this->display();
    }
    // 付费排行
    public function  index3(){
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
        $connection=db($game_id,$db_id);
        $count      =M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->group("b.uid")->select();// 查询满足要求的总记录数
        $Page       = new \Think\Page(count($count),20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $day=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,sum(b.money),a.gem,a.regtime,a.lastlogintime")->group("uid")->order("sum(b.money) desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        // echo M('san_userbase as a','',$connection)->getLastSql();
        for($i=0;$i<count($day);$i++){
            $day[$i]["moneys"]=$day[$i]["sum(b.money)"]/100;
            $uid=$day[$i]["uid"];
            $arr=M('san_recharge','',$connection)->where("uid=$uid")->order("id desc")->find();
            $day[$i]["money"]=$arr["money"]/100;
            $day[$i]["timestamp"]=$arr["timestamp"];
        }
        $this->assign("arr",$day);
        $this->display();





    }
// 导出

    public function exl3(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;

        $db_id=I("get.db_id");
        $connection=db($game_id,$db_id);

        $data=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,sum(b.money),a.gem,a.regtime")->group("uid")->order("sum(b.money) desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        //  echo M('san_userbase as a','',$connection)->getLastSql();
        for($i=0;$i<count($data);$i++){
            $data[$i]["money"]=$data[$i]["sum(b.money)"]/100;
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','音响线');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','用户编号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','用户昵称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','用户等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','用户充值金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','剩余元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','注册时间');

        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$key-1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['uid']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value["uname"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value["money"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value['gem']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value["regtime"]);





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

    public function index4(){
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
        $connection=db($game_id,$db_id);
        $count      =M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $day=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,b.money,a.gem,b.dealtime")->order("b.dealtime desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign("arr",$day);
        $this->display();
    }

    public function exl4(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;

        $db_id=I("get.db_id");
        $connection=db($game_id,$db_id);

        $day=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,b.money,a.gem,b.dealtime")->order("b.dealtime desc")->select();
        //  echo M('san_userbase as a','',$connection)->getLastSql();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','序号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','用户编号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','用户昵称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','用户等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','用户充值金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','剩余元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','充值时间');

        //把数据循环写入excel中
        $i=1;
        foreach($day as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$key-1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['uid']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value["uname"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value["money"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value['gem']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,date("Y-m-d H:i:s",$value["dealtime"]));





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

    public function  index5(){
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
        $connection=db($game_id,$db_id);
        $count      =M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $day=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,sum(b.money),a.gem,a.regtime")->group("uid")->order("sum(b.money) desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        //  echo M('san_userbase as a','',$connection)->getLastSql();
        for($i=0;$i<count($day);$i++){
            $day[$i]["money"]=$day[$i]["sum(b.money)"]/100;
        }
        $this->assign("arr",$day);
        $this->display();
    }
//首付等级分布（人数）
    public function index6(){
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
        $connection=db($game_id,$db_id);
        //$day=M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->field("a.uid,a.uname,a.level,b.money,a.gem,a.regtime")->select();
        $data=M('san_recharge as a','',$connection)->group('uid')->select();
        for($i=0;$i<80;$i++){
$arr[$i]["num"]=0;
            $level=$i+1;
            $arr[$i]["level"]=$level;
            for($j=0;$j<count($data);$j++){
                if($data[$j]["level"]==$level){
                    $arr[$i]["num"]++;
                }
            }
            $arr[$i]["nums"]=round($arr[$i]["num"]/count($data),4)*100;
        }
//var_dump($arr);exit;
        $this->assign("arr",$arr);
        $this->display();
    }
//不同等级的付费金额
    public function index7(){
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
        $connection=db($game_id,$db_id);
        for($i=0;$i<80;$i++){
            $level=$i+1;
            $arr[$i]["level"]=$level;
            $arr[$i]["money"]=M('san_recharge as a','',$connection)->where("level=$level")->sum(money)/100;
            $arr[$i]["num"]=M('san_recharge as a','',$connection)->where("level=$level")->count();


        }
        $this->assign("arr",$arr);
        $this->display();
    }

    public function exl7(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        // 图标 默认 最新服
        $db_id=I("get.db_id");

        $connection=db($game_id,$db_id);
        for($i=0;$i<80;$i++){
            $level=$i+1;
            $arr[$i]["level"]=$level;
            $arr[$i]["money"]=M('san_recharge as a','',$connection)->where("level=$level")->sum(money)/100;
            $arr[$i]["num"]=M('san_recharge as a','',$connection)->where("level=$level")->count();


        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','用户数');


        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['money']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value["num"]);





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



//单笔付费中不同金额的用户数
    public function index9(){
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
        $connection=db($game_id,$db_id);
        // 6   6元
        //5    30
        //4 98
        //3 198   2 328  1 648
        $sum=M('san_recharge as a','',$connection)->count();
        $arr[0]["name"]="6元";
        $arr[0]["num"]=M('san_recharge as a','',$connection)->where("type=6")->count();
        $arr[0]["nums"]=round($arr[0]["num"]/$sum,4)*100;

        $arr[1]["name"]="30元";
        $arr[1]["num"]=M('san_recharge as a','',$connection)->where("type=5")->count();
        $arr[1]["nums"]=round($arr[1]["num"]/$sum,4)*100;

        $arr[2]["name"]="98元";
        $arr[2]["num"]=M('san_recharge as a','',$connection)->where("type=4")->count();
        $arr[2]["nums"]=round($arr[2]["num"]/$sum,4)*100;

        $arr[3]["name"]="198元";
        $arr[3]["num"]=M('san_recharge as a','',$connection)->where("type=3")->count();
        $arr[3]["nums"]=round($arr[3]["num"]/$sum,4)*100;

        $arr[4]["name"]="328元";
        $arr[4]["num"]=M('san_recharge as a','',$connection)->where("type=2")->count();
        $arr[4]["nums"]=round($arr[4]["num"]/$sum,4)*100;

        $arr[5]["name"]="648元";
        $arr[5]["num"]=M('san_recharge as a','',$connection)->where("type=1")->count();
        $arr[5]["nums"]=round($arr[5]["num"]/$sum,4)*100;


        $this->assign("arr",$arr);
        $this->display();
    }
}