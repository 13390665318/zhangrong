<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class RoleexpController extends BaseController
{
    public function index()
    {
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
        if($_GET['level']){
            $level=I('get.level');
            if($level==1){
                $ru['_string']="laterrole_level>prerole_level or laterrole_ChangeLife> prerole_ChangeLife";
                $check="checked='checked'";
                $this->assign('check',$check);
            }
        }
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('roleexp');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $roleexp = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();
        $this->assign('roleexp', $roleexp);
        $this->assign('page',$show);
        $this->display();


    }

    public function exl()
    {
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        //判断获得时间
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
        //判断玩家ID获得
        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
        }
        if($_GET['level']){
            $level=I('get.level');
            if($level==1){
                $ru['_string']="laterrole_level>prerole_level or laterrole_ChangeLife> prerole_ChangeLife";
            }
        }
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('roleexp');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Roleexp&a=index';
         </script>";
            exit;
        }
        $roleexp = $model->where($ru)->select();
        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '途径');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '获得经验值');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '当前经验值');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '经验获得前等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '经验获得后等级');

        //把数据循环写入excel中
        $i = 1;
        foreach ($roleexp as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['exp_reason']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['role_exp_get']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['laterrole_exp']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['prerole_ChangeLife'].'重'.$value['prerole_ChangeLife'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['laterrole_ChangeLife'].'重'.$value['laterrole_level'].'级');
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        //导出代码
        $name='经验日志';
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