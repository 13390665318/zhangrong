<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8 0008
 * Time: 下午 5:04
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");

class LinetimeController extends BaseController
{
    public function index()
    {

        // var_dump($db_id);exit;
        $game_id = 2;
        $clostu = D("db")->where("game_id=$game_id")->order("db_id desc")->select();
        $this->assign("clostu", $clostu);

        // 图标 默认 最新服
        if (isset($_GET["db_id"])) {
            $db_id = I("get.db_id");
            $_SESSION["db_id"] = $db_id;
        } else {
            $db_id = $clostu[0]["db_id"];
            $_SESSION["db_id"] = $db_id;
        }
        $nowtime = date("Y-m-d H:i:s", time());
        $this->assign("db_id", $db_id);
        if (isset($_GET["stime"]) && isset($_GET["etime"])) {
            $stime = I("get.stime");
            $etime = I("get.etime");
        } else {
            $stime = date("Y-m-d 00:00:00 ", strtotime('-6day'));
            $etime = date("Y-m-d H:i:s", time());
        }

        $days = count_days($stime, $etime);

        $this->assign('stime', $stime);
        $this->assign('etime', $etime);

        //通过日志获得区间时间内的在线时长分布
        for ($i = 0; $i <= $days; $i++) {
            $stime = date('Y-m-d 00:00:00', strtotime("-$i day", strtotime($etime)));
            $endtime = date('Y-m-d 23:59:59', strtotime("-$i day", strtotime($etime)));
            //dump($endtime);
            $model = D('Logoutlog');
            $arr1 = D('Logoutlog')->where("logoutlog.LogTime>='$stime' and logoutlog.LogTime<='$endtime' and loginlog.LogTime>='$stime' and loginlog.LogTime<='$endtime'")->join('left join loginlog on loginlog.role_id=logoutlog.role_id')->order('logout_time desc,login_time desc')->field('Logoutlog.role_id,Logoutlog.TodayOnline_time,logout_time,login_time')->buildSql();

            $onlinetime = $model->table($arr1 . 'a')->group('role_id')->select();
            $endtime1 = substr($endtime, 0, 10);

            foreach ($onlinetime as $key => $value) {
                if ($value['login_time'] > $value['logout_time']) {
                    if ($endtime1 == date('Y-m-d')) {
                        $onlinetime[$key]['TodayOnline_time'] = $value['TodayOnline_time'] + ((time()) - (strtotime($value['login_time'])));
                    } else {
                        $onlinetime[$key]['TodayOnline_time'] = $value['TodayOnline_time'] + ((strtotime($endtime)) - (strtotime($value['login_time'])));
                    }
                }
            }
            //取值在线区段计算占比
            $onlinetime = array_column($onlinetime, 'TodayOnline_time');
            $onlnum = count($onlinetime);
            $data[$i]['num1'] = 0;
            $data[$i]['num2'] = 0;
            $data[$i]['num3'] = 0;
            $data[$i]['num4'] = 0;
            $data[$i]['num5'] = 0;
            $data[$i]['num6'] = 0;
            foreach ($onlinetime as $key => $value) {
                if ($value <= 1800) {
                    $data[$i]['num1']++;
                } elseif ($value <= 3600 && $value > 1800) {
                    $data[$i]['num2']++;
                } elseif ($value <= 7200 && $value > 3600) {
                    $data[$i]['num3']++;
                } elseif ($value <= 14400 && $value > 7200) {
                    $data[$i]['num4']++;
                } elseif ($value <= 21600 && $value > 14400) {
                    $data[$i]['num5']++;
                } elseif ($value > 21600) {
                    $data[$i]['num6']++;
                }
            }
            $data[$i]['num1s'] = round($data[$i]["num1"] / $onlnum, 4) * 100;
            $data[$i]['num2s'] = round($data[$i]["num2"] / $onlnum, 4) * 100;
            $data[$i]['num3s'] = round($data[$i]["num3"] / $onlnum, 4) * 100;
            $data[$i]['num4s'] = round($data[$i]["num4"] / $onlnum, 4) * 100;
            $data[$i]['num5s'] = round($data[$i]["num5"] / $onlnum, 4) * 100;
            $data[$i]['num6s'] = round($data[$i]["num6"] / $onlnum, 4) * 100;
            $data[$i]['date'] = substr($stime, 0, 10);
        }
        //dump($data);exit;


        /*$model=D('Logoutlog');
        $arr1=D('Logoutlog')->where("LogTime>='$stime' and LogTime<='$etime'")->order('LogTime desc')->field('role_id,TodayOnline_time,LogTime')->buildSql();
        $onlinetime = $model->table($arr1 . 'a')->group('role_id')->select();

        $onlinetime=array_column($onlinetime,'TodayOnline_time');
        $onlnum = count($onlinetime);
        $num['num1'] = 0;
        $num['num2'] = 0;
        $num['num3'] = 0;
        $num['num4'] = 0;
        $num['num5'] = 0;
        $num['num6'] = 0;


        foreach ($onlinetime as $key => $value) {
            if ($value <= 1800) {
                $num['num1']++;
            } elseif ($value <= 3600 && $value > 1800) {
                $num['num2']++;
            } elseif ($value <= 7200 && $value > 3600) {
                $num['num3']++;
            } elseif ($value <= 14400 && $value > 7200) {
                $num['num4']++;
            } elseif ($value <= 21600 && $value > 14400) {
                $num['num5']++;
            } elseif ($value > 21600) {
                $num['num6']++;
            }
        }



        $num['num1s'] = round($num["num1"] / $onlnum, 4) * 100;
        $num['num2s'] = round($num["num2"] / $onlnum, 4) * 100;
        $num['num3s'] = round($num["num3"] / $onlnum, 4) * 100;
        $num['num4s'] = round($num["num4"] / $onlnum, 4) * 100;
        $num['num5s'] = round($num["num5"] / $onlnum, 4) * 100;
        $num['num6s'] = round($num["num6"] / $onlnum, 4) * 100;*/

        $this->assign('data', $data);


        $this->display();
    }//

