<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9 0009
 * Time: 上午 10:57
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class BackLevelController extends BaseController
{
    public function  index(){
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
        if(isset($_GET["stime"])){
            $stime=I("get.stime");

        }else{
            // 默认 当天
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $connection=db2($game_id,$db_id);
        //  注册 开始 结束时间
        $start_time=date('Y-m-d 00:00:00',  strtotime($stime));
        //  echo $start_time;
        $end_time=date('Y-m-d 23:59:59', strtotime($stime));
        // 登录时间  一个礼拜之后有登录
        $begin_time=date('Y-m-d 00:00:00', strtotime ("+7day", strtotime($stime)));
//查询是有登录的玩家
        $connection=db($game_id,$db_id);
        $Userbase = M('sign');
        $arr = $Userbase->where("regtime>='$start_time' and regtime<='$end_time' and lastupdtime >= '$begin_time'")->order("uid desc")->select();
//判断是否是回流玩家
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $rus=D("sign")->where("game_user_id=$uid")->order("sign_id desc")->limit(0,2)->select();
            if(count_days($rus[0]["start_time"],$rus[1]["start_time"])>=7){
                //回流用户
                $data[$i]["level"]=$arr[$i]["level"];
                $data[$i]["uid"]=$arr[$i]["uid"];
            }
        }
// 去重 超找level
        $res = array();
        foreach ($data as $value) {
            //查看有没有重复项

            if(isset($res[$value["level"]])){
                unset($value["level"]);
            }
            else{
                $res[$value["level"]] = $value;
            }
        }
        $res= array_merge($res);
        //  var_dump($res);
        $stu=null;
        for($i=0;$i<count($res);$i++){
            $level=$res[$i]["level"];
            $stu='" '.$level.'" '.",".$stu;
            $result[$i]["level"]=$res[$i]["level"];
            $result[$i]["num"]=1;
            for($j=0;$j<count($data);$j++){
                if($res[$i]["level"]==$data[$j]["level"]){
                    $result[$i]["num"]= $result[$i]["num"]+1;
                }
            }
            $result[$i]["nums"]=round($result[$i]["num"]/count($data),4)*100;
        }
        //    var_dump($result);
        $this->assign("data",$result);
        $jsoBj=json_encode($result);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("stu",$stu);
        $this->display();
    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
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
        if(isset($_GET["stime"])){
            $stime=I("get.stime");

        }else{
            // 默认 当天
            $stime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $connection=db2($game_id,$db_id);
        //  注册 开始 结束时间
        $start_time=date('Y-m-d 00:00:00',  strtotime($stime));
        //  echo $start_time;
        $end_time=date('Y-m-d 23:59:59', strtotime($stime));
        // 登录时间  一个礼拜之后有登录
        $begin_time=date('Y-m-d 00:00:00', strtotime ("+7day", strtotime($stime)));
//查询是有登录的玩家
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);
        $arr = $Userbase->where("regtime>='$start_time' and regtime<='$end_time' and lastupdtime >= '$begin_time'")->order("uid desc")->select();
//判断是否是回流玩家
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $rus=D("sign")->where("game_user_id=$uid")->order("sign_id desc")->limit(0,2)->select();
            if(count_days($rus[0]["start_time"],$rus[1]["start_time"])>=7){
                //回流用户
                $data[$i]["level"]=$arr[$i]["level"];
                $data[$i]["uid"]=$arr[$i]["uid"];
            }
        }
// 去重 超找level
        $res = array();
        foreach ($data as $value) {
            //查看有没有重复项

            if(isset($res[$value["level"]])){
                unset($value["level"]);
            }
            else{
                $res[$value["level"]] = $value;
            }
        }
        $res= array_merge($res);
        //  var_dump($res);
        $stu=null;
        for($i=0;$i<count($res);$i++){
            $level=$res[$i]["level"];
            $stu='" '.$level.'" '.",".$stu;
            $result[$i]["level"]=$res[$i]["level"];
            $result[$i]["num"]=1;
            for($j=0;$j<count($data);$j++){
                if($res[$i]["level"]==$data[$j]["level"]){
                    $result[$i]["num"]= $result[$i]["num"]+1;
                }
            }
            $result[$i]["nums"]=round($result[$i]["num"]/count($data),4)*100;
        }
        $result=array_values($result);
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','占比');

        //把数据循环写入excel中
        $i=1;
        foreach($result as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["level"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['nums']);



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