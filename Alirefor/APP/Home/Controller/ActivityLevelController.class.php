<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 7:04
 */

namespace Home\Controller;


header("Content-type: text/html; charset=utf-8");
class ActivityLevelController extends BaseController
{
    public function index(){
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
        // 开始 结束时间
        $start_time=date('Y-m-d 00:00:00',  strtotime($stime));
        $end_time=date('Y-m-d 23:59:59', strtotime($stime));
        //查询所以等级
        $stu=null;
       // $connection=db2($game_id,$db_id);
        //$sum=0;
        $arr=M('user')->join("sign as b on user.game_user_id =b.game_user_id ")->where("b.start_time>='$start_time' and b.start_time<='$end_time'")->order("user.level desc")->group("user.game_user_id")->field('user.level')->select();

        // dump(M('user')->getLastSql());exit;
       // $sum=D("sign")->where("start_time>='$start_time' and start_time<='$end_time'")->field('game_user_id')->group("game_user_id")->select();

        for($i=100;$i>=1;$i--){
            $level=$i;
            $data[$i]["level"]=$level;
            $stu='" '.$level.'" '.",".$stu;
$data[$i]["num"]=0;
          //  $data[$i]["num"]=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where(" a.level=$level and b.start_time>='$start_time' and b.start_time<='$end_time'")->count();
           for($j=0;$j<count($arr);$j++){
               if($level==$arr[$j]["level"]){
                   $data[$i]["num"]++;
               }
           }

            $data[$i]["nums"]=round($data[$i]["num"]/count($arr),4)*100;
        }

        $data=array_values($data);





        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("stu",$stu);
$data=array_reverse($data);
 $this->assign("data",$data);
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
        // 开始 结束时间
        $start_time=date('Y-m-d 00:00:00',  strtotime($stime));
        $end_time=date('Y-m-d 23:59:59', strtotime($stime));
        //查询所以等级
        $stu=null;
        $connection=db2($game_id,$db_id);
         $arr=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where("b.start_time>='$start_time' and b.start_time<='$end_time'")->order("a.level desc")->group("a.game_user_id")->field('a.level')->select();
       // $sum=D("sign")->where("start_time>='$start_time' and start_time<='$end_time'")->field('game_user_id')->group("game_user_id")->select();

        for($i=1;$i<=80;$i++){
            $level=$i;
            $data[$i]["level"]=$level;
            $stu='" '.$level.'" '.",".$stu;
$data[$i]["num"]=0;
          //  $data[$i]["num"]=M('user as a','',$connection)->join("sign as b on a.game_user_id =b.game_user_id ")->where(" a.level=$level and b.start_time>='$start_time' and b.start_time<='$end_time'")->count();
           for($j=0;$j<count($arr);$j++){
               if($level==$arr[$j]["level"]){
                   $data[$i]["num"]++;
               }
           }

            $data[$i]["nums"]=round($data[$i]["num"]/count($arr),4)*100;
        }
        $data=array_values($data);
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','人数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','占比');

        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
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

    // 流失用户 等级分布

    // 回流用户等级分布
}