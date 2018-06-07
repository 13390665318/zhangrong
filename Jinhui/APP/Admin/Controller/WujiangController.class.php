<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 6:19
 */

namespace Admin\Controller;


class WujiangController extends BaseController
{
    public function index(){
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
        //var clothes=$("#clothes").val();   区服
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
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");;
        }else{
            $Stime=date("Y-m-01 00:00:00",time());
            $Etime=date("Y-m-d H:i:s",time());
        }
        $Betime=strtotime($Stime);
        $Entime=strtotime($Etime);
        $con["_string"]="time>=$Betime AND time<=$Entime";
        $con["_string"]="type>=11100101 AND type<=14000801";
        $this->assign("Stime",$Stime);
        $this->assign("Etime",$Etime);
        // 日志点
        $connection=db($game_id,$db_id);
        $San_log = M('San_log','',$connection);
        $Log=$San_log->where("time>='$Betime' and time<='$Entime' and type>=11100101 AND type<=14000801")->field("dec")->distinct(true)->select();
        $this->assign("Log",$Log);
        if(isset($_GET["value"])){
            $value=I("get.value");
            if($value==1){
                $con["value"]=array('gt',0);
            }else if($value==-1){
                $con["value"]=array('lt',0);
            }
        }else{
            $value=1;
            $con["value"]=array('lt',0);
        }
        $this->assign("value",$value);
        if(isset($_GET["game_user_id"])){
            if($_GET["game_user_id"]==null){
                $con["uid"]=null;
            }else{
                $con["uid"]=I("get.game_user_id");
            }

        }else{
            $con["uid"]=null;
        }
        if(isset($_GET["game_user_name"])){
            $game_user_name=I("get.game_user_name");
            if($game_user_name!=null){
                $con["uname"]=array('like', "%$game_user_name%");
            }else{
                $con["game_user_name"]=null;
            }
        }else{
            $con["game_user_name"]=null;
        }

        if(isset($_GET["dec"])){
            if($_GET["dec"]=="所有"){
                $con["dec"]=null;
            }else{
                $con["dec"]=I("get.dec");
            }
        }else{
            $con["dec"]=null;
        }
        if(isset($_GET["type"])){
            if($_GET["type"]==null){
                $con["type"]=null;
            }else{
                $con["type"]=I("get.type");
            }
        }else{
            $con["type"]=null;
        }

        $con=array_filter($con);
	//var_dump($con);
        $count      = $San_log->where($con)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=$San_log->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
//echo $San_log->getLastSql();
        $Userbase = M('San_userbase','',$connection);
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $RUS=$Userbase->where("uid=$uid")->find();
            $arr[$i]["uname"]=$RUS["uname"];
            $arr[$i]["level"]=$RUS["level"];
            $arr[$i]["time"]=date("Y-m-d H:i:s",$arr[$i]["time"]);
            $goods_id=$arr[$i]["type"];
            $Gstu=D("goods")->where("itemid=$goods_id")->find();
            $arr[$i]["goods_name"]=$Gstu["itemname"];
        }
        $this->assign("arr",$arr);
        $this->display();

    }
    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
         } else {
            $game_id = 1;
        }
        $db_id=I("get.db_id");
        $Stime=I("get.start_time");
        $Etime=I("get.end_time");;
        $Betime=strtotime($Stime);
        $Entime=strtotime($Etime);
        $con["_string"]="time>=$Betime AND time<=$Entime";
        $con["_string"]="type>=11100101 AND type<=11404001";
        $connection=db($game_id,$db_id);
        $San_log = M('San_log','',$connection);
        if(isset($_GET["value"])){
            $value=I("get.value");
            if($value==1){
                $con["value"]=array('gt',0);
            }else if($value==-1){
                $con["value"]=array('lt',0);
            }
        }else{
            $value=1;
            $con["value"]=array('gt',0);
        }
        if(isset($_GET["game_user_id"])){
            if($_GET["game_user_id"]==null){
                $con["uid"]=null;
            }else{
                $con["uid"]=I("get.game_user_id");
            }
        }else{
            $con["uid"]=null;
        }
        if(isset($_GET["game_user_name"])){
            $game_user_name=I("get.game_user_name");
            if($game_user_name!=null){
                $con["uname"]=array('like', "%$game_user_name%");
            }else{
                $con["game_user_name"]=null;
            }
        }else{
            $con["game_user_name"]=null;
        }

        if(isset($_GET["dec"])){
            if($_GET["dec"]=="所有"){
                $con["dec"]=null;
            }else{
                $con["dec"]=I("get.dec");
            }
        }else{
            $con["dec"]=null;
        }
        if(isset($_GET["type"])){
            if($_GET["type"]==null){
                $con["type"]=null;
            }else{
                $con["type"]=I("get.type");
            }
        }else{
            $con["type"]=null;
        }
        $con=array_filter($con);

        $arr=$San_log->where($con)->select();
        $Userbase = M('San_userbase','',$connection);
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $RUS=$Userbase->where("uid=$uid")->find();
            $arr[$i]["uname"]=$RUS["uname"];
            $arr[$i]["level"]=$RUS["level"];
            $arr[$i]["time"]=date("Y-m-d H:i:s",$arr[$i]["time"]);
            $goods_id=$arr[$i]["type"];
            $Gstu=D("goods")->where("itemid=$goods_id")->find();
            $arr[$i]["goods_name"]=$Gstu["itemname"];
        }


        $objPHPExcel=new \PHPExcel();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','玩家ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','玩家名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','玩家等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','武将产出（消耗）方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','武将产出（消耗）点');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','武将产出（消耗）');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','武将产出（消耗时间）');

        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["uid"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['uname']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['dec']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value['value']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value['goods_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value['time']);



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