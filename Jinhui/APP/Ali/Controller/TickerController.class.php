<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 下午 2:19
 */

namespace Ali\Controller;


use Think\Controller;

class TickerController extends Controller
{
    public function set(){
        if(isset($_POST["sysnotice_id"])){
            $sysnotice_id=I("post.sysnotice_id");
            $r=D("sysnotice")->where("sysnotice_id=$sysnotice_id")->find();
            if($r==null){
               //新增
                $data=$_POST;
                $second=floor((strtotime($_POST["end_time"])-strtotime($_POST["begin_time"]))%86400%60);
                $data["num"]=(int)($second/$_POST["systime"]);
                if($data["clothes"]==0){
                    $data["begin_clothes"]=0;
                    $data["end_clothes"]=0;
                }else{
                    $stu=explode(",",$data["clothes"]);
                    $data["begin_clothes"]=array_search(min($stu), $stu);
                    $data["end_clothes"]=array_search(max($stu), $stu);
                }
                $ru=D("sysnotice")->add($data);
                if($ru){
                    echo 0;
                }else{
                    echo -1;
                }
            }else{
                // 更新
                $data=$_POST;
                $second=floor((strtotime($_POST["end_time"])-strtotime($_POST["begin_time"]))%86400%60);
                $data["num"]=(int)($second/$_POST["systime"]);
                $ru=D("sysnotice")->where("sysnotice_id=$sysnotice_id")->save($data);
                if($ru){
                    echo 0;
                }else{
                    echo -1;
                }
            }
        }
    }
}