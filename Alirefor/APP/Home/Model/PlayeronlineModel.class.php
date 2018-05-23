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
    //在线添加
    public function onlineadd($linestatus)
    {

        D('Period')->addAll($linestatus);
    }
    //登陆添加
    public function loginadd($LoginRole)
    {

        D('Sign')->addAll($LoginRole);
        foreach ($LoginRole as $key=>$value){
            $data=array('level'=>$LoginRole[$key]['level'],'role_ChangeLife'=>$LoginRole[$key]['role_ChangeLife']);
            D('User')->where('game_user_id="'.$value['game_user_id'].'"')->save($data);
        }

    }
    //创建日期添加
    public function createadd($CreateRole){

        D('User')->addAll($CreateRole);
    }
    //支付添加
    public function payadd($Prepaid){

        D('Pay')->addAll($Prepaid);
    }
    //背包添加

    public function backpackadd($backpack){

        D('Backpack')->addAll($backpack);
    }
    //资源添加
    public function currencyadd($currency){

        D('currency')->addAll($currency);

    }
    //角色获得经验添加
    public function roleexpadd($roleexp){

        D('roleexp')->addAll($roleexp);

    }
    //邮箱日志添加
    public function emaillogadd($emaillog){

        D('emaillog')->addAll($emaillog);

    }
    //战场日志添加
    public function mapchangeadd($mapchange){

        D('mapchange')->addAll($mapchange);

    }
    //装备日志添加
    public function equiplogadd($equiplog){

        D('equiplog')->addAll($equiplog);

    }
    //翅膀日志添加
    public function winglogadd($winglog){

        D('winglog')->addAll($winglog);

    }
    //登出日志添加
    public function logoutlogadd($logoutlog){

        D('logoutlog')->addAll($logoutlog);

    }
    //登陆日志添加
    public function loginlogadd($LoginRole){

        D('loginlog')->addAll($LoginRole);

    }
    //商店购买日志
    public function ItemBuyadd($ItemBuylog){

        D('ItemBuy')->addAll($ItemBuylog);

    }
    //帮会人员变动
    public function Guideadd($guide){

        D('Guide')->addAll($guide);

    }
    //帮会变化
    public function Unionadd($union){

        D('Union')->addAll($union);

    }
    //宝藏抽奖日志
    public function Treasureadd($treasure){

        D('Treasure')->addAll($treasure);

    }

    public function JoinFubenadd($JoinFuben){

        D('Joinfuben')->addAll($JoinFuben);

    }






}
