<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 下午 2:43
 */

namespace Home\Controller;


class LegionController extends  BaseController
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
        $this->assign("db_id",$db_id);
        $connection=db($game_id,$db_id);
        $count      =M('san_userbase as a','',$connection)->join("san_recharge as b on a.uid =b.uid ")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $unioninfo = M('san_unioninfo','',$connection);
        $arr=$unioninfo->field("unionname,fight,camp,level,mastername")->limit($Page->firstRow.','.$Page->listRows)->order("fight desc")->select();


        $this->assign("arr",$arr);
        $this->display();
    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;

        $db_id=I("get.db_id");
        $connection=db($game_id,$db_id);
        $unioninfo = M('san_unioninfo','',$connection);
        $arr=$unioninfo->field("unionname,fight,camp,level,mastername")->order("fight desc")->select();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','序号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','公会名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','创建者');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','公会等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','公会战力');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','国家');


        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$key-1);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['unionname']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value["mastername"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value["fight"]);
            if($value['camp']==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,"蜀");
            }else if($value['camp']==2){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,"魏");
            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,"吴");
            }





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