<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 下午 6:01
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class YuanbaouseController extends BaseController
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

        if($_GET['changelevel']){
            $changelevel=I('get.changelevel');
            $ru['role_ChangeLife']=$changelevel;
            $this->assign('role_ChangeLife',$changelevel);
        }

        if($_GET['viplevel']){
            $viplevel=I('get.viplevel');
            $ru['vip']=$viplevel;
            $this->assign('vip',$viplevel);
        }
        if($_GET['resource']){
            $reason=I('get.resource');
            $ru['reason']=array('like','%'.$reason.'%');
            $this->assign('reason',$reason);
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('Yuanbaouse');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $data = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();

        $this->assign('data',$data);
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
            $ru['serverID'] = $db_id;
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

        if($_GET['changelevel']){
            $changelevel=I('get.changelevel');
            $ru['role_ChangeLife']=$changelevel;
            $this->assign('role_ChangeLife',$changelevel);
        }

        if($_GET['viplevel']){
            $viplevel=I('get.viplevel');
            $ru['vip']=$viplevel;
            $this->assign('vip',$viplevel);
        }
        if($_GET['resource']){
            $reason=I('get.resource');
            $ru['reason']=array('like','%'.$reason.'%');
            $this->assign('reason',$reason);
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model=D('Yuanbaouse');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Yuanbaouse&a=index';
         </script>";
            exit;
        }
        $data = $model->where($ru)->select();




        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '操作时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'VIP等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '消耗原因');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '花费元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '剩余钻石');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '剩余红钻(绑钻)');


        //把数据循环写入excel中
        $i = 1;
        foreach ($data as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['prerole_ChangeLife'].'重'.$value['prerole_ChangeLife'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['vip']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['reason']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['useYuanbao']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['left_yuanbao']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['left_free_yuanbao']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

        //导出代码
        $name='元宝消耗日志';
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