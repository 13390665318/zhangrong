<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/22 0022
 * Time: 22:56
 */

namespace Home\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class AuthController extends BaseController
{
        public function index(){
            $count      = D("admin_auth_type")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
            $show       = $Page->show();// 分页显示输出
            $list = D("admin_auth_type")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            for($i=0;$i<count($list);$i++){
                $auth=$list[$i]["auth"];
                $arr=array_filter(explode(",",$auth));
                $stu=null;
                for($j=0;$j<count($arr);$j++){
                    $id=$arr[$j];
                    $rus=D("admin_auth_rule")->where("id=$id")->find();
                    $stu=$rus["title"].",".$stu;
                }

                $list[$i]["auth_name"]=$stu;
            }
            $this->assign("arr",$list);// 赋值数据集
            $this->assign("page",$show);// 赋值分页输出
            $this->display();
        }
    public function add(){
        if(isset($_POST["sub"])){
            $data["name"]=I("post.name");
            $stu=null;
            for($i=2;$i<170;$i++){
                if(isset($_POST["auth".$i])){
                    $stu=$_POST["auth".$i].','.$stu;
                }
            }
        //    var_dump($stu);
            $data["auth"]=$stu;
      //   var_dump($data);exit;
            $num=D("admin_auth_type")->add($data);
            if($num !=null){
                $this->success("添加成功",U("Auth/index"));
            }else{
                $this->error("添加失败", U("Auth/add"));
            }
        }else{
            $data=D("admin_auth_rule")->where("type!=-1 and status!=0")->select();
            $this->assign("data",$data);// 赋值分页输出
            $this->display();
        }
    }
    public function del(){
        if(isset($_GET["id"])){
            $id=I("get.id");

                $num=D("admin_auth_type")->where("id=$id")->delete();
                if($num ==1){
                    $this->success("删除成功",U("Auth/index"));
                }else{
                    $this->error("删除失败", U("Auth/index"));
                }


        }
    }
    public  function edit(){
        if(isset($_GET["id"])){
            $id=I("get.id");
            $list = D("admin_auth_type")->where("id=$id")->find();
            $data=D("admin_auth_rule")->where("type!=-1 and status!=0")->select();
            $this->assign("data",$data);// 赋值分页输出
            $this->assign("list",$list);// 赋值分页输出
            $auth = $list["auth"];
            $arr = explode(',', $auth);
            for ($i = 0; $i < count($arr); $i++) {
                $arrs[$i]["num"] = $arr[$i];
            }
            $rus = json_encode($arrs);

            $this->assign('rus', $rus);
            $this->display();

        }else if(isset($_POST["id"])){
            $id=I("post.id");
            $data["name"]=I("post.name");
            $stu=null;
            for($i=1;$i<170;$i++){
                if(isset($_POST["auth".$i])){
                    $stu=$_POST["auth".$i].','.$stu;
                }
            }
            $data["auth"]=$stu;

            $num=D("admin_auth_type")->where("id=$id")->save($data);
            if($num ==1){
                $this->success("修改成功",U("Auth/index"));
            }else{
                $this->error("修改失败", U("Auth/edit",array("id"=>$id)));
            }
        }
    }
}