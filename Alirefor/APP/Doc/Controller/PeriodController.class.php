<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/13 0013
 * Time: 下午 12:16
 */

namespace Doc\Controller;


use Think\Controller;

class PeriodController extends Controller
{
        public function count(){
            if(isset($_GET["db_id"])){
                $data["db_id"]=I("get.db_id");
                $data["num"]=I("get.num");
                $data["time"]=date("Y-m-d",time());
                $data["ftime"]=date("H",time());
                $rus=D("periods")->add($data);
                if($rus!=null){
                    $list["code"]=0;
                }else{
                    $list["code"]=-1;
                }
                $r=json_encode($list);
                echo $r;
            }
        }
}