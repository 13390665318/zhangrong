<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11 0011
 * Time: 下午 5:29
 */

namespace Home\Controller;


class PCUController extends BaseController
{
    public function index(){
        $game_id = 1;
        $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu",$clostu);
        // 图标 默认 最新服
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
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);

        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

        }
        $connection=db2($game_id,$db_id);
        for($i=0;$i<count($arr);$i++) {
            $data[$i]["time"] = $arr[$i]["time"];
            $Strtime = $arr[$i]["time"];
            $data[$i]["num"] = 0;
            $sum = M('period')->where("time='$Strtime'")->Max("num");
            $data[$i]["num"] = (int)$sum;


        }

        $Stime=substr($Stime,0,strlen($Stime)-1);
        $this->assign("data",$data);
        $jsoBj=json_encode($data);
        $this->assign("jsoBj",$jsoBj);
        $this->assign("Stime",$Stime);




        $this->display();
    }

    public function exl(){
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel=new \PHPExcel();
        $game_id = 1;
        if(isset($_GET["bclothes"]) && isset($_GET["eclothes"])){
            $bclothes=I("get.bclothes");
            $eclothes=I("get.eclothes");
            if( $bclothes==0 && $eclothes==0 ){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("game_id=$game_id and clothes_num>=$bclothes and clothes_num<=$eclothes ")->order("db_id asc")->select();
            }
        }else{
            $db=D("db")->select();
        }
        if(isset($_GET["stime"])&&isset($_GET["etime"])){
            $stime=I("get.stime");
            $etime=I("get.etime");
        }else{
            // 默认 当月
            $stime=date("Y-m-d",strtotime("-6 day"));
            $etime=date("Y-m-d",time());
        }
        $this->assign("stime",$stime);
        $this->assign("etime",$etime);

        $num=count_days($stime,$etime);
        //  var_dump($con);exit;
        $Stime=null;
        for($i=0;$i<=$num;$i++){
            $Stime='" '.date('m-d', strtotime ("-$i day", strtotime($etime))).'" '.",".$Stime;
            $arr[$i]["time"]=date('Y-m-d', strtotime ("-$i day", strtotime($etime)));

        }

        for($i=0;$i<count($arr);$i++){
            $data[$i]["time"]=$arr[$i]["time"];
            $Strtime=$arr[$i]["time"];
            $data[$i]["num"]=0;
            for($j=0;$j<count($db);$j++){
                $db_id=$db[$j]["db_id"];

                $sum=M("periods")->where("db_id=$db_id and time='$Strtime'")->Max("num");
                //     echo M("periods")->getLastSql();exit;
                $data[$i]["num"]=(int)$sum;

            }
        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','最高在线人数');


        //把数据循环写入excel中
        $i=1;
        foreach($data as $key => $value){
            $key+=2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['num']);




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
    public function lists(){
        //dump(C("REDIS_HOST"));
        $Redis=new \Redis("list1");
        $Redis->connect('43.254.151.195',6379);
        $field=array(
            "nmae","age","pro"
        );
        $data=$Redis->get(1);
        dump($data);
        //获得队列中的记录总数

    }
    public function  index5(){
        $redis = new \Redis();
        $redis->connect('43.254.151.195',6379);
        $redis->select('2');
        echo  $redis->get("san_account");
        /**
        $redis = new \Redis();
        $redis->connect('43.254.151.195',6379);
        //  $result = $redis->connect('43.254.151.195',6379)->get('key',60)->find();
        $data = S('key','111');
        var_dump($data);
        S(array(
        'type'=>'redis',
        'host'=>'43.254.151.195',
        'port'=>'6379',
        'prefix'=>'',
        'expire'=>60)
        ); //缓存初始化
        S(1,1);
        echo S(1);
         **/
        /**
        $Redis=new RedisModel("1");
        $field=array("nmae","age","pro");
        $data=$Redis->field($field)->select();
        dump($data);
        //获得队列中的记录总数
        $count=$Redis->count();
        dump($count);
         **/
    }
}