    public function exl()
    {
        header("Content-Type:text/html; charset=utf-8");
        Vendor('PHPExcel.PHPExcel');

        $objPHPExcel = new \PHPExcel();
        $game_id = 1;
        $db_id = I("get.db_id");
        $stime = I("get.stime");
        $etime = I("get.etime");
        $num = count_days($stime, $etime);
        $Stime = null;
        for ($i = 0; $i <= $num; $i++) {

            $arr[$i]["time"] = date('Y-m-d', strtotime("-$i day", strtotime($etime)));
            $arr[$i]["time1"] = date('Y-m-d 00:00:00', strtotime("-$i day", strtotime($etime)));
            $arr[$i]["time2"] = date('Y-m-d 23:59:59', strtotime("-$i day", strtotime($etime)));
        }
        $connection = db($game_id, $db_id);
        $Userbase = M('San_userbase', '', $connection);

        for ($i = 0; $i < count($arr); $i++) {
            $data[$i]["time"] = $arr[$i]["time"];
            $start_time = $arr[$i]["time1"];
            $end_time = $arr[$i]["time2"];
            $begin_time = date('Y-m-d 00:00:00', strtotime("+7day", strtotime($arr[$i]["time"])));
            $stu = $Userbase->where("regtime>='$start_time' and regtime<='$end_time' and lastupdtime >= '$begin_time'")->order("uid desc")->select();
            //判断是否是回流玩家
            $data[$i]["num"] = 0;
            for ($j = 0; $j < count($stu); $j++) {
                $uid = $stu[$j]["uid"];
                $rus = D("sign")->where("game_user_id=$uid")->order("sign_id desc")->limit(0, 2)->select();
                if (count_days($rus[0]["start_time"], $rus[1]["start_time"]) >= 7) {
                    //回流用户
                    $data[$i]["num"] = $data[$i]["num"] + 1;
                }
            }

        }
        //设置excel列名
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '回流玩家');


        //把数据循环写入excel中
        $i = 1;
        foreach ($data as $key => $value) {
            $key += 2;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $key, $value["time"]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $key, $value["num"]);


        }
        //导出代码
        $name = time();
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


    }
}