<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/22 0022
 * Time: 上午 10:04
 */

namespace Doc\Controller;


use Think\Controller;

class ShowController extends Controller
{
    public function index(){
        if(isset($_GET["id"])){
            $notice_id=I("get.id");
            $Rus=D("notice")->where("notice_id=$notice_id")->find();
            $this->assign("arr",$Rus);
            $this->display();
        }
    }
}