<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 下午 6:42
 */

namespace Doc\Controller;


use Think\Controller;

class PayController extends Controller
{
    public function payNotify(){
        if(isset($_POST)){
            $body = file_get_contents("php://input", "r");

//$arrs["post"]=$body ;
//$A=D("text")->add($arrs);
            $data=json_decode($body,true);
            $serect_key="158df75271d6439abba7870df6b3c8a2";  // 需配置
            Vendor('Common.Common');
            $Common=new \Common();
            if (!empty($data['sign'])) {

                $sign = $data['sign'];
                unset($data['sign']);

                if ($sign == $Common->getSign($data,$serect_key)) { // 签名检验成功
                    // 1.查询订单号是否存在
                    $cpOrderId=$data["cpOrderId"];
                    $ru=D("order")->where("cporderid='$cpOrderId'")->find();
                    if($ru!=null){
                        die("SUCCESS");
                    }else{
                        //入库
                        $cpOrderParam=$data["cpOrderParam"];

//$arrs["post"]=$cpOrderParam;
//$A=D("text")->add($arrs);
                        $data2=json_decode($cpOrderParam,true);

                        $arr["amount"]=$data["amount"];
                        $arr["cpOrderId"]=$data["cpOrderId"];
                        $arr["dsOrderId"]=$data["dsOrderId"];
                        $arr["gameId"]=$data["gameId"];
                        $arr["status"]=$data["status"];
                        $arr["userId"]=$data["userId"];
                        $arr["creator"]=$data["creator"];
                        $arr["db_id"]=$data2["db_id"];
                        $arr["uid"]=$data2["uid"];
                        $arr["orderid"]=$data2["order_id"];
                        $arr["type"]=$data2["type"];
		      $arr["level"]=$data2["level"];
                        $arr["time"]=date("Y-m-d H:i:s",time());
                        $r=D("order")->add($arr);
                        if($r){
                            // 给服务器发送消息
                            // 数据订单比较
                            $game_id=1;
                            $db_id=$arr["db_id"];
                            $connection=db($game_id,$db_id);
                            $san_recharge= M('San_recharge','',$connection);
                // 查询账号

                            $Account = M('San_account','',$connection);
                            $uid=$data2["uid"];
                            $ru=$Account->where("uid=$uid")->find();
                            $stu["order"]=$data["cpOrderId"];
                            $stu["uid"]=$data2["uid"];
                            $stu["account"]=$ru["account"];
                            $stu["type"]=$data2["type"];
		if($data2["level"]==null){
 $stu["level"]=0;
	}else{
 $stu["level"]=$data2["level"];
	}
                           
			 $stu["money"]=$data["amount"];
                            $stu["timestamp"]=time();
                            $stu["flag"]=0;
  // 入库  san_recharge
//$a=json_encode($stu);                          
//$A=D("text")->add($a);
                            $resu=$san_recharge->add($stu);
                            if($resu){
                                die("SUCCESS");
                            }else{
                                die("FAILURE");
                            }
/**
                            $game_id=$data["gameId"];
                            $db_id=$arr["db_id"];
                            $arr2=D("db")->where("db_id=$db_id")->find();

                            $ip=$arr2["ip"];
                            $port=$arr2["db_port"];
                            $uid=$arr["uid"];
                            $type=$arr["type"];
                            $order_id=$arr["orderid"];
                            $url="http://$ip:$port/recharge?recharge=$type&uid=$uid&status=1&order_id=$order_id";

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            $output = curl_exec($ch);
                            curl_close($ch);
                            $a=json_decode($output);
                            if($a->code==0) {
                                die("SUCCESS");
                            }else{

                                die("FAILURE");
                            }**/
                        }
                    }
                }else{
                    // 签名校验失败
                    die("FAILURE");
                }



            } else {
                // 签名校验失败
                die("FAILURE");
            }
        }else{
            // 没有解释到post值
            die("FAILURE");
        }


    }

    public function test(){
        $url = "http://106.15.137.174/Aliplus/index.php?m=Doc&c=Pay&a=payNotify";
        $body = array (
            "gameId"=>"10000008",
            "cpOrderParam"=>array("db_id"=>1,"uid"=>2459,"order_id"=>15099632862459,'type'=>0),// db_id  服务器 id  uid  用户id  order_id  订单id  type : 类别
            "amount" => 1,
            "dsOrderId"=>"fwx117110618144581094425935",
            "creator"=>"DS",
            "sign"=>"8ac843be68ce0970c45d294f8a77ba70",
            "cpOrderId"=>"1509963286_2459",
            "userId"=>"1addd813256a6658bcaac13ec177e60b",
            "status"=> 1,
        );
        $body=json_encode($body);
        //  var_dump($data);//exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $output = curl_exec($ch);
        curl_close($ch);
        $a=json_decode($output);
        print_r($output);
    }
}