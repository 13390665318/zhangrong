<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/12 0012
 * Time: 上午 11:07
 */

namespace Admin\Controller;


class RlogController extends BaseController
{
    public function index(){
        $M=D("rlog");
        $count      = $M->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $list = $M->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign("list",$list);// 赋值数据集
        $this->assign("page",$show);// 赋值分页输出
        $this->display();
    }
}