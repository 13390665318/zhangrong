<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 下午 2:42
 */

namespace Api\Controller;


class SignController extends BaseController
{
    public function sign()
    {
        $need_params = array('game_user_id', 'game_id', 'clothes', 'mobile_type');
        $optional_params = array('mobile_model', 'source', 'game_user_name');
        $data = get_param($need_params, $optional_params);
        //   var_dump($data);exit;
        //判断游戏是否添加
        $game_id = $data["game_id"];
        $Rus = D("game")->where("game_id='$game_id'")->find();
        if ($Rus == null) {
            echo_error(1011, "该游戏尚未入库，请联系后台管理人员");
        } else {
            if ($Rus["status" == 0]) {
                echo_error(1012, "该游戏已经下线");
            } else {
                // 判断区/服是否添加
                $db_id = $data["clothes"];
                $game_name = $Rus["game_name"];
                $Kus = D("db")->where("game_id=$game_id and db_id=$db_id")->find();

                if ($Kus == null) {
                    echo_error(1013, "该产品区\服尚未增加，请联系后台管理人员");
                } else {
                    $game_user_id = $data["game_user_id"];
                    $Aus = D("user")->where("game_user_id=$game_user_id")->find();

                    if ($Aus == null) {
                        // 新增注册 登录
                        $arr = array();
                        $arr["game_user_id"] = $data["game_user_id"];
                        $arr["game_id"] = $data["game_id"];
                        $arr["game_name"] = $game_name;
                        $arr["db_id"] = $db_id;
                        $arr["mobile_type"] = $data["mobile_type"];
                        $arr["mobile_model"] = $data["mobile_model"];
                        $arr["register_time"] = date("Y-m-d H:i:s", time());
                        $arr["source"] = $data["source"];
                        $arr["status"] = 1;
                        $arr["num"] = 1;
                        $arr["level"] = 1;
                        $arr["count_num"] = 1;
                        $arr["end_time"] = date("Y-m-d H:i:s", time());
                        $arr["user_day"] = 1;
                        $arr["user_num"] = 1;
                        $arr["user_time"] = -1;
                        $arr["game_user_name"] = $data["game_user_name"];

                        $ru = D("user")->add($arr);
                        if ($ru != null) {
                            // 默认 登录
                            $str["game_user_id"] = $data["game_user_id"];
                            $str["user_id"] = $ru;
                            $str["game_id"] = $data["game_id"];
                            $str["game_name"] = $game_name;
                            $str["db_id"] = $db_id;
                            $str["source"] = $data["source"];
                            $str["mobile_model"] = $data["mobile_model"];
                            $str["ip"]=$_SERVER['REMOTE_ADDR'];
                            $str["start_time"] = date("Y-m-d H:i:s", time());
                            $str["time"] = date("Y-m-d H:i:s", time());
                            $r = D("sign")->add($str);
                            // 记录 时段操作
                            $rus["game_name"] = $game_name;
                            $rus["game_id"] = $data["game_id"];
                            $rus["db_id"] = $db_id;
                            $rus["f_time"] = date("H:00", time());
                            $rus["time"] = date("Y-m-d", time());
                            $rs = D("period")->where($rus)->find();
                            if ($rs == null) {
                                // 新增
                                $rus["num"] = 1;
                                $rus["user_num"] = 1;
                                $rk = D("period")->add($rus);
                            } else {
                                //修改
                                $period_id = $rs["period_id"];
                                $ars["num"] = $rs["num"] + 1;
                                $ars["user_num"] = $rs["user_num"] + 1;
                                $rsk = D("period")->where("period_id=$period_id")->save($ars);
                            }
                            
                            $list["list"] = "success";
                            $list["sign_id"] = $r;
                            echo_success($list);
                        } else {
                            echo_error(1003, "数据库插入失败，请联系后台工程师");
                        }
                    } else {

                        // 登录
                        $arr = array();
                        $arr["game_user_id"] = $data["game_user_id"];
                        $game_user_id = $data["game_user_id"];
                        $rs = D("user")->where("game_user_id=$game_user_id")->find();
                        $arr["user_id"] = $rs["user_id"];
                        $arr["game_name"] = $game_name;
                        $arr["game_id"] = $data["game_id"];
                        $arr["db_id"] = $db_id;
                        $arr["mobile_type"] = $data["mobile_type"];
                        $arr["mobile_model"] = $data["mobile_model"];
                        $arr["ip"]=$_SERVER['REMOTE_ADDR'];
                        //开始时间
                        $arr["start_time"] = date("Y-m-d H:i:s", time());
                        $arr["time"] = date("Y-m-d H:i:s", time());
                        $arr["source"] = $data["source"];
                        $ru = D("sign")->add($arr);
                        if ($ru != null) {
                            // 更新登录次数  连续登录次数
                            $user_id = $rs["user_id"];
                            $str["num"] = $rs["num"] + 1;
                            $str["user_day"] = $rs["user_day"] + 1;
                            $str["user_num"] = $rs["user_num"] + 1;
                            $end_time = $rs["end_time"];
                            $dd_time = date("Y-m-d", strtotime("-1 day"));
                            if ($end_time == $dd_time) {
                                $str["end_time"] = date("Y-m-d", time());
                                $str["count_num"] = $rs["count_num"] + 1;
                            } else {
                                $str["end_time"] = date("Y-m-d 23:59:59", time());
                                $str["count_num"] = 1;
                            }
                            $r = D("user")->where("user_id=$user_id")->save($str);
                            //更新时段内记录
                            $rus["game_name"] = $game_name;
                            $rus["game_id"] = $data["game_id"];
                            $rus["db_id"] = $db_id;
                            $rus["f_time"] = date("H:00", time());
                            $rus["time"] = date("Y-m-d", time());
                            $rs = D("period")->where($rus)->find();
                            if ($rs == null) {
                                // 新增
                                $rus["num"] = 1;
                                $rus["user_num"] = 0;
                                $rk = D("period")->add($rus);
                            } else {
                                //修改
                                $period_id = $rs["period_id"];
                                $ars["num"] = $rs["num"] + 1;

                                $rsk = D("period")->where("period_id=$period_id")->save($ars);
                            }

                            $list["list"] = "success，登录成功";
                            $list["sign_id"] = $ru;
                            echo_success($list);
                        } else {
                            echo_error(1002, "登录失败");
                        }

                    }
                }
            }
        }


    }

