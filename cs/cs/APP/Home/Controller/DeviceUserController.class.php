<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/14 0014
 * Time: 上午 10:22
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class DeviceUserController extends BaseController
{
    public function index(){
    $game_id = 1;
    if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
        $bclothes=I("get.bclothes");
        $eclothes=I("get.eclothes");
        if( $bclothes==0 && $eclothes==0 ){
            $db=D("db")->select();
        }else{
            $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
        }
    }else{
        $db=D("db")->select();
    }
    if(isset($_GET["stime"])&&isset($_GET["etime"])){
        $stime=I("get.stime");
        $etime=I("get.etime");
    }else{
        // 默认 当月
        $stime=date("Y-m-d",strtotime("-6 day"));
        $etime=date("Y-m-d",time());
    }
    $this->assign("stime",$stime);
    $this->assign("etime",$etime);
    //查询渠道
    $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }    $where=null;
    for($i=0;$i<count($rus);$i++){
        $name=$rus[$i]["name"];
        $where = "source = '$name' or "  .$where;
    }

    $con=substr($where,0,strlen($where)-3);
    $num=count_days($stime,$etime);

    if((int)$num>31){
	$num=30;
	}

    $Stime=null;
    for($i=0;$i<=$num;$i++){
        $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
        $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
        $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
        $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
    }
    $user=0;
    for($i=0;$i<count($arr);$i++){
        $data[$i]["time"]=$arr[$i]["time"];
        $Strtime=$arr[$i]["time1"];
        $Endtime=$arr[$i]["time2"];
        $ru['_string']="(".$con.") and register_time>='$Strtime' and register_time<='$Endtime'";
        //var_dump($ru) ;
        $data[$i]["num"]=0;
        for($j=0;$j<count($db);$j++){
            $db_id=$db[$j]["db_id"];
            $connection=db2($game_id,$db_id);
            $sum=M('user','',$connection)->where($ru)->count();
          //  echo M('user','',$connection)->getLastSql();
            $data[$i]["num"]=$data[$i]["num"]+$sum;
            $user=$user+$sum;
        }
    }
    for($i=0;$i<count($data);$i++){
        $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
    }
    //  var_dump($data);
    //  exit;
    $Stime=substr($Stime,0,strlen($Stime)-1);
    $this->assign("data",$data);
    $jsoBj=json_encode($data);
    $this->assign("jsoBj",$jsoBj);
    $this->assign("Stime",$Stime);




    $this->display();
}

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        //查询渠道
        $qudao=D("qudao")->select();
        $this->assign("qudao",$qudao);
        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=D("qudao")->select();
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["name"]=$stu[$i];
                }
            }

        }else{
            //默认全渠道
            $rus=D("qudao")->select();
        }
        $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["name"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="(".$con.") and register_time>='$Strtime' and register_time<='$Endtime'";
            //var_dump($ru) ;
            $data[$i]["num"]=0;
            for($j=0;$j<count($db);$j++){
                $db_id=$db[$j]["db_id"];
                $connection=db2($game_id,$db_id);
                $sum=M('user','',$connection)->where($ru)->count();
                $data[$i]["num"]=$data[$i]["num"]+$sum;
                $user=$user+$sum;
            }
        }
        for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','新增用户(占比)');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num'].'('.$value['num'].'%)');




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
      $game_id = 1;
      if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        //查询渠道
        $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }//var_dump($rus);
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["cid"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);

        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
          }
          $user=0;
        $redis = new \Redis();
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="(".$con.") and register_time>='$Strtime' and register_time<='$Endtime'";
            $data[$i]["num"]=0;
           for($j=0;$j<count($db);$j++){
                $db_id=$db[$j]["db_id"];
                $connection=db2($game_id,$db_id);
               $sum=M('user','',$connection)->where($ru)->count();
            //  echo M('user','',$connection)->getLastSql();exit;
               $data[$i]["num"]=$data[$i]["num"]+$sum;
               $user=$user+$sum;
            }
         }
         for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
         }


        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);




        $this->display();
    }

    public function exl2(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);
        //查询渠道
        $qudao=$this->qudao3;
	$this->assign("qudao",$qudao);

        if(isset($_GET["creator"])){
            $qu=I("get.creator");
            if($qu=='null'){
                $rus=$this->qudao3;
            }else{
                $stu=explode(',',$qu);
                for($i=0;$i<count($stu);$i++){
                    $rus[$i]["cid"]=$stu[$i];
                }
            }

        }else{
        //默认全渠道
            $rus=$this->qudao3;
        }        $where=null;
        for($i=0;$i<count($rus);$i++){
            $name=$rus[$i]["name"];
            $where = "source = '$name' or "  .$where;
        }

        $con=substr($where,0,strlen($where)-3);
        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $user=0;
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time1"];
            $Endtime=$arr[$i]["time2"];
            $ru['_string']="(".$con.") and register_time>='$Strtime' and register_time<='$Endtime'";
            //var_dump($ru) ;
            $data[$i]["num"]=0;
            for($j=0;$j<count($db);$j++){
                $db_id=$db[$j]["db_id"];
                $connection=db2($game_id,$db_id);
                $sum=M('user','',$connection)->where($ru)->count();
                $data[$i]["num"]=$data[$i]["num"]+$sum;
                $user=$user+$sum;
            }
        }
        for($i=0;$i<count($data);$i++){
            $data[$i]["nums"]=round($data[$i]["num"]/$user,4)*100;
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','新增用户(占比)');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num'].'('.$value['num'].'%)');




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