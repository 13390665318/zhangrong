<?php
namespace Doc\Controller;

use Think\Controller;
class NoticeController extends Controller
{
    public function index(){
        if(isset($_GET["notice_id"])){
            $notice_id=I("get.notice_id");
            $list=D("notice")->where("notice_id=$notice_id")->find();
            $this->assign("list",$list);
            $this->display();
        }
    }

    public function  refor(){
        // $_GET["db_id"]=1;
        if(isset($_GET["db_id"])){

            $db_id=I("get.db_id");
            $time=strtotime(date("Y-m-d H:i:s",time()));
            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            //  $redis->set('test','hello world!');
            $notice=$redis->keys("noticeAlirefor"."*");

            // $notice=json_decode($notice,true);
            for($i=0;$i<count($notice);$i++){
                $arrs[$i]=$redis->get($notice[$i]);
                $ksd=json_decode($arrs[$i],true);
                //   var_dump($ksd);
                if(strtotime($ksd["begin_time"])<=$time){//echo 1;
                    //echo strtotime( $arrs[$i]["end_time"])."   " .$time;
                    if(strtotime($ksd["end_time"])>$time){// echo 2;
                        if( $ksd["status"]==0){ //echo 3;
                            if($ksd["type"]==2){// echo 4;
                                $arr[$i]=$ksd;
                            }
                        }
                    }
                }
            }
            $arr=array_values($arr);


            for($i=0;$i<count($arr);$i++){
                $clothes=$arr[$i]["clothes"];

                if($clothes==null){
                    $data[$i]["title"]=$arr[$i]["title"];
                    $data[$i]["content"]=$arr[$i]["content"];
                    $data[$i]["priority"]=$arr[$i]["priority"];
                }else{
                    $stu=explode(",",$clothes);
                    $con=array_filter($stu);
                    if(in_array($db_id,$con)!=null){
                        $data[$i]["title"]=$arr[$i]["title"];
                        $data[$i]["content"]=$arr[$i]["content"];
                        $data[$i]["priority"]=$arr[$i]["priority"];
                        //   $data[$i]["url"]="http://www.text.com/CT/index.php?m=Doc&c=Show&a=index&id=".$arr[$i]["notice_id"];
                    }
                }
            }

            $data=array_values($data);
        //    $data = arraySort($data, 'priority', 'asc');
            if($data==null){
                $list["code"]=1021;
                $list["list"]=null;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }else{
                $list["code"]=0;
                $list["list"]=$data;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }
            echo $list;




        }else{
            $time=strtotime(date("Y-m-d H:i:s",time()));
            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            $redis->set('test','hello world!');
            $notice=$redis->keys("noticeAlirefor"."*");
            // $notice=json_decode($notice,true);
            for($i=0;$i<count($notice);$i++){
                $arrs[$i]=$redis->get($notice[$i]);
                $ksd=json_decode($arrs[$i],true);
                //   var_dump($ksd);
                if(strtotime($ksd["begin_time"])<=$time){//echo 1;
                    //echo strtotime( $arrs[$i]["end_time"])."   " .$time;
                    if(strtotime($ksd["end_time"])>$time){// echo 2;
                        if( $ksd["status"]==0){ //echo 3;
                            if($ksd["type"]==2){// echo 4;
                                $arr[$i]=$ksd;
                            }
                        }
                    }
                }
            }
            $arr=array_values($arr);
            for($i=0;$i<count($arr);$i++){
                $clothes=$arr[$i]["clothes"];
                if($clothes==null){
                    $data[$i]["title"]=$arr[$i]["title"];
                    $data[$i]["content"]=$arr[$i]["content"];
                    $data[$i]["priority"]=$arr[$i]["priority"];
                }
            }
            $data=array_values($data);
         //  $data = arraySort($data, 'priority', 'asc');
            if($data==null){
                $list["code"]=1021;
                $list["list"]=null;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }else{
                $list["code"]=0;
                $list["list"]=$data;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }
            echo $list;

        }
    }


    public function  refor2(){
        // $_GET["db_id"]=2;
        if(isset($_GET["db_id"])){
            $db_id=I("get.db_id");
            $time=strtotime(date("Y-m-d H:i:s",time()));
            $redis = new \Redis();
            $redis->connect("127.0.0.1","6379");
            //   $redis->set('test','hello world!');
            $notice=$redis->keys("noticeAlirefor"."*");
            // $notice=json_decode($notice,true);
            for($i=0;$i<count($notice);$i++){
                $arrs[$i]=$redis->get($notice[$i]);
                $ksd=json_decode($arrs[$i],true);
                //   var_dump($ksd);
                if(strtotime($ksd["begin_time"])<=$time){//echo 1;
                    //echo strtotime( $arrs[$i]["end_time"])."   " .$time;
                    if(strtotime($ksd["end_time"])>$time){// echo 2;
                        if( $ksd["status"]==0){ //echo 3;
                            if($ksd["type"]==2){// echo 4;
                                $arr[$i]=$ksd;
                            }
                        }
                    }
                }
            }
            $arr=array_values($arr);
            //   var_dump($arr);
            //     $arr=D("notice")->where(" begin_time<='$time' and end_time >='$time' and status=0 and type=2")->order("priority asc")->select();

            for($i=0;$i<count($arr);$i++){
                $clothes=$arr[$i]["clothes"];

                if($clothes==null){

                    $data[$i]["title"]=$arr[$i]["title"];
                    $data[$i]["content"]=$arr[$i]["content"];
                    $data[$i]["priority"]=$arr[$i]["priority"];
                }else{
                    $stu=explode(",",$clothes);
                    $con=array_filter($stu);
                    if(in_array($db_id,$con)!=null){
                        $data[$i]["title"]=$arr[$i]["title"];
                        $data[$i]["content"]=$arr[$i]["content"];
                        $data[$i]["priority"]=$arr[$i]["priority"];

                    }
                }
            }
            $data=array_values($data);

            if($data==null){
                $list["code"]=1021;
                $list["list"]=null;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }else{
                $list["code"]=0;
                $list["list"]=$data;
                $list=json_encode($list,JSON_UNESCAPED_UNICODE);
            }
            echo $list;




        }
    }
}

?>