    public function updatename()
    {
        $need_params = array('game_user_id', 'game_id', 'clothes', 'game_user_name');
        $optional_params = array();
        $data = get_param($need_params, $optional_params);
        $game_id = $data["game_id"];
        $Rus = D("game")->where("game_id='$game_id'")->find();
        if ($Rus==null)
        {
            echo_error(1011, "该游戏尚未入库，请联系后台管理人员");
        } else{
            if ($Rus["status" == 0]) {
                echo_error(1012, "该游戏已经下线");
            } else {
                // 判断区/服是否添加
                $db_id = $data["clothes"];

                $Kus = D("db")->where("game_id=$game_id and db_id='$db_id'")->find();
                if ($Kus == null) {
                    echo_error(1013, "该产品区\服尚未增加，请联系后台管理人员");
                } else {
                    $game_user_id=$data["game_user_id"];
                    $Lus=D("user")->where("game_user_id=$game_user_id")->find();
                    if($Lus==null){
                        echo_error(1009, "	用户不存在");
                    }else{
                        $arr["game_user_name"]=$data["game_user_name"];
                        $Lus=D("user")->where("game_user_id=$game_user_id")->save($arr);
                        if($Lus==1){
                            $list["list"]="修改成功,success";
                            echo_success($list);
                        }else{
                            echo_error(1,"	接口奔溃，请联系后台程序猿");
                        }
                    }
                }
            }
        }
    }



}