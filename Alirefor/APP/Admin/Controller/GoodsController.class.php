<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 5:45
 */

namespace Admin\Controller;


class GoodsController extends BaseController
{
    public function index(){
        // 类别
        if(isset($_GET["itemname"])){
            $itemname=I("get.itemname");
                $where['itemname']  = array('like', "%$itemname%");
        }
        if(isset($_GET["itemid"])){
            $where["itemid"]=I("get.itemid");
        }
        $con=array_filter($where);
      
        $count      = D("goods")->where($con)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("goods")->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }

}