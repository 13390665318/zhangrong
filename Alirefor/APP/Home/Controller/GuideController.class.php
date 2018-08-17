<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3 0003
 * Time: 下午 3:13
 */

namespace Home\Controller;

// 新手引导流失
class GuideController extends BaseController
{
    public function index(){

        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $ru['server'] = $db_id;
        }


        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
            $stime = date("Y-m-d H:i:s", strtotime($stime));
            $etime = date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d H:i:s", strtotime('-2hours'));
            $etime = date("Y-m-d H:i:s", time());
        }


        //$stime1=date("Y-m-d", strtotime($stime));
        $this->assign('Stime', $stime);
        $this->assign('Etime', $etime);


        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['target_role_id']=$game_user_id;
            $this->assign('role_id',$game_user_id);
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['target_role_name']=$game_user_name;
            $this->assign('role_name',$game_user_name);
        }
        //dump($ru);exit;
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('guide');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $guide = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();
        //dump($model->getLastSql());exit;
        $this->assign('guide', $guide);
        $this->assign('page',$show);
        $this->display();


    }

    public function exl(){

        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        //判断获得时间
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
        } else {
            $db_id = $clostu[0]["db_id"];
        }
        if ($db_id != 0) {
            $ru['server'] = $db_id;
        }

        $this->assign("db_id", $db_id);
        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.start_time");
            $stime=date("Y-m-d H:i:s", strtotime($stime));
            $etime=date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d 00:00:00", time());
            $etime = date("Y-m-d 23:59:59", time());
        }


        $stime1=date("Y-m-d", strtotime($stime));
        $this->assign('Stime',$stime1);
        $this->assign('Etime',$etime);


        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['target_role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['target_role_name']=$game_user_name;
        }
        //dump($ru);exit;
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('guide');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Guide&a=index';
         </script>";
            exit;
        }
        $guide = $model->where($ru)->select();
        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '帮派ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '帮派名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '操作类型');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '操作人');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '操作人名字');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '目标角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '目标角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '目标角色等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '角色VIP等级');


        //把数据循环写入excel中
        $i = 1;
        foreach ($guide as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['union_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['union_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['union_membe_reasom']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['target_role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['target_role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['role_ChangeLife'].'重'.$value['role_level'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $key, $value['vip']);

        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('G')->setWidth(12);

        //导出代码
        $name='帮派人员变动日志'.$stime1;
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