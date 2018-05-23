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

    protected $fields=array('LogTime','Operation','db_id','num','time','f_time','start_time','game_user_id','ip',
        'game_user_name','register_time','level','pay_number','pay_time','user_id','server','account_id','role_id',
        'role_name','role_level','item_reason','change_type','item_get','item_use','item_left','prop','account_id',
        'currency_reason','value');
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
    public function backpackadd($backpack){

        D('Backpack')->addAll($backpack,array(),true);
    }
    public function currencyadd($currency){

        D('currency')->addAll($currency,array(),true);

    }
    public function roleexpadd($roleexp){

        D('roleexp')->addAll($roleexp);

    }
    public function emaillogadd($emaillog){

        D('emaillog')->addAll($emaillog);

    }
    public function mapchangeadd($mapchange){

        D('mapchange')->addAll($mapchange);

    }
    public function equiplogadd($equiplog){

        D('equiplog')->addAll($equiplog);

    }
    public function winglogadd($winglog){

        D('winglog')->addAll($winglog);

    }
    public function logoutlogadd($logoutlog){

        D('logoutlog')->addAll($logoutlog);

    }
    public function loginlogadd($LoginRole){

        D('loginlog')->addAll($LoginRole);

    }
    public function ItemBuyadd($ItemBuylog){

        D('ItemBuy')->addAll($ItemBuylog);

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
