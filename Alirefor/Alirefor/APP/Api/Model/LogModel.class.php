<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 上午 10:47
 */

namespace Api\Model;


use Think\Model;

class LogModel extends Model
{
    protected $fields=array('LogTime','Operation','db_id','num','time','f_time','start_time','game_user_id','ip',
        'game_user_name','register_time','level','pay_number','pay_time','user_id','server','account_id','role_id',
        'role_name','role_level','item_reason','change_type','item_get','item_use','item_left','prop','account_id',
        'currency_reason','value');
    //在线添加
    public function onlineadd($linestatus)
    {
       $result= D('Period')->addAll($linestatus);
       return $result;
    }
    //登陆添加
    public function loginadd($LoginRole)
    {

        $result=D('Sign')->addAll($LoginRole);

        foreach ($LoginRole as $key=>$value){
            $data=array('level'=>$LoginRole[$key]['level'],'role_ChangeLife'=>$LoginRole[$key]['role_ChangeLife']);
            D('User')->where('game_user_id="'.$value['game_user_id'].'"')->save($data);
        }
        return $result;

    }
    //创建日期添加
    public function createadd($CreateRole){

        $result= D('User')->addAll($CreateRole);
        return $result;
    }
    //支付添加
    public function payadd($Prepaid){

        $result= D('Pay')->addAll($Prepaid);
        return $result;
    }
    //背包添加

    public function backpackadd($backpack){

        $result= D('Backpack')->addAll($backpack);
        return $result;
    }
    //资源添加
    public function currencyadd($currency){

        $result= D('currency')->addAll($currency);
        return $result;

    }
    //角色获得经验添加
    public function roleexpadd($roleexp){

        $result= D('roleexp')->addAll($roleexp);
        return $result;

    }
    //邮箱日志添加
    public function emaillogadd($emaillog){

        $result=  D('emaillog')->addAll($emaillog);
        return $result;

    }
    //战场日志添加
    public function mapchangeadd($mapchange){

        $result=  D('mapchange')->addAll($mapchange);
        return $result;

    }
    //装备日志添加
    public function equiplogadd($equiplog){

        $result=  D('equiplog')->addAll($equiplog);
        return $result;

    }
    //翅膀日志添加
    public function winglogadd($winglog){

        $result=  D('winglog')->addAll($winglog);
        return $result;

    }
    //登出日志添加
    public function logoutlogadd($logoutlog){

        $result=  D('logoutlog')->addAll($logoutlog);
        return $result;

    }
    //登陆日志添加
    public function loginlogadd($LoginRole){

        $result= D('loginlog')->addAll($LoginRole);
        return $result;

    }
    //商店购买日志
    public function ItemBuyadd($ItemBuylog){

        $result=  D('ItemBuy')->addAll($ItemBuylog);
        return $result;

    }
    //帮会人员变动
    public function Guideadd($guide){

        $result=D('Guide')->addAll($guide);
        return $result;

    }
    //帮会变化
    public function Unionadd($union){

        $result= D('Union')->addAll($union);
        return $result;

    }
    //宝藏抽奖日志
    public function Treasureadd($treasure){

        $result=  D('Treasure')->addAll($treasure);
        return $result;
    }

    public function JoinFubenadd($JoinFuben){

        $result=  D('Joinfuben')->addAll($JoinFuben);
        return $result;

    }



}