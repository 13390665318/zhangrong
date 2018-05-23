<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 上午 10:06
 */

namespace Home\Controller;


class LossLevelController extends BaseController
{
    public function index()
    {
        $game_id = 1;

        // 游戏区/服
        $clostu = D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("get.db_id");
            $_SESSION["db_id"] = $db_id;
        } else {
            if (isset($_SESSION["db_id"])) {
                $db_id = $_SESSION["db_id"];
            } else {
                $db_id = $clostu[0]["db_id"];
                $_SESSION["db_id"] = $db_id;
            }

        }
        $this->assign("db_id", $db_id);
        if (isset($_GET["stime"])) {
            $time = I("get.stime");
        } else {
            $time = date("Y-m-d", time());
        }
        $this->assign("stime", $time);
        $stime = $time . " 00:00:00";
        $etime = $time . " 23:59:59";

        if (isset($_GET["cesa"])) {
            $cesa = I("get.cesa");

            if ($cesa == 24) {
                $day = 1;
            } else if ($cesa == 48) {
                $day = 2;
            } else {
                $day = 3;
            }
        } else {
            $cesa = 24;
            $day = 1;
        }
        $this->assign("cesa", $cesa);
        //$endtime=date('Y-m-d H:i:s', strtotime ("+$day day", strtotime($time)));

        //$con["_string"]="regtime>='$stime' AND regtime<='$etime' and lastupdtime < '$endtime'"; // 注册时间
        // $con=array_filter($con);
        // $connection=db($game_id,$db_id);
        $stu = D('user')->field('game_user_id')->select();


        for ($j = 0; $j < count($stu); $j++) {

            $uid = $stu[$j]["game_user_id"];
            $rus = D("sign")->field('sign.game_user_id,start_time,user.level')->join('left join user  on sign.game_user_id = user.game_user_id')->where("sign.game_user_id=$uid")->order("start_time desc")->find();
            //判断7天没有上线为流失玩家

            if (count_days($rus['start_time'], date('Y-m-d H:i:s', time())) >= $day) {
                $arr[] = $rus;
            }
        }
          
/*        for ($i = 100; $i >= 1; $i--) {

            $level = $i;
            $data[$i]["level"] = $level;

            $stu = '" ' . $level . '" ' . "," . $stu;

            $data[$i]["num"] = 0;
            //  $data[$i]["num"]=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where(" a.level=$level and b.start_time>='$start_time' and b.start_time<='$end_time'")->count();
            for ($j = 0; $j < count($arr); $j++) {
                if ($level == $arr[$j]["level"]) {
                    $data[$i]["num"]++;
                }
            }

            $data[$i]["nums"] = round($data[$i]["num"] / count($arr), 4) * 100;
        }
        $this->assign("stu", $stu);

        $data = array_values($data);
        for ($i = 200; $i >= 101; $i--) {
            $level = $i;
            $data2[$i]["level"] = $level;
            $stu2 = '" ' . $level . '" ' . "," . $stu2;

            $data2[$i]["num"] = 0;
            for ($j = 0; $j < count($arr); $j++) {
                if ($level == $arr[$j]["level"]) {
                    $data2[$i]["num"]++;
                }
            }

            $data2[$i]["nums"] = round($data[$i]["num"] / count($arr), 4) * 100;
        }
        $data2 = array_values($data2);
        for ($i = 300; $i >= 201; $i--) {
            $level = $i;
            $data3[$i]["level"] = $level;
            $stu3 = '" ' . $level . '" ' . "," . $stu3;

            $data3[$i]["num"] = 0;
            for ($j = 0; $j < count($arr); $j++) {
                if ($level == $arr[$j]["level"]) {
                    $data3[$i]["num"]++;
                }
            }

            $data3[$i]["nums"] = round($data[$i]["num"] / count($arr), 4) * 100;
        }
        $data3 = array_values($data3);*/

        for ($i = 100; $i >=1; $i--) {
            $level = $i;
            $stu1 = $level.','.$stu1;
        }

