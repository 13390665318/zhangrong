<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 下午 8:01
 */

namespace Home\Controller;


class MapchangeController extends BaseController
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
        if($_GET['fubenid']){
            $fubenid=I('get.fubenid');
            $ru['fuben_id']=$fubenid;
            $this->assign('fuben_id',$fubenid);
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('mapchange');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $mapchange = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();
        //dump($model->getLastSql());exit;
        $this->assign('mapchange', $mapchange);
        $this->assign('page',$show);
        $this->display();



    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
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
        //判断获得时间
        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
            $stime=date("Y-m-d H:i:s", strtotime($stime));
            $etime=date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d 00:00:00", time());
            $etime = date("Y-m-d 23:59:59", time());
        }


        $stime1=date("Ymd", strtotime($stime));
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
        if($_GET['fubenid']){
            $fubenid=I('get.fubenid');
            $ru['fuben_id']=$fubenid;
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('mapchange');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Mapchange&a=index';
         </script>";
            exit;
        }
        $mapchange = $model->where($ru)->select();
        foreach ( $mapchange as $key=>$value){
            if($value['fuben_success']==1){
                $mapchange[$key]['fuben_success']='成功';
            }else{
                $mapchange[$key]['fuben_success']='失败';
            }
            if($value['killBoss']==1){
                $mapchange[$key]['killboss']='击杀';
            }else{
                $mapchange[$key]['killboss']='存活';
            }
        }
        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '账号ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '角色等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '角色VIP等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '角色战力值');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '战场类别');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '战场副本ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', '成功/胜利');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', '死亡次数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', '重生次数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', '杀怪数量');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', '击杀其他玩家的数量');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'BOSS情况');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', '挂机时长');

        //把数据循环写入excel中
        $i = 1;
        foreach ($mapchange as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['account_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['role_ChangeLife'].'重'.$value['role_level'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['vip']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['power']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['fuben_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['fuben_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $key, $value['fuben_success']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $key, $value['Die_times']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $key, $value['Reborn_times']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $key, $value['killMonsters']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $key, $value['killPlayer']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $key, $value['killboss']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $key, $value['AutoPlaytime'].'s');
                 }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);

        //导出代码
        $name='战场日志';
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