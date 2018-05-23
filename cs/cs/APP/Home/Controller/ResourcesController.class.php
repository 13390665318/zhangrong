<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3 0003
 * Time: 上午 9:59
 */

namespace Home\Controller;


class ResourcesController extends BaseController
{
    public function index(){

        $game_id = 2;
        $clostu = D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("get.db_id");
            $_SESSION["db_id"] = $db_id;
        } else {
            $db_id = $clostu[0]["db_id"];
            $_SESSION["db_id"] = $db_id;
        }
        $nowtime = date("Y-m-d H:i:s", time());
        $this->assign("db_id", $db_id);


        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
        } else {
            $stime = date("Y-m-01 00:00:00", time());
            $etime = date("Y-m-d H:i:s", time());
        }

        $this->assign("Stime",$stime);
        $this->assign("Etime",$etime);

        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
        }
        if($_GET['resource']){
            $game_user_name=I('get.resource');
            $ru['currency_reason']=$game_user_name;
        }
        //dump($ru);exit;
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('currency');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $data = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();

        foreach ($data as $k=>$value){
            preg_match_all("/(?:\")(.*)(?:\")/i",$value['currency_left'], $currency);
            $data[$k]['currency']=$currency[1][0];
            preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_get'], $get);
            $data[$k]['get_num']=$get[1][0];
            preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_use'], $use);
            $data[$k]['use_num']=$use[1][0];
            preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_left'], $left);
            $data[$k]['left_num']=$left[1][0];
        }

        $this->assign("arr",$data);
        $this->assign('page',$show);

        $this->display();




    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        $game_id = 1;
        $db_id=I("get.db_id");
        $Stime=I("get.start_time");

        $stime=$Stime."00:00:00";
        $etime=$Stime."23:59:59";
        $Betime=strtotime($stime);
        $Entime=strtotime($etime);
        $con["_string"]="time>=$Betime AND time<=$Entime AND type !=1";


        if(isset($_GET["value"])){
            $values=I("get.value");
            if($values==1){
                $con["value"]=array('gt',0);
            }else if($values==-1){
                $con["value"]=array('lt',0);
            }
        }else{
            $values=1;
            $con["value"]=array('gt',0);
        }

        $connection=db($game_id,$db_id);

        $San_log = M('San_log','',$connection);
        $Userbase = M('San_userbase','',$connection);
        $con=array_filter($con);
        $data=$San_log->where($con)->distinct(true)->field('dec')->select();

        for($i=0;$i<count($data);$i++){
            $con["dec"]=$data[$i]["dec"];
            //var_dump($con);exit;
            $data[$i]["value"]=$San_log->where($con)->sum("value");
            $data[$i]["value"]=abs($data[$i]["value"]);
            $data[$i]["time"]=$Stime;


        }
        $objPHPExcel=new \PHPExcel();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','来源');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','数值');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','时间');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["dec"]);
            if($values==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,"产出");
            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,"消耗");
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['value']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['time']);




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