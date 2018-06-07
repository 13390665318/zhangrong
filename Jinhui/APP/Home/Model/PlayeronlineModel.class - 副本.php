<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 上午 10:47
 */

namespace Home\Model;

use Think\Model;

class PlayeronlineModel extends  Model{

    protected $fields=array('LogTime','Operation','serverID','OnLinePlayerNum','time','date','2018-01-01','2018-01-02','2018-01-03','2018-01-04','2018-01-05','2018-01-06');
    public function onlineadd($linestatus,$nyr){

    foreach ($linestatus as $key=>$value){
       // dump($linestatus[$key]['time']);exit;
        D('Online')->where('time="'.$value['time'].'"')->setField($nyr,$linestatus[$key][$nyr]);
    }
        //D('Online')->addAll($linestatus,array(),true);
       /* dump($linestatus);exit;
        D('Online')->where('1=1')->setField($nyr,1);*/
      //  D('Online')->addAll($linestatus,array(),true);

    }
    public function loginroleadd($loginrole){
        D('LoginRole')->addAll($loginrole,array(),true);
    }
    public function yuanbaouseadd($yuanbaouse){
        D('YuanbaoUse')->addAll($yuanbaouse,array(),true);
    }
    public function itembuyadd($itembuy){
        D('ItemBuy')->addAll($itembuy,array(),true);
    }
    public function chatadd($chat){
        D('Chat')->addAll($chat,array(),true);
    }

}
