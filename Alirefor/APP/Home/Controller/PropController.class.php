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
    public function index(){

        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        // 游戏区/服
        $clostu=D("db")->where("game_id=$game_id")->order("db_id asc")->select();
        $this->assign("clostu",$clostu);
        // 默认 最新服
        //var clothes=$("#clothes").val();   区服
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $_SESSION["db_id"]=$db_id;
        }else{
            if(isset($_SESSION["db_id"])){
                
                $db_id= $_SESSION["db_id"];
            }else{
                $db_id=$clostu[0]["db_id"];
                $_SESSION["db_id"]=$db_id;
            }

        }
        $this->assign("db_id",$db_id);
        if(isset($_GET["start_time"]) && isset($_GET["end_time"])){
            $Stime=I("get.start_time");
            $Etime=I("get.end_time");
        }else{
            $Stime=date("Y-m-d 00:00:00",time());
            $Etime=date("Y-m-d H:i:s",time());
        }
        $Betime=strtotime($Stime);
        $Entime=strtotime($Etime);
        $con["_string"]="time>=$Betime AND time<=$Entime and type!=1";
        $this->assign("Stime",$Stime);
        $this->assign("Etime",$Etime);
 if($_GET){

        // 日志点
        $connection=db($game_id,$db_id);
        $San_log = M('San_log','',$connection);
        $Log=$San_log->where("time>='$Betime' and time<='$Entime'")->field("dec")->distinct(true)->select();
        $this->assign("Log",$Log);
        //var money_type="{$money_type}"   类别
        if(isset($_GET["value"])){
            $value=I("get.value");
            if($value==1){
                $con["value"]=array('gt',0);
            }else if($value==-1){
                $con["value"]=array('lt',0);
            }
        }else{
            $value=-1;
            $con["value"]=array('lt',0);
        }
        $this->assign("value",$value);
         $Userbase = M('San_userbase','',$connection);
        if(isset($_GET["game_user_name"])){
            $game_user_name=I("get.game_user_name");
            if($game_user_name!=null){
                $where["uname"]=array('like', "%$game_user_name%");
                $uname=$Userbase->where($where)->find();

		if($uname==null){
		echo "<script>alert('用户信息错误，无法查找');location.href='http://106.15.137.174/Test/Home/Prop/index'</script>";exit;
		}
                $con["uid"]=$uname["uid"];
            }else{
                if(isset($_GET["game_user_id"])){
                    if($_GET["game_user_id"]==null){
                        $con["uid"]=null;
                    }else{
                        $con["uid"]=I("get.game_user_id");
                    }

                }else{
                    $con["uid"]=null;
                }
            }
        }else{
            if(isset($_GET["game_user_id"])){
                if($_GET["game_user_id"]=="undefined"){
                    $con["uid"]=null;
                }else{
                    $con["uid"]=I("get.game_user_id");
                }

            }else{
                $con["uid"]=null;
            }
        }


//var_dump($_GET["game_user_id"]);
        if($_GET["goods_name"]!="undefined"){
           $goods_name=I("get.goods_name");
        }else{
            $goods_name=null;
        }

        $con=array_filter($con);
	//var_dump($con);
         $count      = $San_log->where($con)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=$San_log->where($con)->limit($Page->firstRow.','.$Page->listRows)->order("id desc")->select();
//var_dump($arr);
         for($i=0;$i<count($arr);$i++){
            $goods_id=$arr[$i]["type"];
            $Gstu=D("goods")->where("itemid=$goods_id")->find();
            if($goods_name!=null){
                similar_text($goods_name, $Gstu["itemname"], $percent);

                if((float)$percent>60){
                    $arrs[$i]["goods_name"]=$Gstu["itemname"];
                    $uid=$arr[$i]["uid"];
                    $RUS=$Userbase->where("uid=$uid")->find();
                    $arrs[$i]["uname"]=$RUS["uname"];
                    $arrs[$i]["level"]=$RUS["level"];
                    $arrs[$i]["time"]=date("Y-m-d H:i:s",$arr[$i]["time"]);
                    $arrs[$i]["uid"]=$arr[$i]["uid"];
                    $arrs[$i]["dec"]=$arr[$i]["dec"];
                    $arrs[$i]["value"]=$arr[$i]["value"];
                }
            }else{
                $arrs[$i]["goods_name"]=$Gstu["itemname"];
                $uid=$arr[$i]["uid"];
                $RUS=$Userbase->where("uid=$uid")->find();
                $arrs[$i]["uname"]=$RUS["uname"];
                $arrs[$i]["level"]=$RUS["level"];
                $arrs[$i]["time"]=date("Y-m-d H:i:s",$arr[$i]["time"]);
                $arrs[$i]["uid"]=$arr[$i]["uid"];
                $arrs[$i]["dec"]=$arr[$i]["dec"];
                $arrs[$i]["value"]=$arr[$i]["value"];
            }

        }


	 $this->assign("arr",$arrs);
        $this->display();

}else{
 $this->display();
}
    }
    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
         } else {
            $game_id = 1;
          }
         $db_id=I("get.db_id");
            $Stime=I("get.start_time");
           $Etime=I("get.end_time");

        $Betime=strtotime($Stime);
        $Entime=strtotime($Etime);
        $con["_string"]="time>=$Betime AND time<=$Entime";

        // 日志点
        $connection=db($game_id,$db_id);
        $San_log = M('San_log','',$connection);
        if(isset($_GET["value"])){
            $value=I("get.value");
            if($value==1){
                $con["value"]=array('gt',0);
            }else if($value==-1){
                $con["value"]=array('lt',0);
            }
        }else{
            $value=-1;
            $con["value"]=array('lt',0);
        }


        if(isset($_GET["game_user_id"])){
            if($_GET["game_user_id"]==null){
                $con["uid"]=null;
            }else{
                $con["uid"]=I("get.game_user_id");
            }

        }else{
            $con["uid"]=null;
        }
        if(isset($_GET["game_user_name"])){
            $game_user_name=I("get.game_user_name");
            if($game_user_name!=null){
                $con["uname"]=array('like', "%$game_user_name%");
            }else{
                $con["game_user_name"]=null;
            }
        }else{
            $con["game_user_name"]=null;
        }

        if(isset($_GET["dec"])){
            if($_GET["dec"]=="所有"){
                $con["dec"]=null;
            }else{
                $con["dec"]=I("get.dec");
            }
        }else{
            $con["dec"]=null;
        }

        $con=array_filter($con);
        $arr=$San_log->where($con)->select();
        $Userbase = M('San_userbase','',$connection);
        for($i=0;$i<count($arr);$i++){
            $uid=$arr[$i]["uid"];
            $RUS=$Userbase->where("uid=$uid")->find();
            $arr[$i]["uname"]=$RUS["uname"];
            $arr[$i]["level"]=$RUS["level"];
            $arr[$i]["time"]=date("Y-m-d H:i:s",$arr[$i]["time"]);
            $goods_id=$arr[$i]["type"];
            $Gstu=D("goods")->where("itemid=$goods_id")->find();
            $arr[$i]["goods_name"]=$Gstu["itemname"];
        }

        $objPHPExcel=new \PHPExcel();

        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','玩家ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','玩家名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','玩家等级');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','产出（消耗）名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','产出（消耗）数值');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','产出（消耗）');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','产出（消耗时间）');

        //把数据循环写入excel中
        $i=1;
        foreach($arr as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["uid"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['uname']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['level']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['dec']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$value['value']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,$value['goods_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,$value['time']);



        }
        //导出代码
        $name=time();
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


    }
}