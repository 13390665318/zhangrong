<?php
namespace Home\Controller;

class IndexController extends BaseController {
    public function index()
    {
        $a = exec("D:/copy.bat");
        //新增用户
        $ip = $_SERVER['REMOTE_ADDR'];
//echo $ip;exit;
        $game_id = 1;
        $db = D("db")->select();
        // 默认7天
        $stime = date("Y-m-d", strtotime("-6 day"));
        $etime = date("Y-m-d", time());
        $num = count_days($stime, $etime);
        $Stime = null;
        for ($i = 0; $i <= $num; $i++) {
            $Stime = '" ' . date('m-d', strtotime("-$i day", strtotime($etime))) . '" ' . "," . $Stime;
            $arr[$i]["time"] = date('Y-m-d', strtotime("-$i day", strtotime($etime)));
            $arr[$i]["time1"] = date('Y-m-d 00:00:00', strtotime("-$i day", strtotime($etime)));
            $arr[$i]["time2"] = date('Y-m-d 23:59:59', strtotime("-$i day", strtotime($etime)));
        }
        $user = 0;// 新增用户
        $user2 = 0;//活跃用户
        $user3 = 0;// 充值用户
        for ($i = 0; $i < count($arr); $i++) {
            $data[$i]["time"] = $arr[$i]["time"];
            $Strtime = $arr[$i]["time1"];
            $Endtime = $arr[$i]["time2"];
            $ru['_string'] = "register_time>='$Strtime' and register_time<='$Endtime'"; // 新增用户
            $ru2['_string'] = "start_time>='$Strtime' and start_time<='$Endtime'";//活跃用户
            $ru3["_string"] = "pay_time>='$Strtime' and pay_time<='$Endtime'";//充值用户
            //$pay_user = D("order")->where($ru3)->group('uid')->select(); // 今日充值用户
            $pay_user = D("pay")->where($ru3)->group('game_user_id')->select(); // 今日充值用户
            $data4[$i]["num"] = count($pay_user);
            $amount = (int)D("pay")->where($ru3)->sum(pay_number);
            $data5[$i]["num"] = $amount;
            $data[$i]["num"] = 0;
            $data2[$i]["num"] = 0;
            // for($j=0;$j<count($db);$j++){
            // $db_id=$db[$j]["db_id"];
            // $connection=db2($game_id,$db_id);
            $sum = M('user')->where($ru)->count();
            $data[$i]["num"] = $data[$i]["num"] + $sum;
            $user = $user + $sum;


            $sum2 = M('sign')->where($ru2)->group('game_user_id')->select();
            $data2[$i]["num"] = $data2[$i]["num"] + count($sum2);
            $user2 = $user2 + count($sum2);

            // }
        }


        $Stime = substr($Stime, 0, strlen($Stime) - 1);
        $this->assign("user", $data[0]["num"]);
        $jsoBj = json_encode($data);
        $this->assign("jsoBj", $jsoBj);
        $this->assign("Stime", $Stime);


        $this->assign("user2", $data2[0]["num"]);
        $jsoBj2 = json_encode($data2);
        $this->assign("jsoBj2", $jsoBj2);

        $jsoBj4 = json_encode($data4); // 充值用户
        $this->assign("jsoBj4", $jsoBj4);
        $this->assign("paynum", $data4[0]["num"]);

        $jsoBj5 = json_encode($data5); // 充值金额
        $this->assign("jsoBj5", $jsoBj5);
        $this->assign("paymoney", $data5[0]["num"]);
        // var_dump($jsoBj5);
// 在线人数
        $Stime2 = null;
        $time = date("Y-m-d", time());
        $time1 = date('Y-m-d', strtotime("-0 day", strtotime($time)));
        $time2 = date('Y-m-d', strtotime("-1 day", strtotime($time)));
        $time3 = date('Y-m-d', strtotime("-2 day", strtotime($time)));
        $time4 = date('Y-m-d', strtotime("-4 day", strtotime($time)));
        $time5 = date('Y-m-d', strtotime("-6 day", strtotime($time)));
//echo $time5;
        for ($j = 0; $j < 24; $j++) {
            if ($j < 10) {
                $f_time = "0$j:00";
            } else {
                $f_time = "$j:00";
            }
//echo $f_time." ";
            $data3[$j]["num"] = 0;
            $data3[$j]["nums"] = 0;
            $data3[$j]["numss"] = 0;
            $data3[$j]["numsss"] = 0;
            $data3[$j]["numssss"] = 0;

            //  for($i=0;$i<count($db);$i++){
            //  $db_id=$db[$i]["db_id"];
            // $connection=db2($game_id,$db_id);
            $sum3 = M('period')->where("time='$time' and f_time='$f_time'")->find();
            //echo $sum3["num"];exit;
            $sum4 = M('period')->where("time='$time2' and f_time='$f_time'")->find();
            $sum33 = M('period')->where("time='$time3' and f_time='$f_time'")->find();
            $sum35 = M('period')->where(" time='$time4' and f_time='$f_time'")->find();
            $sum37 = M('period')->where("time='$time5' and f_time='$f_time'")->find();
            $data3[$j]["num"] = $data3[$j]["num"] + $sum3["num"];
            $data3[$j]["nums"] = $data3[$j]["nums"] + $sum4["num"];
            $data3[$j]["numss"] = $data3[$j]["numss"] + $sum33["num"];
            $data3[$j]["numsss"] = $data3[$j]["numsss"] + $sum35["num"];
            $data3[$j]["numssss"] = $data3[$j]["numssss"] + $sum37["num"];
//var_dump($data3);exit;
            // }
            //

        }


        $today = date("Y-m-d", time());
        $lastupdtime = date("Y-m-d H:i:s", time() - 12 * 3600);
        $uonline = M('period')->field('num')->order('f_time desc')->where("time='$today'")->limit(1)->select();
        $uonline = $uonline[0]['num'];
        $adds = 0;
        //$ip:$port/serverstatus?
        // for($i=0;$i<count($db);$i++) {
        // $db_id=$db[$i]["db_id"];
        //   $connection = db($game_id, $db_id);
//dump($connection );exit;
        $Userbase = M('User');
        $add2s = $Userbase->count();
        $adds = $adds + $add2s; // 新增

        /*
                    $ip=$db[$i]["ip"];
                    $port=$db[$i]["db_port"];
                    // var_dump($token);exit;
                    $token=$_SESSION["token"];
                    $md=md5($token);
                    $url="http://$ip:$port/serverstatus?token=$md";
        //echo $url;exit;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $output = curl_exec($ch);

                    curl_close($ch);*/

        //$a=json_decode($output,true);
        // $keyword=$a["msg"];
        //$need=getNeedBetween($keyword, '{' , '}' );
        // $need2=explode(',',$need);
        //$need3=strstr($need2[8],":");
        // $need4=substr($need3,1);
        // $uonline=$uonline+$need4;
        // }

        //  $data3= array_reverse($data3);

        $result = array_merge($data3);
        $this->assign("adds", $adds);
        $result = json_encode($result);

        // 充值人数


        $this->assign("uonline", $uonline);
        $this->assign("result", $result);
// ARPU  今日  付费/登录用户
        // ARPPU   总金额/ 付费用户
        // 累计充值金额
        // 付费率   付费/登录
        // 人均砖石金额
        $sum_money = D("order")->sum(amount) / 100;
        $begin_time = date('Y-m-d 00:00:00', time());
        $end_time = date('Y-m-d 23:59:59', time());
        $day_money = D("order")->where("time>='$begin_time' and time<='$end_time'")->sum(amount) / 100; // 今日付费总金额
        $day_people = D("order")->where("time>='$begin_time' and time<='$end_time'")->group('uid')->select(); // 今日付费用户
        $day_people = count($day_people);
        $sign_people = $data2[0]["num"];// 活跃用户
        $fufeilv = round($day_people / $sign_people, 4) * 100;
        $ARPU = round($day_money / $sign_people, 4);
        $ARPPU = round($day_money / $day_people, 4);
        $this->assign("sum_money", $sum_money);
        $this->assign("fufeilv", $fufeilv);
        $this->assign("ARPU", $ARPU);
        $this->assign("ARPPU", $ARPPU);
        //  $this->assign("result",$result);
        //  $this->assign("result",$result);
// 留存

        $stime = date("Y-m-d", strtotime("-1 day"));
        //  $connection2 = db2(1,$db_id);
        $users = M('user');
        // $Userbase = M('San_userbase','', $connection);
        $Strtime = date('Y-m-d 00:00:00', strtotime("+0 day", strtotime($stime)));
        $Endtime = date('Y-m-d 23:59:59', strtotime("+0 day", strtotime($stime)));
        $add_people = $users->where("register_time>='$Strtime' and register_time<='$Endtime'  and game_id=1 and db_id=1 ")->count();
//echo $add_people;
        //2ri
        $Strtime2 = date('Y-m-d 00:00:00', strtotime("+1 day", strtotime($Strtime)));
        $Endtime2 = date('Y-m-d 23:59:59', strtotime("+1 day", strtotime($Strtime)));
        $save_people = $users->where("register_time>='$Strtime' and register_time<='$Endtime' and end_time>='$Strtime2' and end_time<='$Endtime2' and game_id=1 and db_id=1 and source!=' '")->count();
        $saves = round($save_people / $add_people, 4) * 100;
        $this->assign("saves", $saves);

        $this->display();

        $date=date('Y-m-d H:i:s',time());

        $miao=substr($date,11,5);

        if($miao==="00:00"){
            $ym=date('Y-m-d',strtotime("-1 day"));
        }else{
            $ym=date('Y-m-d',strtotime($date));
        }


            $ym = str_replace('-', '',$ym);
            $ymlog = "public/Log/" . $ym . ".log";

            $json = file_get_contents($ymlog);
            $json = explode(PHP_EOL, $json);

            foreach ($json as $v) {
                $datar[] = json_decode($v, 1);
            }



            foreach ($datar as $k => $value) {
                if ($value['Operation'] == 'OnlineRoleNum') {
                    $linestatus[] = $value;
                } elseif ($value['Operation'] == 'LoginRole') {
                    $LoginRole[] = $value;
                } elseif ($value['Operation'] == 'CreateRole') {
                    $CreateRole[] = $value;
                }elseif($value['Operation']=='Prepaid'){
                    $Prepaid[]=$value;
                }elseif($value['Operation']=='backpack'){
                    $backpack[]=$value;
                }
            }
          //  dump($backpack);exit;
        foreach ($Prepaid as $k=>$value){
            $Prepaid[$k]['pay_number']=$value['cash'];
            $Prepaid[$k]['game_user_name']=$value['role_name'];
            $Prepaid[$k]['db_id']=$value['serverID'];
            $Prepaid[$k]['game_user_id']=$value['role_id'];
            $Prepaid[$k]['level']=$value['role_level'];
            $Prepaid[$k]['user_id']=$value['account_id'];
            unset($Prepaid[$k]['cash']);
            unset($Prepaid[$k]['role_name']);
            unset($Prepaid[$k]['serverID']);
            unset($Prepaid[$k]['role_id']);
            unset($Prepaid[$k]['role_level']);
            unset($Prepaid[$k]['account_id']);
        }

            $model=D('Playeronline');
            $model->payadd($Prepaid);

            foreach ($CreateRole as $k => $value) {
                $CreateRole[$k]['register_time'] = $value['create_time'];
                $CreateRole[$k]['game_user_name'] = $value['role_name'];
                $CreateRole[$k]['db_id'] = $value['serverID'];
                $CreateRole[$k]['game_user_id'] = $value['role_id'];
                unset($CreateRole[$k]['create_time']);
                unset($CreateRole[$k]['role_name']);
                unset($CreateRole[$k]['serverID']);
                unset($CreateRole[$k]['role_id']);
            }

            $model = D('Playeronline');
            $model->createadd($CreateRole);
            /*  foreach ($data as $k=>$value){
                  if(isset($value['account_id'])){
                      $account[]=$value;
                  }
              }*/

            foreach ($LoginRole as $k => $value) {
                $LoginRole[$k]['start_time'] = $value['LogTime'];
                $LoginRole[$k]['game_user_id'] = $value['role_id'];
                $LoginRole[$k]['level'] = $value['role_level'];
                unset($LoginRole[$k]['LogTime']);
                unset($LoginRole[$k]['role_id']);
                unset($LoginRole[$k]['role_level']);
            }

            $model = D('Playeronline');
            $model->loginadd($LoginRole);



            //dump($LoginRole);exit;

            /*foreach ($LoginRole as $k=>$value){
                $new_array[$k]=$value['account_id'];
            }
            $account_id=array_unique($new_array);
            foreach ($account_id as $k=>$value){
                $last_array[]=$LoginRole[$k];
            }*/


            //dump($last_array);exit;


            foreach ($linestatus as $key => $value) {
                $linestatus[$key]['time'] = substr($value['LogTime'],0, 11);
                $linestatus[$key]['db_id'] = $value['serverID'];
                $linestatus[$key]['num'] = $value['OnLinePlayerNum'];
                unset($linestatus[$key]['OnLinePlayerNum']);
                unset($linestatus[$key]['serverID']);
                $linestatus[$key]['f_time'] = substr($linestatus[$key]['LogTime'], 11, 5);
            }

            $model = D('Playeronline');
            $model->onlineadd($linestatus);
        }
    function sendSms($phone,$code){

        // 基于TP3.2开发

        //引进阿里的配置文件

        Vendor ('api_sdk.vendor.autoload');


        // 加载区域结点配置
        \Aliyun\Core\Config::load();
        // 初始化用户Profile实例
        $profile = \Aliyun\Core\Profile\DefaultProfile::getProfile(C('ALI_SMS.REGION'), C('cfg_smssid'), C('cfg_smstoken'));


        // 增加服务结点
        \Aliyun\Core\Profile\DefaultProfile::addEndpoint(C('ALI_SMS.END_POINT_NAME'), C('ALI_SMS.REGION'), C('ALI_SMS.PRODUCT'), C('ALI_SMS.DOMAIN'));
        // 初始化AcsClient用于发起请求
        $acsClient = new \Aliyun\Core\DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new \Aliyun\Api\Sms\Request\V20170525\SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称
        $request->setSignName(C('cfg_smsname'));
        // 必填，设置模板CODE
        $request->setTemplateCode('SMS_122281847');
        $params = array(
            'code' => $code
        );
        // 可选，设置模板参数
        $request->setTemplateParam(json_encode($params));
        // 可选，设置流水号
        //if($outId) {
        //    $request->setOutId($outId);
        //}
        // 发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        // 打印请求结果
        // var_dump($acsResponse);
        return $acsResponse;
    }


    //config配置文件中要写上参数




  
    
}