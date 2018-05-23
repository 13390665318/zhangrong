<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16 0016
 * Time: 上午 11:37
 */

namespace Home\Controller;


class LogController extends  BaseController
{
    public function index(){
	// $redis = new \Redis();
      //  $redis->connect("127.0.0.1","6379");
      //  $redis->set('test','hello world!');
//$redis->delete(14);
    //   var_dump($redis->keys("notice"."*"));
    //    exit;

        $name=$_SESSION["username"];
        if($name=="admin"){
            $arr["name"]=null;
        }else{
            $arr["name"]=$name;
        }
        $arr=array_filter($arr);
        $M = D("log"); // 实例化User对象
        $count      = $M->where($arr)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $role=$_SESSION["role"];
        if($role==1){
            $stu= $M->where($arr)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign("list",$stu);// 赋值数据集
            $this->assign("page",$show);// 赋值分页输出
        }
        $this->display();
    }
}