<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class ShopController extends BaseController
{
    public function index()
    {
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id']=$db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        // 图标 默认 最新服
        if ($db_id != 0) {
            $ru['serverID'] = $db_id;
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

        $model=D('Itembuy');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $shop = $model->alias('a')->join('left join prop b on b.ID=a.prop')->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();

        foreach ($shop as  $key => $value){
            $shop[$key]['GainItems']=json_decode($value['GainItems'],1);
            $shop[$key]['currency_use']=json_decode($value['currency_use'],1);
            $shop[$key]['currency_left']=json_decode($value['currency_left'],1);
        }
        $this->assign('shop', $shop);
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
        // 图标 默认 最新服
        if ($db_id != 0) {
            $ru['serverID'] = $db_id;
        }
        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
            $stime=date("Y-m-d H:i:s", strtotime($stime));
            $etime=date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d 00:00:00", time());
            $etime = date("Y-m-d 23:59:59", time());
        }
        //判断玩家ID获得
        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
        }
        $stime1=date("Ymd", strtotime($stime));
        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('Itembuy');
        $count = $model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Shop&a=index';
         </script>";
            exit;
        }
        $shop = $model->alias('a')->join('left join prop b on b.ID=a.prop')->where($ru)->select();
        foreach ($shop as  $key => $value){
            $shop[$key]['GainItems']=json_decode($value['GainItems'],1);
            $shop[$key]['currency_use']=json_decode($value['currency_use'],1);
            $shop[$key]['currency_left']=json_decode($value['currency_left'],1);
        }




        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '购买时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '账号名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '账号ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '角色等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '角色VIP等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '购买物品信息');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '花费财产信息');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', '剩余财产信息');


        //把数据循环写入excel中
        $i = 1;
        foreach ($shop as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["buy_time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['account_Name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['account_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['role_ChangeLife'].'重'.$value['role_level'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['vip']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['name'].':'.$value['GainItems']['count']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['currency_use'][0]['MoneyTypes'].':'.$value['currency_use'][0]['count']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $key, $value['currency_left'][0]['MoneyTypes'].':'.$value['currency_left'][0]['count']);
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

        //导出代码
        $name='商店购买'.$stime1;
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