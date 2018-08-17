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
        //if ($_GET['game_user_id'] or $_GET['game_user_name'] or $_GET['method'] or $_GET['resource'] or $_GET['resource2'] ) {
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
        if ($_GET['resource']) {
            $resource = I('get.resource');
            $ru['currency_reason'] = array('like', '%' . $resource . '%');

            $this->assign('currency_reason', $resource);
        }
        if ($_GET['resource2']) {
            $resource2 = I('get.resource2');

            $ru['_string']="currency_get like '%:$resource2,%'  OR  currency_use like '%:$resource2,%'";
            $this->assign('resource2', $resource2);
        }
        if ($_GET['method']) {
            $method = I('get.method');
            $ru['value'] = $method;
            $this->assign('value', $method);
        }

        $ru['LogTime'] = array(array('gt', $stime), array('lt', $etime));
        $model = D('currency');
        $count = $model->where($ru)->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $data = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($ru)->select();
        //dump($model->getLastSql());exit;
        foreach ($data as $key => $value) {
            $data[$key]['currency_get'] = json_decode($value['currency_get'], 1);
            $data[$key]['currency_use'] = json_decode($value['currency_use'], 1);
            $data[$key]['currency_left'] = json_decode($value['currency_left'], 1);
        }


        /*   foreach ($data as $k=>$value){
               preg_match_all("/(?:\")(.*)(?:\")/i",$value['currency_left'], $currency);
               $data[$k]['currency']=$currency[1][0];
               preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_get'], $get);
               $data[$k]['get_num']=$get[1][0];
               preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_use'], $use);
               $data[$k]['use_num']=$use[1][0];
               preg_match_all("/(?:\:)(.*)(?:\})/i",$value['currency_left'], $left);
               $data[$k]['left_num']=$left[1][0];

           }*/
        $this->assign("arr", $data);
        $this->assign('page', $show);
   // }

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
            $stime = date("Y-m-d H:i:s", strtotime('-2hours'));
            $etime = date("Y-m-d H:i:s", time());
        }
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
        if($_GET['method']){
            $method=I('get.method');
            $ru['value']=$method;
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));

        $model = D('currency');
        $data = $model->where($ru)->select();
        $count = $model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Resources&a=index';
         </script>";
            exit;
        }
        foreach ($data as $key => $value) {
            $data[$key]['currency_get'] = json_decode($value['currency_get'], 1);
            $data[$key]['currency_use'] = json_decode($value['currency_use'], 1);
            $data[$key]['currency_left'] = json_decode($value['currency_left'], 1);
        }
        //
        foreach ($data as $key => $value){
          if($value['value']==1){
              $data[$key]['type']="产出";
              $data[$key]['currency']=$value['currency_get'][0]['MoneyTypes'];
              $data[$key]['count']=$value['currency_get'][0]['count'];
          }else{
              $data[$key]['type']="消耗";
              $data[$key]['currency']=$value['currency_left'][0]['MoneyTypes'];
              $data[$key]['count']=$value['currency_left'][0]['count'];
          }
        }

        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '来源');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '资源');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '数值');


        //把数据循环写入excel中
        $i = 1 ;
        foreach ($data as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['currency_reason']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['currency']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['type']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['count']);
    }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);


        //导出代码
        $name='资源日志'.$stime1;
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