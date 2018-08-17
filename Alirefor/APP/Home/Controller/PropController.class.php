<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 3:11
 */

namespace Home\Controller;


class PropController extends BaseController
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
        $this->assign('Stime', $stime);
        $this->assign('Etime', $etime);


        // 日志点
        // $connection=db($game_id,$db_id);
        //if ($_GET['game_user_id'] or $_GET['game_user_name'] or $_GET['goods_name'] or $_GET['cause']) {
            if ($_GET['game_user_id']) {
                $game_user_id = I('get.game_user_id');
                $ru['role_id'] = $game_user_id;
                $this->assign('role_id', $game_user_id);
            }
            if ($_GET['game_user_name']) {
                $game_user_name = I('get.game_user_name');
                $ru['role_name'] = $game_user_name;
                $this->assign('role_name', $game_user_name);
            }
            if ($_GET['goods_name']) {
                $goods_name = I('get.goods_name');
                $ru['prop'] = $goods_name;
                $this->assign('prop', $goods_name);
            }
            if ($_GET['cause']) {
                $cause = I('get.cause');
                $ru['item_reason'] = array('like', '%' . $cause . '%');
                $this->assign('item_reason', $cause);
            }
            $ru['LogTime'] = array(array('gt', $stime), array('lt', $etime));
            $count = M('backpack')->where($ru)->count();
            $Page = new \Think\Page($count, 25);
            $show = $Page->show();
            $Log = M('backpack')->where($ru)->alias('a')->join('left join prop b on b.ID=a.prop')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            foreach ($Log as $key => $value) {
                $Log[$key]['item_get'] = json_decode($Log[$key]['item_get'], 1);
                $Log[$key]['item_use'] = json_decode($Log[$key]['item_use'], 1);
                $Log[$key]['item_left'] = json_decode($Log[$key]['item_left'], 1);
            }

            $this->assign('page', $show);
            $this->assign("Log", $Log);
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

        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
            $stime = date("Y-m-d H:i:s", strtotime($stime));
            $etime = date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d H:i:s", strtotime('-2hours'));
            $etime = date("Y-m-d H:i:s", time());
        }
        $this->assign('Stime', $stime);
        $this->assign('Etime', $etime);


        // 日志点
        // $connection=db($game_id,$db_id);
        //if ($_GET['game_user_id'] or $_GET['game_user_name'] or $_GET['goods_name'] or $_GET['cause']) {
        if ($_GET['game_user_id']) {
            $game_user_id = I('get.game_user_id');
            $ru['role_id'] = $game_user_id;
            $this->assign('role_id', $game_user_id);
        }
        if ($_GET['game_user_name']) {
            $game_user_name = I('get.game_user_name');
            $ru['role_name'] = $game_user_name;
            $this->assign('role_name', $game_user_name);
        }
        if ($_GET['goods_name']) {
            $goods_name = I('get.goods_name');
            $ru['prop'] = $goods_name;
            $this->assign('prop', $goods_name);
        }
        if ($_GET['cause']) {
            $cause = I('get.cause');
            $ru['item_reason'] = array('like', '%' . $cause . '%');
            $this->assign('item_reason', $cause);
        }
        $ru['LogTime'] = array(array('gt', $stime), array('lt', $etime));
        $count = M('backpack')->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Prop&a=index';
         </script>";
            exit;
        }
        $Log = M('backpack')->where($ru)->alias('a')->join('left join prop b on b.ID=a.prop')->select();
        foreach ($Log as $key => $value) {
            $Log[$key]['item_get'] = json_decode($Log[$key]['item_get'], 1);
            $Log[$key]['item_use'] = json_decode($Log[$key]['item_use'], 1);
            $Log[$key]['item_left'] = json_decode($Log[$key]['item_left'], 1);
        }
        foreach ($Log as $key => $value){
            if($value['item_get']==null){
                $Log[$key]['itemid']=$value['item_use'][0]['goodDBId'];
                $Log[$key]['count']=$value['item_use'][0]['count'];
                $Log[$key]['type']="消耗";
            }else{
                $Log[$key]['itemid']=$value['item_get'][0]['goodDBId'];
                $Log[$key]['count']=$value['item_get'][0]['count'];
                $Log[$key]['type']="获得";
            }
        }
        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '玩家ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '玩家名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '玩家等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '原因');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '物品编号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '变化类型');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '剩余');

        //把数据循环写入excel中
        $i = 1;
        foreach ($Log as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['role_ChangeLife'] . '重' . $value['role_level'] . '级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['item_reason']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['type'] . '了' . $value['count'] . '个');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['item_left'][0]['count']);

        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        //导出代码
        $name = '道具日志' . $stime1;
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