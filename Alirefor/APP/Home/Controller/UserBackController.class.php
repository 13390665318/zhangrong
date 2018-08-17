<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 5:04
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class UserBackController extends BaseController
{
    public function index(){


        $clostu=D("db")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        // 图标 默认 最新服
        if($db_id!=0){
            $ru['db_id']=$db_id;
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
        $num=count_days($stime,$etime);
       $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }

        //$connection=db($game_id,$db_id);
        //$Userbase = M('San_userbase','',$connection);
        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $start_time=$arr[$i]["time1"];
            $end_time=$arr[$i]["time2"];
           $begin_time=date('Y-m-d 00:00:00', strtotime ("+7day", strtotime( $arr[$i]["time"])));
            $ru['_string']="start_time>='$start_time' and start_time<='$end_time'";
           $stu = D('sign')->field('user_id')->where($ru)->select();
           $stu= array_unique($stu, SORT_REGULAR);
           $stu= array_values($stu);
           /* foreach ($stu as $k=>$value){
                $user[$k]=$value['game_user_id'];
            }
            $user=array_unique($user);*/


            //判断是否是回流玩家
          $data[$i]["num"]=0;
            for($j=0;$j<count($stu);$j++){
                $uid=$stu[$j]["user_id"];
                $rus=D("sign")->where("user_id=$uid")->order("start_time desc")->limit(2)->select();
                if(count($rus)==2){
                    if (count_days($rus[0]["start_time"], $rus[1]["start_time"]) >= 7) {
                        //回流用户e
                        $data[$i]["num"] = $data[$i]["num"] + 1;
                    }
                }
            }


        }

        //var_dump($data);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);
      $this->display();
    }//

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        $db_id=I("get.db_id");
        $stime=I("get.stime");
        $etime=I("get.etime");
        $num=count_days($stime,$etime);
        $Stime=null;
        for($i=0;$i<=$num;$i++){

            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time1"]=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["time2"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
        }
        $connection=db($game_id,$db_id);
        $Userbase = M('San_userbase','',$connection);

        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $start_time=$arr[$i]["time1"];
            $end_time=$arr[$i]["time2"];
            $begin_time=date('Y-m-d 00:00:00', strtotime ("+7day", strtotime( $arr[$i]["time"])));
            $stu = $Userbase->where("regtime>='$start_time' and regtime<='$end_time' and lastupdtime >= '$begin_time'")->order("uid desc")->select();
            //判断是否是回流玩家
            $data[$i]["num"]=0;
            for($j=0;$j<count($stu);$j++){
                $uid=$stu[$j]["uid"];
                $rus=D("sign")->where("game_user_id=$uid")->order("sign_id desc")->limit(0,2)->select();
                if(count_days($rus[0]["start_time"],$rus[1]["start_time"])>=7){
                    //回流用户
                    $data[$i]["num"]=$data[$i]["num"]+1;
                }
            }

        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','回流玩家');



        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value["num"]);





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