<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class ZuanController extends BaseController
{
    public function index()
    {

        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);
        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $map['server'] = $db_id;
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
        //利用获取到的用户进行查询判断
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
        //dump($ru);exit;
        $ru['currency_get'] = array('like', array("%MoneyTypes\":40%", "%MoneyTypes\":50%"));
        $ru['currency_use'] = array('like', array("%MoneyTypes\":40%", "%MoneyTypes\":50%"));
        $ru['_logic'] = 'or';
        $map['_complex'] = $ru;
        $map['LogTime'] = array(array('gt', $stime), array('lt', $etime));
        $model = D('currency');
        $count = $model->where($map)->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $data = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($map)->select();
        //遍历转义的json
        foreach ($data as $key => $value) {
            $data[$key]['currency_get'] = json_decode($value['currency_get'], 1);
            $k = $data[$key]['currency_get'][0]['MoneyTypes'];
            $data[$key]['currency_get'][$k] = $data[$key]['currency_get'][0]['count'];
            $data[$key]['currency_use'] = json_decode($value['currency_use'], 1);
            $k = $data[$key]['currency_use'][0]['MoneyTypes'];
            $data[$key]['currency_use'][$k] = $data[$key]['currency_use'][0]['count'];
            $data[$key]['currency_left'] = json_decode($value['currency_left'], 1);
            $k = $data[$key]['currency_left'][0]['MoneyTypes'];
            $data[$key]['currency_left'][$k] = $data[$key]['currency_left'][0]['count'];
        }
        $this->assign('data', $data);
        $this->assign('page', $show);
        $this->display();


    }

    public function index2()
    {
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id'] = $db_id;
        } else {
            $db_id =$clostu[0]['db_id'];
        }
        if ($db_id != 0) {
          /*  $rua['a.db_id'] = $db_id;
            $rub['a.serverID'] = $db_id;
            $string = 'and db_id=' . $db_id;*/
            $where="serverID =$db_id and";
        }


        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $stime = date("Y-m-d 00:00:00", strtotime($stime));
            $etime = date("Y-m-d 23:59:59", strtotime($stime));
        } else {
            $stime = date("Y-m-d 00:00:00", time());
            $etime = date("Y-m-d 23:59:59", time());
        }
        $stime1 = date("Y-m-d", strtotime($stime));
        $this->assign('Stime', $stime1);
        $this->assign('Etime', $etime);
        $rua['LogTime'] = array(array('gt', $stime), array('lt', $etime));
        $rub['LogTime'] = array(array('gt', $stime), array('lt', $etime));

        $model = M('pay');
        $user = M('user')->group('game_user_id')->select(false);
        $table1 = M()->table('pay  a')->field('sum(pay_number/20) as zuangetnum,role_ChangeLife,role_level')->join('(' . $user . ')b on a.game_user_id=b.game_user_id')->where($rua)->group('role_ChangeLife,role_level')->select(false);
        $model = D('Yuanbaouse');
        $table2 = M()->table('Yuanbaouse  a')->field('LogTime,role_ChangeLife,role_level,sum(useYuanbao)as zuanusenum')->where($rub)->group('role_ChangeLife,role_level')->select(false);
        $list = M()->table('(' . $table1 . ')a')->join('(' . $table2 . ')b on a.role_ChangeLife = b.role_ChangeLife')->select(false);

        $sql = "SELECT a.role_ChangeLife,a.level,zuanusenum,zuangetnum from  level a 
        LEFT JOIN(SELECT role_ChangeLife,role_level,sum(useYuanbao) as zuanusenum   FROM Yuanbaouse where $where LogTime>='$stime' and LogTime<='$etime'  GROUP BY role_ChangeLife,role_level)c
        on a.role_ChangeLife=c.role_ChangeLife and a.level=c.role_level 
        Left JOIN(SELECT role_ChangeLife,role_level,sum(Gain_yuanbao) as zuangetnum FROM Yuanbaogain where $where LogTime>='$stime' and LogTime<='$etime'  GROUP BY role_ChangeLife,role_level)b 
        on a.role_ChangeLife=b.role_ChangeLife and a.level=b.role_level ";
        $list = M()->query($sql);
        foreach ($list as $key => $value) {
            $list[$key]['than'] = round($value['zuanusenum'] / $value['zuangetnum'], 4) * 100;
        }
        $this->assign('list', $list);
        $this->display();
    }

    public function index3()
    {
        $clostu = D("db")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
            $_SESSION['db_id'] = $db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $ru['a.db_id'] = $db_id;
        }

        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
            $stime = date("Y-m-d H:i:s", strtotime($stime));
            $etime = date("Y-m-d H:i:s", strtotime($etime));
        } else {
            $stime = date("Y-m-d 00:00:00", time());
            $etime = date("Y-m-d H:i:s", time());
        }

        $ru['pay_time'] = array(array('gt', $stime), array('lt', $etime));
        //$stime1=date("Y-m-d", strtotime($stime));
        $this->assign('Stime', $stime);
        $this->assign('Etime', $etime);
        $model = M('pay');
        $table1 = $model->alias('a')->field('game_user_id,sum(pay_number) as total')->where($ru)->group('game_user_id')->select(false);
        $table2 = M('user')->alias('a')->where($ru)->group('game_user_id')->select(false);
        $list = M()->table('(' . $table1 . ')a')->field('b.game_user_name,b.game_user_id,total')->join('(' . $table2 . ')b on a.game_user_id = b.game_user_id')->order('total desc')->select();
        $this->assign('list', $list);
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
            $_SESSION['db_id'] = $db_id;
        } else {
            $db_id = $_SESSION['db_id'];
        }
        if ($db_id != 0) {
            $map['server'] = $db_id;
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
        //利用获取到的用户进行查询判断
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
        //dump($ru);exit;
        // $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $ru['currency_get'] = array('like', array("%MoneyTypes\":40%", "%MoneyTypes\":50%"));
        $ru['currency_use'] = array('like', array("%MoneyTypes\":40%", "%MoneyTypes\":50%"));
        $ru['_logic'] = 'or';
        $map['_complex'] = $ru;
        $map['LogTime'] = array(array('gt', $stime), array('lt', $etime));
        $model = D('currency');
        $count = $model->where($map)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Zuan&a=index';
         </script>";
            exit;
        }
        $data = $model->where($map)->select();


        //获取json下的指定字符串
        foreach ($data as $key => $value) {
            $data[$key]['currency_get'] = json_decode($value['currency_get'], 1);
            $k = $data[$key]['currency_get'][0]['MoneyTypes'];
            $data[$key]['currency_get'][$k] = $data[$key]['currency_get'][0]['count'];
            $data[$key]['currency_use'] = json_decode($value['currency_use'], 1);
            $k = $data[$key]['currency_use'][0]['MoneyTypes'];
            $data[$key]['currency_use'][$k] = $data[$key]['currency_use'][0]['count'];
            $data[$key]['currency_left'] = json_decode($value['currency_left'], 1);
            $k = $data[$key]['currency_left'][0]['MoneyTypes'];
            $data[$key]['currency_left'][$k] = $data[$key]['currency_left'][0]['count'];
        }


        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '操作时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '途径');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '增加非绑钻石');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '消耗非绑钻石');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '剩余非绑定钻石');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '增加绑定钻石(绑定钻石)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '消耗绑定钻石(绑定钻石)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '剩余绑定钻石(绑定钻石)');



        //把数据循环写入excel中
        $i = 1;
        foreach ($data as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['currency_reason']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['currency_get'][40]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['currency_use'][40]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['currency_left'][40]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['currency_get'][50]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['currency_use'][50]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['currency_left'][50]);
            unset($data[$key]);
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
        $name = '货币流水日志';
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