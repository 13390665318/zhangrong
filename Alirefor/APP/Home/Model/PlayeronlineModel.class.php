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

    protected $fields=array('LogTime','Operation','db_id','num','time','f_time','start_time','game_user_id','ip','game_user_name','register_time','level','pay_number','pay_time','user_id');
    public function onlineadd($linestatus)
    {


        D('Period')->addAll($linestatus, array(), true);


    }
    public function loginadd($LoginRole)
    {

        D('Sign')->addAll($LoginRole, array(), true);
        foreach ($LoginRole as $key=>$value){
            D('User')->where('game_user_id="'.$value['game_user_id'].'"')->setField('level',$LoginRole[$key]['level']);
        }

    }
    public function createadd($CreateRole){

        D('User')->addAll($CreateRole, array(), true);
    }
    public function payadd($Prepaid){


        D('Pay')->addAll($Prepaid, array(), true);
    }


       // dump($linestatus[$key]['time']);exit;
       /* D('Online')->where('time="'.$value['time'].'"')->setField($nyr,$linestatus[$key][$nyr]);
    }*/
        //D('Online')->addAll($linestatus,array(),true);
       /* dump($linestatus);exit;
        D('Online')->where('1=1')->setField($nyr,1);*/
      //  D('Online')->addAll($linestatus,array(),true);


   /* public function loginadd($LoginRole){
        D('Sign')->addAll($LoginRole,array(),true);
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
    }*/

}
