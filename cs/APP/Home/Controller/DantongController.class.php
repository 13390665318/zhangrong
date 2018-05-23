<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31 0031
 * Time: 上午 11:47
 */

namespace Home\Controller;


class DantongController extends BaseController
{
    public function index(){
        $chepai=I('get.chepai');
        $car=D('oil')->field('baiyou')->order('start_time desc')->where("chepai='$chepai'")->select();
        $time=D('oil')->field('start_time,chepai,beizhu')->order('start_time desc')->where("chepai='$chepai'")->select();
        $this->assign('time',$time[0]['chepai']);
        /*$time=array_column($time,'start_time');
        $beizhu=array_column($time,'beizhu');*/
        $Stime=null;
        foreach ($time as $key =>$value){
            $Stime='"'.$time[$key]['start_time']."(".$time[$key]['beizhu'].")".'"'."," .$Stime;
        }

        $Stime = substr($Stime, 0, strlen($Stime) - 1);


        $this->assign('stu',$Stime);
        $this->assign('car',$car);
        $jsoBj=json_encode($car);
        $this->assign("jsoBj",$jsoBj);
        $this->display();



       }


}