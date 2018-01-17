<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 下午 5:18
 */

namespace Doc\Controller;


use Think\Controller;

class CodeController extends Controller
{
// 邀请码请求
    public function code_post(){
        //     $_POST["code_number"]="1DzVpc171";
        //   $_POST["uid"]=13;
        //    $_POST["channel"]="";

        if(isset($_GET["code_number"])){
            $code_number=I("get.code_number");
            $uid=I("get.uid");
            $channel=I("get.channel");
            $db_id=I("get.db_id");
            // 判断是否存在该邀请码
            $rus=D("code")->where("code_number='$code_number'")->find();
            if($rus==null){
                $data["errcode"]=1000;
                $data["list"]="邀请码错误";
            }else{
                // 判断 该礼包是否被领取
                $rus=D("code_receive")->where("uid=$uid and code_number='$code_number'")->find();
                if($rus==null){


                    $cus=D("code")->where("code_number='$code_number'")->find();
                    $code_page_id=$cus["code_page_id"];  // 邀请码列表id
                    //  var_dump($code_page_id);exit;
                    $vus=D("code_page")->where("code_page_id=$code_page_id")->find();
                    $code_type_id=$vus["code_type_id"];//礼包邀请码类别id
                    $bus=D("code_type")->where("code_type_id=$code_type_id")->find();
                    $code_channel=$bus["channel"];
                    if($code_channel==$channel || $code_channel==0){
                        // 判断时间 是否已经失效
                        $time=strtotime(date("Y-m-d",time()));
                        $begin_time=strtotime($vus["begin_time"]);
                        $end_time=strtotime($vus["end_time"]);
                        if($time>=$begin_time&&$time<=$end_time){
                            // 判读用户  同类型的领取
                            $type=$cus["type"];
                            if($type==1){
                                // 可以领用  入库  返回 道具ID 数量
                                $type=$cus["type"];
                                $aus=D("code_receive")->where("uid=$uid and code_page_id=$code_page_id ")->find();
                                if($aus!=null){
                                    $data["errcode"]=1006;
                                    $data["list"]="该用户已经领取过该类型礼包";
                                }else {
                                    $arr["uid"] = $uid;
                                    $arr["type"] = $type;
                                    $arr["code_number"] = $code_number;
                                    $arr["channel"] = $channel;
                                    $arr["time"] = date("Y-m-d H:i:s", time());
                                    $arr["code_page_id"] = $code_page_id;

                                    $rus = D("code_receive")->add($arr);
                                    if ($rus != null) {
                                        $goods_ids = $bus["goods_ids"];
//var_dump($goods);
                                        $goods = explode(';', $goods_ids);
                                        for ($i = 0; $i < count($goods); $i++) {
                                            $ru = explode(':', $goods[$i]);
                                            $stu[$i]["goods_id"] = (int)$ru[0];
                                            $stu[$i]["num"] = (int)$ru[1];
                                            $sssstu[$i]["itemid"] = (int)$ru[0];
                                            $sssstu[$i]["num"] = (int)$ru[1];
                                        }
                                        $get_data["item"] = $sssstu;
                                        $get_data["uid"] = (int)$uid;

                                        $jsData = json_encode($get_data, JSON_UNESCAPED_UNICODE);

                                        $ruSdata = base64_encode($jsData);

                                        $ruSdata = str_replace(array('+', '/'), array('-', '_'), $ruSdata);
                                        $data = D("db")->where("db_id=$db_id")->find();
                                        $ip = $data["ip"];
                                        $port = $data["db_port"];
                                        $token = "alibabawansui";
                                        $md = md5($token);
                                        //调用接口http
                                        $url = "http://$ip:$port/senditem?context=$ruSdata&token=$md";
                                        //  $url="http://$ip:$port/senditem?context=$ruSdata";
                                        //    echo $url;
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                        $output = curl_exec($ch);
                                        curl_close($ch);
//var_dump($output);exit;
                                        $data["errcode"] = 0;
                                        $data["list"] = $stu;
                                    } else {
                                        echo "1005,接口奔溃";
                                    }
                                }
                            }else if($type==2){
                                // 判断 该邀请码 是否已经被领用   唯一礼包
                                $type=$cus["type"];
                                $aus=D("code_receive")->where("uid=$uid and code_page_id=$code_page_id ")->find();
                                if($aus!=null){
                                    $data["errcode"]=1006;
                                    $data["list"]="该用户已经领取过该类型礼包";
                                }else {
                                    $rususer=D("code_receive")->where("code_number='$code_number'")->find();
                                    if($rususer){
                                        $data["errcode"]=1001;
                                        $data["list"]="该用户已经领取该礼包";
                                    }

                                    $arr["uid"] = $uid;
                                    $arr["code_number"] = $code_number;
                                    $arr["channel"] = $channel;
                                    $arr["code_page_id"] = $code_page_id;
                                    $arr["type"] = $type;
                                    $arr["time"] = date("Y-m-d H:i:s", time());
                                    $rus = D("code_receive")->add($arr);
                                    if ($rus != null) {
                                        $goods_ids = $bus["goods_ids"];

                                        $goods = explode(';', $goods_ids);
                                        for ($i = 0; $i < count($goods); $i++) {
                                            $ru = explode(':', $goods[$i]);
                                            $stu[$i]["goods_id"] = (int)$ru[0];
                                            $stu[$i]["num"] = (int)$ru[1];
                                            $sssstu[$i]["itemid"] = (int)$ru[0];
                                            $sssstu[$i]["num"] = (int)$ru[1];
                                        }
                                        $get_data["item"] = $sssstu;
                                        $get_data["uid"] = (int)$uid;
                                        $jsData = json_encode($get_data, JSON_UNESCAPED_UNICODE);

                                        $ruSdata = base64_encode($jsData);
                                        $ruSdata = str_replace(array('+', '/'), array('-', '_'), $ruSdata);
                                        $data = D("db")->where("db_id=$db_id")->find();
                                        $ip = $data["ip"];
                                        $port = $data["db_port"];
                                        $token="alibabawansui";
                                        $md=md5($token);
                                        $url="http://$ip:$port/senditem?context=$ruSdata&token=$md";
                                        //   $url = "http://$ip:$port/senditem?context=$ruSdata";
                                        //   echo $url;
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                        $output = curl_exec($ch);
                                        curl_close($ch);


                                        $data["errcode"] = 0;
                                        $data["list"] = $stu;
                                    } else {
                                        $data["errcode"] = 1005;
                                        $data["list"] = "接口奔溃";
                                    }

                                }


                            }else {
                                $data["errcode"] = 1007;
                                $data["list"] = "该类型礼包不存在";

                            }


                        }else{
                            $data["errcode"]=1003;
                            $data["list"]="邀请码已经过期";
                        }
                    }else{
                        $data["errcode"]=1002;
                        $data["list"]="礼包渠道不对";
                    }
                }else{
                    $data["errcode"]=1001;
                    $data["list"]="该用户已经领取该礼包";
                }
            }
            $data=json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $data;
            ///  return $data;
            // var_dump($data);



        }
    }
}