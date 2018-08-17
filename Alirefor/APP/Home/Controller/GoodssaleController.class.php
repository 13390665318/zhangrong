<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class GoodssaleController extends BaseController
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
            /*  $rua['a.db_id'] = $db_id;
              $rub['a.serverID'] = $db_id;
              $string = 'and db_id=' . $db_id;*/
            $where="serverID =$db_id and";
        }
        $nowtime = date("Y-m-d H:i:s", time());
//判定是否获得时间
        if(isset($_GET["start_time"])&&isset($_GET["end_time"])){
            $stime=I("get.start_time");
            $etime=I("get.end_time");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }



        $this->assign('Stime',$stime);
        $this->assign('Etime',$etime);

        $num=count_days($stime,$etime);
        for($i=0;$i<=$num;$i++){
            $arr[$i]['stime']=date('Y-m-d 00:00:00', strtotime ("-$i day", strtotime($etime)));
            $arr[$i]["etime"]=date('Y-m-d 23:59:59', strtotime ("-$i day", strtotime($etime)));
            $time[$i]=date("Y-m-d", strtotime($arr[$i]['stime']));
        }
        for($i=0;$i<count($arr);$i++) {
            $stime=$arr[$i]['stime'];
            $etime=$arr[$i]['etime'];
            $sql="select * from langoods a left  join  
          (select LogTime,count(*)as num,money_reason from usemoney where $where LogTime>='$stime' and LogTime<='$etime' group  by money_reason  )b on a.hgoods=b.money_reason ";
            $goodssale[$i]=M()->query($sql);
        }

        foreach ($goodssale as $key =>$value){
            foreach ($value as $k=>$v){
                $goodssale[$key][$k]['LogTime']=$time[$key];
            }
        }
        $cgoods=M('langoods')->field('cgoods')->select();
        $this->assign('time',$time);
        $this->assign('cgoods',$cgoods);
        $this->assign('goodssale', $goodssale);
        $this->display();
    }

    public function exl()
    {
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
     //判断获得时间
        $clostu = D("db")->order("db_id desc")->select();
        if (isset($_GET["db_id"])) {
            $db_id = I("db_id");
        } else {
            $db_id = $clostu[0]["db_id"];
        }
        if ($db_id != 0) {
            $ru['serverID'] = $db_id;
        }
        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $stime=date("Y-m-d 00:00:00", strtotime($stime));
            $etime=date("Y-m-d 23:59:59", strtotime($stime));
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

        $model=D('loginlog');

        $loglog = $model->where($ru)->select();
        foreach($loglog as $key=>$value){
            $loglog[$key]['player_message']=json_decode($value['player_message'],1);
        }

        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '登录时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'IP');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '创建时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '剩余金币');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '剩余免费金币');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '剩余元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '剩余免费元宝');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', '角色名');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', '上次登出时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', '玩家信息');

        //把数据循环写入excel中
        $i = 1;

        foreach ($loglog as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['ip']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['create_time']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['role_ChangeLife'].'重'.$value['role_level'].'级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['left_Money']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['left_Bind_Money']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['left_yuanbao']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['left_free_yuanbao']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $key, $value['last_logout_time']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $key, '角色好友数:'. $value['player_message']['FriendCount'].'经验值:'.$value['player_message']['CurExp'].'战力:'.$value['player_message']['CombatForce'].'在线时长:'.$value['player_message']['TotalOnlineSecs'].'PK值:'.$value['player_message']['PKPoint'].'帮贡:'.$value['player_message']['BangGong']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        //导出代码
        $name='登录日志'.$stime1;
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