<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/24 0024
 * Time: 下午 4:10
 */

namespace Admin\Controller;


class ChannelController extends BaseController
{
    public function index(){
        $count      =D("qudao")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("qudao")->order("cid asc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }

}