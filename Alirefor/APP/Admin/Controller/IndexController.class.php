<?php
namespace Admin\Controller;

use Think\Controller;
class IndexController extends BaseController {
    /**
     * 加载头部header
     */

    public  function header(){

      $list=D("game")->where("status=1")->select();

        $this->assign("list",$list);
         $this->display();
    }
    public function save(){
        if (isset($_GET)){
            $game_id=I("get.game_id");
            $mobile_type=I("get.mobile_type");
            $ar=D("game")->where("game_id=$game_id")->find();
            $game_name=$ar["game_name"];
            session('game_id',null);
            session('game_name',null);
            session('mobile_type',null);
            $_SESSION["game_id"]=$game_id;
            $_SESSION["game_name"]=$game_name;
            $_SESSION["mobile_type"]=$mobile_type;
            if($_SESSION["game_id"]!='' && $_SESSION["game_name"]!=''){
                    echo "success";
            }else{
                echo "error";
            }

        }
    }
      public function menu(){

       $userID=$_SESSION["userID"];
    
        $data=D("admin")->where("id=$userID")->find();
        $auth=$data["auth"];
        $stu= explode(',',$auth);
        $stu=array_filter($stu);
  //var_dump($stu);
        for($i=0;$i<count($stu);$i++){
            $id=$stu[$i];
            if($id!=1) {
                $ru[$i] = D("admin_auth_rule")->where("id=$id and status=1 ")->find();
		if($ru[$i]!=null){
                $arr[$i] = $ru[$i]["type"];}
            }
        }
//var_dump($arr);exit;
        $arr=array_unique($arr);
        $arr=array_values($arr);
 // 
        for($i=0;$i<count($arr);$i++){
            $id=$arr[$i];
            $rus[$i]=D("admin_auth_type")->where("type_id=$id")->find();
        }
     //   var_dump($rus);
        $this->assign("ru",$ru);
        $this->assign("rus",$rus);
        $this->display();

    }
    /**
     * 首页欢迎
     */
    public function main(){

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
            if($_SESSION["game_id"]=='' && $_SESSION["game_name"]=='' && $_SESSION["mobile_type"]==''){
                $list=D("game")->where("status=1")->select();
                //存储 信息
                $_SESSION["game_id"]=$list[0]["game_id"];
                $_SESSION["game_name"]=$list[0]["game_name"];
                $_SESSION["mobile_type"]="Android";
            }

        $this->assign("list",$stu);// 赋值数据集
        $this->assign("page",$show);// 赋值分页输出
	}
        $this->display();
    }
    /**
     * 首页
     */
    public function index(){


         $this->display(); // 输出模板
        
        
    }
    public function amend2(){
			$password=I("get.password");
			$password1=I("get.password1");
			$user_name=$_SESSION["username"];
			$data["password"]=$password1;
			$ru=D("admin")->where("user_name='$user_name'")->save($data);
			
			if($ru==1){
			echo 1;
			}else{
			echo 0;
			}
		}


}