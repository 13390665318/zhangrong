<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class EquiplogController extends BaseController
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

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('equiplog');
        $count=$model->where($ru)->count();
        $Page=new \Think\Page($count,20);
        $show=$Page->show();
        $equiplog = $model->limit($Page->firstRow.','.$Page->listRows)->where($ru)->select();
        foreach ($equiplog as $key =>$value){
            $equiplog[$key]['equipInfo']=json_decode($value['equipInfo'],1);
            $equiplog[$key]['consumeInfo']=json_decode($value['consumeInfo'],1);
            $equiplog[$key]['leftInfo']=json_decode($value['leftInfo'],1);
        }
       //dump($equiplog);exit;

        $this->assign('equiplog', $equiplog);
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


        if($_GET['game_user_id']){
            $game_user_id=I('get.game_user_id');
            $ru['role_id']=$game_user_id;
        }

        if($_GET['game_user_name']){
            $game_user_name=I('get.game_user_name');
            $ru['role_name']=$game_user_name;
        }

        $ru['LogTime']=array(array('gt',$stime),array('lt',$etime));
        $model=D('equiplog');
        $count =$model->where($ru)->count();
        if ($count > 20000) {
            echo "<script>alert('最大导出不能超过两万条')
            window.location.href='index.php?m=Home&c=Equiplog&a=index';
         </script>";
            exit;
        }
        $equiplog = $model->where($ru)->select();
        foreach ($equiplog as $key =>$value){
            $equiplog[$key]['equipInfo']=json_decode($value['equipInfo'],1);
            $equiplog[$key]['consumeInfo']=json_decode($value['consumeInfo'],1);
            $equiplog[$key]['leftInfo']=json_decode($value['leftInfo'],1);
        }

        foreach ($equiplog as $key =>$value){
            if($value['equip_operation']=='装备强化'){
               //$equiplog[$key]['goodDBId']=$value['equipInfo']['goodDBId'];
               $equiplog[$key]['Forge_level_before']=$value['equipInfo']['Forge_level_before'];
               $equiplog[$key]['Forge_level_after']=$value['equipInfo']['Forge_level_after'];
               $equiplog[$key]['use']='物品ID:'.$value['consumeInfo'][0]['goodDBId'].'金钱类型:'.$value['consumeInfo'][1]['MoneyTypes'].'数量:'.$value['consumeInfo'][1]['count'];
               $equiplog[$key]['left']='物品ID:'.$value['leftInfo'][0]['goodDBId'].'金钱类型:'.$value['consumeInfo'][1]['MoneyTypes'].'数量:'.$value['leftInfo'][1]['count'];
            }elseif ($value['equip_operation']=='装备进阶'){
                $equiplog[$key]['Forge_level_before']=$value['equipInfo']['EquipA']['goodDBId'].'  '.$value['equipInfo']['EquipB']['goodDBId'].'  '.$value['equipInfo']['EquipC']['goodDBId'];
                $equiplog[$key]['Forge_level_after']=$value['equipInfo']['newEquip']['goodDBId'];
                $equiplog[$key]['use']=$value['consumeInfo'][0]['goodDBId'].'  '.$value['consumeInfo'][1]['goodDBId'].'  '.$value['consumeInfo'][2]['goodDBId'].'金钱类型:'.$value['consumeInfo'][3]['MoneyTypes'].'数量:'.$value['consumeInfo'][3]['count'];
                $equiplog[$key]['left']=$value['leftInfo'][3]['goodDBId'].'金钱类型:'.$value['leftInfo'][4]['MoneyTypes'].'数量:'.$value['leftInfo'][4]['count'];
            }elseif ($value['equip_operation']=='装备自动全部传承'){
                $equiplog[$key]['Forge_level_before']='leftID:'.$value['equipInfo']['leftEquip']['goodDBId'].
                    '  leftlevel_befor:'.$value['equipInfo']['leftEquip']['Forge_level_before'].
                    '  leftPropLev_before:'.$value['equipInfo']['leftEquip']['AppendPropLev_before'].
                    '  rightID:'.$value['equipInfo']['rightEquip']['goodDBId'].
                    '  rightlevel_befor:'.$value['equipInfo']['rightEquip']['Forge_level_before'].
                    '  rightPropLev_before:'.$value['equipInfo']['rightEquip']['AppendPropLev_before'];
                $equiplog[$key]['Forge_level_after']='leftID:'.$value['equipInfo']['leftEquip']['goodDBId'].
                    '  leftlevel_befor:'.$value['equipInfo']['leftEquip']['Forge_level_after'].
                    '  leftPropLev_before:'.$value['equipInfo']['leftEquip']['AppendPropLev_after'].
                    '  rightID:'.$value['equipInfo']['rightEquip']['goodDBId'].
                    '  rightlevel_befor:'.$value['equipInfo']['rightEquip']['Forge_level_after'].
                    '  rightPropLev_before:'.$value['equipInfo']['rightEquip']['AppendPropLev_after'];
                 $equiplog[$key]['use']='金钱类型:'.$value['consumeInfo'][0]['MoneyTypes'].' 数量:'.$value['consumeInfo'][0]['count'];
                 $equiplog[$key]['left']='金钱类型:'.$value['leftInfo'][0]['MoneyTypes'].' 数量:'.$value['leftInfo'][0]['count'];
            }elseif($value['equip_operation']=='装备炼化'){
                $equiplog[$key]['Forge_level_before']='ID:'.$value['equipInfo']['BeforeAttribute'][0]['AttributeId'].'  value:'.$value['equipInfo']['BeforeAttribute'][0]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['BeforeAttribute'][1]['AttributeId'].'  value:'.$value['equipInfo']['BeforeAttribute'][1]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['BeforeAttribute'][2]['AttributeId'].'  value:'.$value['equipInfo']['BeforeAttribute'][2]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['BeforeAttribute'][3]['AttributeId'].'  value:'.$value['equipInfo']['BeforeAttribute'][3]['AttributeValue'];
                $equiplog[$key]['Forge_level_after']='ID:'.$value['equipInfo']['AfterAttribute'][0]['AttributeId'].'  value:'.$value['equipInfo']['AfterAttribute'][0]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['AfterAttribute'][1]['AttributeId'].'  value:'.$value['equipInfo']['AfterAttribute'][1]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['AfterAttribute'][2]['AttributeId'].'  value:'.$value['equipInfo']['AfterAttribute'][2]['AttributeValue'].
                    '  ID:'.$value['equipInfo']['AfterAttribute'][3]['AttributeId'].'  value:'.$value['equipInfo']['AfterAttribute'][3]['AttributeValue'];
                $equiplog[$key]['use']=$value['consumeInfo'][0]['goodDBId'].':'.$value['consumeInfo'][0]['count'].' 金钱类型:'.$value['consumeInfo'][1]['MoneyTypes'].'数量:'.$value['consumeInfo'][1]['count'];
                $equiplog[$key]['left']=$value['leftInfo'][0]['goodDBId'].':'.$value['leftInfo'][0]['count'].' 金钱类型:'.$value['leftInfo'][1]['MoneyTypes'].'数量:'.$value['leftInfo'][1]['count'];
            }elseif ($value['equip_operation']=='装备附加'){
                $equiplog[$key]['Forge_level_before']=$value['equipInfo']['AppendPropLev_before'];
                $equiplog[$key]['Forge_level_after']=$value['equipInfo']['AppendPropLev_after'];
                $equiplog[$key]['use']=$value['consumeInfo'][0]['goodDBId'].':'.$value['consumeInfo'][0]['count'].' 金钱类型:'.$value['consumeInfo'][1]['MoneyTypes'].'数量:'.$value['consumeInfo'][1]['count'];
                $equiplog[$key]['left']=$value['leftInfo'][0]['goodDBId'].':'.$value['leftInfo'][0]['count'].' 金钱类型:'.$value['leftInfo'][1]['MoneyTypes'].'数量:'.$value['leftInfo'][1]['count'];
            }
        }


        $objPHPExcel = new \PHPExcel();

        //设置excel列名

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '角色ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '角色名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '装备ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '装备类型');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '操作类型');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '操作前属性');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', '操作后属性');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '物品消耗');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', '物品剩余');


        //把数据循环写入excel中
        $i = 1;
        foreach ($equiplog as $key => $value) {
            $key += 2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["LogTime"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value['role_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $key, $value['role_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $key, $value['equip_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $key, $value['equip_type']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $key, $value['equip_operation']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $key, $value['Forge_level_before']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $key, $value['Forge_level_after']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $key, $value['use']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $key, $value['left']);

        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

        //导出代码
        $name='装备操作日志';
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