        $this->assign("stu", $stu1);
        for($i=1000;$i>0;$i--){
            $level=$i;
            $data[$i]["level"]=$level;
            $data[$i]["num"]=0;
            for ($j=0;$j<count($arr);$j++){
                if($level==$arr[$j]["level"]){
                    $data[$i]["num"]++;
                }
            }
            $data[$i]["nums"]=round($data[$i]["num"]/count($arr),4)*100;
            $stu = '" ' . $level . '" ' . "," . $stu;
            if($i>900&&$i<=1000){
                $data9[$i]["level"]=$level;
                $data9[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data9[$i]["num"]++;
                    }
                }
            }else if($i>800&&$i<=900){
                $data8[$i]["level"]=$level;
                $data8[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data8[$i]["num"]++;
                    }
                }
            }else if($i>700&&$i<=800){
                $data7[$i]["level"]=$level;
                $data7[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data7[$i]["num"]++;
                    }
                }
            }else if($i>600&&$i<=700){
                $data6[$i]["level"]=$level;
                $data6[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data6[$i]["num"]++;
                    }
                }
            }else if($i>500&&$i<=600){
                $data5[$i]["level"]=$level;
                $data5[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data5[$i]["num"]++;
                    }
                }
            }else if($i>400&&$i<=500){
                $data4[$i]["level"]=$level;
                $data4[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data4[$i]["num"]++;
                    }
                }
            }else if($i>300&&$i<=400){
                $data3[$i]["level"]=$level;
                $data3[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data3[$i]["num"]++;
                    }
                }
            }else if($i>200&&$i<=300){
                $data2[$i]["level"]=$level;
                $data2[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data2[$i]["num"]++;
                    }
                }
            }else if($i>100&&$i<=200){
                $data1[$i]["level"]=$level;
                $data1[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data1[$i]["num"]++;
                    }
                }
            }else if($i>0&&$i<=100){
                $data0[$i]["level"]=$level;
                $data0[$i]["num"]=0;
                for($j=0;$j<count($arr);$j++){
                    if($level==$arr[$j]["level"]){
                        $data0[$i]["num"]++;
                    }
                }
            }
        }




        //$Userbase = M('San_userbase','',$connection);
        /*$loss=$Userbase->where($con)->count(); // 流失总人数
        $stu = $Userbase->where($con)->distinct(true)->field('level')->order("level desc")->select();*/

        //  for($i=0;$i<count($stu);$i++){
        //     $level=$stu[$i]["level"];
        //     $ru='" '.$level.'" '.",".$ru;
        //     $arr[$i]["level"]=$stu[$i]["level"];

        //    $con["level"]=$stu[$i]["level"];
        //等级流失人数
        //$arr[$i]["loss"]=$Userbase->where($con)->count();
        // 流失率
        //$arr[$i]["loss_num"]=round($arr[$i]["loss"]/$loss,4)*100;
        //  }

        $data0 = array_values($data0);
        $data1 = array_values($data1);
        $data2 = array_values($data2);
        $data3 = array_values($data3);
        $data4 = array_values($data4);
        $data5 = array_values($data5);
        $data6 = array_values($data6);
        $data7 = array_values($data7);
        $data8 = array_values($data8);
        $data9 = array_values($data9);
        $jsoBj0 = json_encode($data0);
        $jsoBj1 = json_encode($data1);
        $jsoBj2 = json_encode($data2);
        $jsoBj3 = json_encode($data3);
        $jsoBj4 = json_encode($data4);
        $jsoBj5 = json_encode($data5);
        $jsoBj6 = json_encode($data6);
        $jsoBj7 = json_encode($data7);
        $jsoBj8 = json_encode($data8);
        $jsoBj9 = json_encode($data9);

        $this->assign("jsoBj0", $jsoBj0);
        $this->assign("jsoBj1", $jsoBj1);
        $this->assign("jsoBj2", $jsoBj2);
        $this->assign("jsoBj3", $jsoBj3);
        $this->assign("jsoBj4", $jsoBj4);
        $this->assign("jsoBj5", $jsoBj5);
        $this->assign("jsoBj6", $jsoBj6);
        $this->assign("jsoBj7", $jsoBj7);
        $this->assign("jsoBj8", $jsoBj8);
        $this->assign("jsoBj9", $jsoBj9);

        $data = array_reverse($data);

        $this->assign("arr", $data);
        $this->assign("arr2", $data2);
        $this->display();
    }

    public function exl()
    {
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel = new \PHPExcel();
        $game_id = 1;
        $db_id = I("get.db_id");
        $time = I("get.stime");


        $stime = $time . " 00:00:00";
        $etime = $time . " 23:59:59";
        if (isset($_GET["cesa"])) {
            $cesa = I("get.cesa");
            if ($cesa == 24) {
                $day = 1;
            } else if ($cesa == 48) {
                $day = 2;
            } else {
                $day = 3;
            }
        }

        $endtime = date('Y-m-d H:i:s', strtotime("+$day day", strtotime($time)));

        $con["_string"] = "regtime>='$stime' AND regtime<='$etime' and lastupdtime < '$endtime'"; // 注册时间
        $con = array_filter($con);
        $connection = db($game_id, $db_id);
        $Userbase = M('San_userbase', '', $connection);
        $loss = $Userbase->where($con)->count(); // 流失总人数
        $stu = $Userbase->where($con)->distinct(true)->field('level')->order("level desc")->select();
        $ru = null;
        for ($i = 0; $i < count($stu); $i++) {
            $level = $stu[$i]["level"];
            $ru = '" ' . $level . '" ' . "," . $ru;
            $arr[$i]["level"] = $stu[$i]["level"];

            $con["level"] = $stu[$i]["level"];
            //等级流失人数
            $arr[$i]["loss"] = $Userbase->where($con)->count();
            // 流失率
            $arr[$i]["loss_num"] = round($arr[$i]["loss"] / $loss, 4) * 100;
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '人数(占比)');


        //把数据循环写入excel中
        $i = 1;
        foreach ($arr as $key => $value) {
            $key += 2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["level"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['loss'] . '(' . $value['loss_num'] . '%)');


        }
        //导出代码
        $name = time();
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


    }
}