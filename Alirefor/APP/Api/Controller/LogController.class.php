<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 上午 11:52
 */

namespace Api\Controller;



class LogController extends BaseController
{
    public function log()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        if (IS_POST) {
            //获取json
            $json = file_get_contents("php://input");
            $json = explode(PHP_EOL, $json);
            foreach ($json as $value) {
               $redis->rPush('list',$value);
            }
            if($redis->lLen('list')>20){
                $data=$redis->lrange('list',0, -1);
                foreach ($data as $value){
                    $datar[]=json_decode($value,1);
                }
                $redis->del('list');
                foreach ($datar as $k => $value) {
                    if ($value['Operation'] == 'OnlineRoleNum') {
                        $linestatus[] = $value;
                    } elseif ($value['Operation'] == 'LoginRole') {
                        $LoginRole[] = $value;
                    } elseif ($value['Operation'] == 'CreateRole') {
                        $CreateRole[] = $value;
                    } elseif ($value['Operation'] == 'Prepaid') {
                        $Prepaid[] = $value;
                    } elseif ($value['Operation'] == 'backpack') {
                        $backpack[] = $value;
                    } elseif ($value['Operation'] == 'currency') {
                        $currency[] = $value;
                    } elseif ($value['Operation'] == 'role_exp') {
                        $roleexp[] = $value;
                    } elseif ($value['Operation'] == 'email') {
                        $emaillog[] = $value;
                    } elseif ($value['Operation'] == 'map_change') {
                        $mapchange[] = $value;
                    } elseif ($value['Operation'] == 'treasure') {
                        $treasure[] = $value;
                    } elseif ($value['Operation'] == 'equip') {
                        $equiplog[] = $value;
                    } elseif ($value['Operation'] == 'wing') {
                        $winglog[] = $value;
                    } elseif ($value['Operation'] == 'LogoutRole') {
                        $logoutlog[] = $value;
                    } elseif ($value['Operation'] == 'ItemBuy') {
                        $ItemBuylog[] = $value;
                    } elseif ($value['Operation'] == 'union_member') {
                        $guide[] = $value;
                    } elseif ($value['Operation'] == 'union_info') {
                        $union[] = $value;
                    } elseif ($value['Operation'] == 'JoinFuben') {
                        $JoinFuben[] = $value;
                    } elseif ($value['Operation'] == 'YuanbaoUse') {
                        $YuanbaoUse[] = $value;
                    } elseif ($value['Operation'] == 'fashion') {
                        $Fashion[] = $value;
                    } elseif ($value['Operation'] == 'usemoney') {
                        $usemoney[] = $value;
                    } elseif ($value['Operation'] == 'YuanbaoGain') {
                        $YuanbaoGain[] = $value;
                    }
                }//将日志中帮会围攻战中的副本名原为BOSS名的统一更改为帮战围攻战
		
                $model = D('Log');
				
                $model->YuanbaoGainadd($YuanbaoGain);
				foreach ($usemoney as $key =>$value){
                    $usemoney[$key]['serverID']=1;
                }
			
                $model->Usemoneyadd($usemoney);

                foreach ($Fashion as $key=>$value){
                    $Fashion[$key]['preequip']=json_encode($value['preequip']);
                    $Fashion[$key]['item_use']=json_encode($value['item_use']);
                    $Fashion[$key]['item_left']=json_encode($value['item_left']);
                }

                $model->Fashionadd($Fashion);

                $model->YuanbaoUseadd($YuanbaoUse);

                foreach ($JoinFuben as $key => $value) {

                    if (strpos($value['fuben_name'], '锁魔塔') !== false) {
                        $JoinFuben[$key]['fuben_name'] = "锁魔塔";
                    } elseif (strpos($value['fuben_name'], '魔炎灵主') !== false
                        or strpos($value['fuben_name'], '女王蛇魔') !== false
                        or strpos($value['fuben_name'], '斩魂将军') !== false
                        or strpos($value['fuben_name'], '刑天') !== false
                        or strpos($value['fuben_name'], '九婴') !== false
                        or strpos($value['fuben_name'], '尾兽') !== false) {
                        $JoinFuben[$key]['fuben_name'] = "帮会围攻战";
                    }
                }

                //调用模型方法进行插入数据

                $model->JoinFubenadd($JoinFuben);

                foreach ($treasure as $key=>$value){
                    $treasure[$key]['treasure_get']=json_encode($value['treasure_get']);
                    $treasure[$key]['diamond_use']=json_encode($value['diamond_use']);
                    $treasure[$key]['diamond_left']=json_encode($value['diamond_left']);
                }

                $model->Treasureadd($treasure);

                $model->Unionadd($union);

                $model->Guideadd($guide);
                foreach ($ItemBuylog as $key=>$value){
                    $ItemBuylog[$key]['GainItems']=json_encode($value['GainItems']);
                    $ItemBuylog[$key]['currency_use']=json_encode($value['currency_use']);
                    $ItemBuylog[$key]['currency_left']=json_encode($value['currency_left']);
                    $ItemBuylog[$key]['prop']=$value['GainItems']['goodId'];
                }

                $model->ItemBuyadd($ItemBuylog);

                //登录玩家信息序列化
                foreach ($LoginRole as $key=>$value){
                    $LoginRole[$key]['player_message']=json_encode($value['player_message']);
                }
                $model->loginlogadd($LoginRole);
                //登出信息序列化
                foreach ($logoutlog as $key=>$value){
                    $logoutlog[$key]['player_message']=json_encode($value['player_message']);
                }

                $model->logoutlogadd($logoutlog);
                foreach ($winglog as $key=>$value){
                    $winglog[$key]['prewing']=json_encode($value['prewing']);
                    $winglog[$key]['laterewing']=json_encode($value['laterewing']);
                    $winglog[$key]['item_use']=json_encode($value['item_use']);
                    $winglog[$key]['item_left']=json_encode($value['item_left']);
                    $winglog[$key]['gold_use']=json_encode($value['gold_use']);
                    $winglog[$key]['gold_left']=json_encode($value['gold_left']);
                }

                $model->winglogadd($winglog);
		
                foreach ($equiplog as $key=>$value){
                    $equiplog[$key]['equipInfo']=json_encode($value['equipInfo']);
                    $equiplog[$key]['consumeInfo']=json_encode($value['consumeInfo']);
                    $equiplog[$key]['leftInfo']=json_encode($value['leftInfo']);
                }
				


                $model->equiplogadd($equiplog);

                $model->mapchangeadd($mapchange);
                //邮件日志序列化
					
                foreach ($emaillog as $key=>$value){
                    $emaillog[$key]['email_get']=json_encode($value['email_get']);
                }
				
                $model->emaillogadd($emaillog);

                $model->roleexpadd($roleexp);

                //给资源使用增加字段
                foreach ($currency as $k => $value) {
                    $currency[$k]['currency_get']=json_encode($value['currency_get']);
                    $currency[$k]['currency_use']=json_encode($value['currency_use']);
                    $currency[$k]['currency_left']=json_encode($value['currency_left']);
                    if (($currency[$k]['currency_use'])=="[]") {
                        $currency[$k]['value'] = 1;
                    } else {
                        $currency[$k]['value'] = -1;
                    }
                }

                $model->currencyadd($currency);

                //使用正则筛选



				
                foreach ($backpack as $k => $value) {
                    $backpack[$k]['item_get']=json_encode($value['item_get']);
                    $backpack[$k]['item_use']=json_encode($value['item_use']);
                    $backpack[$k]['item_left']=json_encode($value['item_left']);
                    if($value['item_use']==null){
                        $backpack[$k]['prop']=$value['item_get'][0]['goodId'];
                    }else{
                        $backpack[$k]['prop']=$value['item_use'][0]['goodId'];
                    }
                }
			
                $model->backpackadd($backpack);
				
                //循环修改键名
						
                foreach ($Prepaid as $k => $value) {
                    $Prepaid[$k]['pay_number'] = $value['cash'];
                    $Prepaid[$k]['game_user_name'] = $value['role_name'];
                    $Prepaid[$k]['db_id'] = 1;
                    $Prepaid[$k]['game_user_id'] = $value['role_id'];
                    $Prepaid[$k]['level'] = $value['role_level'];
                    $Prepaid[$k]['user_id'] = $value['account_id'];
                    unset($Prepaid[$k]['cash']);
                    unset($Prepaid[$k]['role_name']);
                    unset($Prepaid[$k]['serverID']);
                    unset($Prepaid[$k]['role_id']);
                    unset($Prepaid[$k]['role_level']);
                    unset($Prepaid[$k]['account_id']);
                }
			

                $model->payadd($Prepaid);
	
                //修改创建角色日志
                foreach ($CreateRole as $k => $value) {
                    $CreateRole[$k]['register_time'] = $value['create_time'];
                    $CreateRole[$k]['game_user_name'] = $value['role_name'];
                    $CreateRole[$k]['db_id'] = $value['serverID'];
                    $CreateRole[$k]['game_user_id'] = $value['role_id'];
                    $CreateRole[$k]['game_id'] = $value['account_id'];
                    unset($CreateRole[$k]['create_time']);
                    unset($CreateRole[$k]['role_name']);
                    unset($CreateRole[$k]['serverID']);
                    unset($CreateRole[$k]['role_id']);
                }


                $model->createadd($CreateRole);
				
                /*  foreach ($data as $k=>$value){
                      if(isset($value['account_id'])){
                          $account[]=$value;
                      }
                  }*/
                //修改登陆角色日志
                foreach ($LoginRole as $k => $value) {
                    $LoginRole[$k]['start_time'] = $value['LogTime'];
                    $LoginRole[$k]['db_id'] = $value['serverID'];
                    $LoginRole[$k]['game_user_id'] = $value['role_id'];
                    $LoginRole[$k]['level'] = $value['role_level'];
                    $LoginRole[$k]['user_id'] = $value['account_id'];
                    unset($LoginRole[$k]['LogTime']);
                    unset($LoginRole[$k]['role_id']);
                    unset($LoginRole[$k]['role_level']);
                }


                $model->loginadd($LoginRole);


                foreach ($LoginRole as $k => $value) {
                    $new_array[$k] = $value['account_id'];
                }

                $account_id = array_unique($new_array);
                foreach ($account_id as $k => $value) {
                    $last_array[] = $LoginRole[$k];
                }


                //dump($last_array);exit;

                //修改在线日志
                foreach ($linestatus as $key => $value) {
                    $linestatus[$key]['time'] = substr($value['LogTime'], 0, 11);
                    $linestatus[$key]['db_id'] = $value['serverID'];
                    $linestatus[$key]['num'] = $value['OnLinePlayerNum'];
                    unset($linestatus[$key]['OnLinePlayerNum']);
                    unset($linestatus[$key]['serverID']);
                    $linestatus[$key]['f_time'] = substr($linestatus[$key]['LogTime'], 11, 5);
                }
                $model->onlineadd($linestatus);
            }
                echo 1;



        } else {
            echo '请求方式错误';
        }



}


public  function log2(){
    if(IS_POST) {
        echo 'is post';
    }else{
        echo 'not post';
    }
}


/**
 * 退出
 */


}