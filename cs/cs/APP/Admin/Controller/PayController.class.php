<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 下午 7:25
 */

namespace Admin\Controller;


class PayController extends BaseController
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
        //计算开服时间
        $stu=D("db")->where("game_id=$game_id and db_id='$db_id'")->find();
        $start_time=$stu["time"];
       $time=date("Y-m-d",time());
       // $time="2018-09-01";
        $MonthNum=getMonthNum($start_time,$time);
        for($i=0;$i<=$MonthNum;$i++){
             $TimeStu[$i]["time"]=date("Y-m", strtotime("+$i month", strtotime($start_time)));
        }
        $this->assign("TimeStu",$TimeStu);
        if(isset($_GET["time"])){
            $time=I("get.time");
        }else{
            $time=date("Y-m", strtotime("+$MonthNum month", strtotime($start_time)));
        }
        $this->assign("time",$time);
        // 计算  注册后 1 4 5 15 天充值人数
            $begin_time1= date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($start_time)));
            $end_time1= date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($start_time)));
            $day1_num=D("pay")->where("pay_time>='$begin_time1' and pay_time <='$end_time1' and game_id=$game_id and db_id=$db_id ")->count();
            $this->assign("day1_num",$day1_num);
            $begin_time4= date('Y-m-d 00:00:00', strtotime ("+3 day", strtotime($start_time)));
            $end_time4= date('Y-m-d 23:59:59', strtotime ("+3 day", strtotime($start_time)));

            $day4_num=D("pay")->where("pay_time>='$begin_time4' and pay_time<='$end_time4' and game_id=$game_id and db_id=$db_id")->count();
        $this->assign("day4_num",$day4_num);
            $begin_time5= date('Y-m-d 00:00:00', strtotime ("+4 day", strtotime($start_time)));
            $end_time5= date('Y-m-d 23:59:59', strtotime ("+4 day", strtotime($start_time)));
            $day5_num=D("pay")->where("pay_time>='$begin_time5' and pay_time<='$end_time5' and game_id=$game_id and db_id=$db_id")->count();
        $this->assign("day5_num",$day5_num);
            $begin_time15= date('Y-m-d 00:00:00', strtotime ("+14 day", strtotime($start_time)));
            $end_time15= date('Y-m-d 23:59:59', strtotime ("+14 day", strtotime($start_time)));
            $day15_num=D("pay")->where("pay_time>='$begin_time15' and pay_time<='$end_time15' and game_id=$game_id and db_id=$db_id")->count();
        $this->assign("day15_num",$day15_num);
    // 统计充值月报表

        $endtime=strtotime($time);
        if(date('Y-m', $endtime)==date('Y-m', time())){
            $year=date('Y', $endtime);// 年
            $month=date('m', $endtime); // 当前月
            $day=(int)date('d', time());
        }else{
            $year=date('Y', $endtime);// 年
            $month=date('m', $endtime); // 当前月
            $day= (int)date('t', strtotime($time));
        }
        $sum_money=0;
        for($i=1;$i<=$day;$i++){
            if($i<10){
                $btime="$year-$month-0$i 00:00:00";
                $etime="$year-$month-0$i 23:59:59";
                $arr[$i]["time"]="$year-$month-0$i";
            }else{
                $btime="$year-$month-$i 00:00:00";
                $etime="$year-$month-$i 23:59:59";
                $arr[$i]["time"]="$year-$month-$i";
            }
            //充值人数
            $arr[$i]["user_num"]=D("pay")->where("pay_time>='$btime' and pay_time<='$etime' and game_id=$game_id and db_id=$db_id")->count('distinct(user_id)');
            if($arr[$i]["user_num"]==null){
                $arr[$i]["user_num"]=0;
            }
           //充值次数
            $arr[$i]["num"]=D("pay")->where("pay_time>='$btime' and pay_time<='$etime' and game_id=$game_id and db_id=$db_id")->count();
            if($arr[$i]["num"]==null){
                $arr[$i]["num"]=0;
            }
            //金额总数
            $arr[$i]["money"]=D("pay")->where("pay_time>='$btime' and pay_time<='$etime' and game_id=$game_id and db_id=$db_id")->sum("money");
            if($arr[$i]["money"]==null){
                $arr[$i]["money"]=0;
            }
            $sum_money=$sum_money+$arr[$i]["money"];
            //ARPU
           $arr[$i]["arpu"]=round($arr[$i]["money"]/ $arr[$i]["user_num"],2);
        }
        $arr= array_reverse($arr);
        $this->assign("sum_money",$sum_money);
        $this->assign("arr",$arr);
        //查询平台
        $Starr=D("pay")->where("game_id=$game_id and db_id=$db_id")->field("source")->distinct(true)->select();
        $this->assign("Starr",$Starr);
         $this->display();
    }

    /**
     * 查看具体详细数据
     */
    public function show(){
        if(isset($_GET["time"])){
            if (isset($_SESSION["db_id"]) && isset($_SESSION["game_id"])) {
                $db_id = $_SESSION["db_id"];
                $game_id = $_SESSION["game_id"];
            } else {
                $str = D("game")->where("game_id=1")->find();
                $game_id = 1;
                $db_id = 1;
            }
        $time=I("get.time");
            $btime="$time 00:00:00";
            $etime="$time 23:59:59";
            $data=D("pay")->where("pay_time>='$btime' and pay_time<='$etime' and game_id=$game_id and db_id=$db_id")->select();
            $data=json_encode($data);
            echo $data;
    }

}

