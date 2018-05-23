<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 4:59
 * 91000003   体力
 */

namespace Home\Controller;


class FubenconController extends BaseController
{
    public function index()
    {

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


        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
        } else {
            $stime = date("Y-m-d",time());
        }
        $this->assign("db_id", $db_id);
        $this->assign('Stime', $stime);
        $start_time = date('Y-m-d 00:00:00', strtotime($stime));
        $end_time = date('Y-m-d 23:59:59', strtotime($stime));
        $signnum = D('sign')->field('game_user_id')->where("start_time>='$start_time' and start_time<='$end_time'")->group('game_user_id')->select();
        $connection = db2($game_id, $db_id);
        $model = M('t_roles', '', $connection);
        $fubencon = D('fubencon')->select();

        foreach ($fubencon as $key => $value) {


            //调取游戏内主线任务信息

            $where['maintaskid'] = array('egt', $value['maintaskid']);
            $where['level'] = array('egt', $value['level']);
            $where['changelifecount'] = array('egt', $value['role_ChangeLife']);
            $fubencon[$key]['con'] = $model->field('rid')->where($where)->select();

            //调取游戏符合参加副本的玩家

            $fubencon[$key]['scon'] = array_intersect(array_column($fubencon[$key]['con'], 'rid'), array_column($signnum, 'game_user_id'));

            //取得当前在线玩家中符合条件的玩家

            $fubencon[$key]['snum'] = count($fubencon[$key]['scon']);
            $ru['LogTime'] = array(array('gt', $start_time), array('lt', $end_time));
            $ru['fuben_name'] = $value['fuben_name'];
            $fubencon[$key]['fb'] = D('Joinfuben')->field('fuben_name,role_id')->where($ru)->group('role_id')->select();
            //$fubencon[$key]['fb']=D('Joinfuben')->field('fuben_name,role_id')->where("fuben_name='$value[fuben_name]'")->group('role_id')->select();
            $fubencon[$key]['fbnum'] = count($fubencon[$key]['fb']);

            $fubencon[$key]['lv'] = round($fubencon[$key]['fbnum'] / $fubencon[$key]['snum'], 4) * 100;
        }
        $this->assign('fubencon', $fubencon);


        $this->display();


    }

    public function add()
    {
        if(IS_POST){
            $data=I('post.');

            $model=$result=D('Fubencon');
            $model->fubenadd($data);

            if($result==1){
                $this->success("添加成功", U("Fubencon/index"));
            }else {
                $this->error("添加失败", U("Fubencon/index"));
            }
        }else{
            $this->display();
        }

    }


}