<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26 0026
 * Time: 下午 2:25
 */

namespace Home\Controller;


class CheckpointsController extends BaseController
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
            $ru['role_id']=$game_user_id;
            $this->assign('role_id',$game_user_id);
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
            $this->assign('role_name',$game_user_name);
        }
        //dump($ru);exit;
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('treasure');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $loglog = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();
        //dump($model->getLastSql());exit;
        $this->assign('loglog', $loglog);
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
            $etime = I("get.end_time");
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
            $ru['role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
        }
        //dump($ru);exit;
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('treasure');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Checkpoints&a=index';
         </script>";
            exit;
        }
        $loglog = $model->where($ru)->select();



        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '角色等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'VIP等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '宝箱类型');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '抽奖获得');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '钻石、代券消耗');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '钻石、代券剩余');


        //把数据循环写入excel中
        $i = 1;
        foreach ($loglog as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['role_ChangeLife'].'重'.$value['role_level'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['vip']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['treasure_type']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['treasure_get']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['diamond_use']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['diamond_left']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(150);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);

        //导出代码
        $name='宝藏抽奖';
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