/**
 * 充值记录查询
 */
public function goselect(){
    if (isset($_SESSION["db_id"]) && isset($_SESSION["game_id"])) {
        $db_id = $_SESSION["db_id"];
        $game_id = $_SESSION["game_id"];
    } else {
        $db_id=1;
        $game_id = 1;

    }
    if($_GET["start_time"]!=''){
        $arr["source"]=I("get.pingtai");
        $start_time=I("get.start_time")." 00:00:00";
        $end_time=I("get.end_time")." 23:59:59";
        $arr["game_user_name"]=I("get.game_user_name");
        $arr["game_user_id"]=I("get.game_user_id");
        $arr["pay_number"]=I("get.pay_number");
        $arr["game_id"]=$game_id;
        $arr["db_id"]=$db_id;
        $arr=array_filter($arr);
        $data=D("pay")->where($arr)->select();
        for($i=0;$i<count($data);$i++){
            if($data[$i]["pay_time"]>=$start_time && $data[$i]["pay_time"]<=$end_time ){
                $stu[$i]=$data[$i];
            }
        }
        $arr=array_filter($stu);
        $arr=array_values($arr);
        $data=json_encode($arr);
    }else{
        $arr["source"]=I("get.pingtai");
        $arr["game_user_name"]=I("get.game_user_name");
        $arr["game_user_id"]=I("get.game_user_id");
        $arr["pay_number"]=I("get.pay_number");
        $arr["game_id"]=$game_id;
        $arr["db_id"]=$db_id;
        $arr=array_filter($arr);
        $data=D("pay")->where($arr)->select();
        $data=json_encode($data);
    }


    echo $data;
}

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        if (isset($_SESSION["db_id"]) && isset($_SESSION["game_id"])) {
            $db_id = $_SESSION["db_id"];
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
            $db_id=1;
        }
        if($_GET["start_time"]!=''){
            $arr["source"]=I("get.pingtai");
            $start_time=I("get.start_time")." 00:00:00";
            $end_time=I("get.end_time")." 23:59:59";
            $arr["game_user_name"]=I("get.game_user_name");
            $arr["game_user_id"]=I("get.game_user_id");
            $arr["pay_number"]=I("get.pay_number");
            $arr["game_id"]=$game_id;
            $arr["db_id"]=$db_id;
            $arr=array_filter($arr);
            $data=D("pay")->where($arr)->select();
            for($i=0;$i<count($data);$i++){
                if($data[$i]["pay_time"]>=$start_time && $data[$i]["pay_time"]<=$end_time ){
                    $stu[$i]=$data[$i];
                }
            }
            $arr=array_filter($stu);
            $data=array_values($arr);

        }else{
            $arr["source"]=I("get.pingtai");
            $arr["game_user_name"]=I("get.game_user_name");
            $arr["game_user_id"]=I("get.game_user_id");
            $arr["pay_number"]=I("get.pay_number");
            $arr["game_id"]=$game_id;
            $arr["db_id"]=$db_id;
            $arr=array_filter($arr);
            $data=D("pay")->where($arr)->select();

        }

        $objPHPExcel=new \PHPExcel();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','平台');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','充值方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','充值号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','人民币');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','充值时间');
        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["source"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['game_user_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['game_user_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value['pay_type']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value['pay_number']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value['money']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,$value['acer']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$key,$value['pay_time']);

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
    public function lists(){
        if (isset($_SESSION["game_id"]) ) {
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
            $db_id=$clostu[0]["db_id"];
            $_SESSION["db_id"]=$db_id;
        }
        $this->assign("db_id",$db_id);
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-01",time());
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        $STime=date('Y-m-d 00:00:00', strtotime ("+0 day", strtotime($stime)));
        $ETime=date('Y-m-d 23:59:59', strtotime ("+0 day", strtotime($etime)));
        $arr["num"]=D("pay")->where("pay_time>='$STime' and pay_time<='$ETime' and game_id=$game_id and db_id=$db_id")->count('distinct(user_id)');
        $arr["money"]=D("pay")->where("pay_time>='$STime' and pay_time<='$ETime' and game_id=$game_id and db_id=$db_id")->sum("money");
        $arr["%"]=round($arr["money"]/$arr["num"],2);
        $this->assign("arr",$arr);
        $Souarr=D("pay")->where("game_id=$game_id and db_id=$db_id and pay_time>='$STime' and pay_time<='$ETime'")->field("source")->group("source")->select();
        for($i=0;$i<count($Souarr);$i++){
            $source=$Souarr[$i]["source"];
            $stu[$i]["name"]=$source;
            $stu[$i]["num"]=D("pay")->where("pay_time>='$STime' and pay_time<='$ETime' and source='$source' and game_id=$game_id and db_id=$db_id")->count('distinct(user_id)');
            $stu[$i]["money"]=D("pay")->where("pay_time>='$STime' and pay_time<='$ETime'and  source='$source' and game_id=$game_id and db_id=$db_id")->sum("money");
            $stu[$i]["APRU"]=round($stu[$i]["money"]/$stu[$i]["num"],2);
            $stu[$i]["%"]=round($stu[$i]["money"]/$arr["money"],4)*100;
        }
        $this->assign("stu",$stu);
        $this->display();
    }
}