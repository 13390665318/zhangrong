<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 下午 1:53
 */

namespace Home\Controller;


class SysnoticeController extends BaseController
{
    public function index(){
        $count      =D("sysnotice")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("sysnotice")->order("sysnotice_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        //   var_dump($arr);
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if(isset($_SESSION["game_id"])) {
            $game_id = $_SESSION["game_id"];
        } else {
            $game_id = 1;
        }
        if(isset($_POST["sub"])){
            $data["begin_clothes"]=I("post.begin_clothes");
            $data["end_clothes"]=I("post.end_clothes");
            $begin_clothes=I("post.begin_clothes");
            $end_clothes=I("post.end_clothes");
            $clostu=D("db")->where("game_id=$game_id and clothes_num>=$begin_clothes and clothes_num<=$end_clothes ")->order("db_id asc")->select();
            if($clostu==null){
                $stu=0; // 全服
            }else{
                $stu=null;
                for($i=0;$i<count($clostu);$i++){
                    $stu=$clostu[$i]["db_id"].','.$stu;
                }
            }
            $data["clothes"]=$stu;
            $data["type"]=I("post.type");
            $data["begin_time"]=I("post.begin_time");
            $data["num"]=I("post.num");
            $data["systime"]=I("post.systime");
            $data["content"]=I("post.content");
            $data["status"]=0;
            $data["time"]=date("Y-m-d H:i:s",time());
            //  var_dump($data);exit;

            $rus=D("sysnotice")->add($data);
            if($rus !=null){
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$russ["name"];
                $Rlog["account"]=$russ["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="增加id为".$rus."跑马灯";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Sysnotice/index"));
            }else{
                $this->error("新增失败", U("Sysnotice/add"));
            }
        }else{

                $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
                $this->assign("arr", $clostu);
                $this->display();

        }
    }
    public function edit(){
        if (isset($_SESSION["game_id"]) && isset($_SESSION["game_name"])) {
            $game_id = $_SESSION["game_id"];
            $game_name = $_SESSION["game_name"];
            $mobile_type = $_SESSION["mobile_type"];
        } else {
            $str = D("game")->where("game_id=1")->find();
            $game_id = 1;
            $game_name = $str["game_name"];
            $mobile_type = "Andriod";
        }
        if(isset($_POST["sysnotice_id"])){
            $sysnotice_id=I("post.sysnotice_id");
            $data["begin_clothes"]=I("post.begin_clothes");
            $data["end_clothes"]=I("post.end_clothes");
            $begin_clothes=I("post.begin_clothes");
            $end_clothes=I("post.end_clothes");
            $clostu=D("db")->where("game_id=$game_id and clothes_num>=$begin_clothes and clothes_num<=$end_clothes ")->order("db_id asc")->select();
            if($clostu==null){
                $stu=0; // 全服
            }else{
                $stu=null;
                for($i=0;$i<count($clostu);$i++){
                    $stu=$clostu[$i]["db_id"].','.$stu;
                }
            }
            $data["clothes"]=$stu;
            $data["type"]=I("post.type");
            $data["begin_time"]=I("post.begin_time");
            $data["num"]=I("post.num");
            $data["systime"]=I("post.systime");
            $data["content"]=I("post.content");
            $data["status"]=0;

            $rus=D("sysnotice")->where("sysnotice_id=$sysnotice_id")->save($data);;
            if($rus ==1){
                //  $this->redirect('Reward/index');
                $userid=$_SESSION["userID"];
                $russ=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$russ["name"];
                $Rlog["account"]=$russ["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="修改id为".$sysnotice_id."的跑马灯";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Sysnotice/index"));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("修改失败", U("Sysnotice/edit",array("id"=>$sysnotice_id)));
            }
        }else if(isset($_GET["id"])){

                $sysnotice_id = I("get.id");
                $arr = D("sysnotice")->where("sysnotice_id=$sysnotice_id")->find();
                $this->assign("arr", $arr);
                $this->display();


        }

    }
    public function del(){

        if(isset($_GET["ids"])){

                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
                $M = D("sysnotice"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $replay_id = $str[$i];
                    if ($replay_id != null) {
                        $num = $M->where("sysnotice_id=$replay_id")->delete();
                        if ($num == 1) {
                            $userid=$_SESSION["userID"];
                            $russ=D('admin')->where("$userid=$userid")->find();
                            $Rlog["user"]=$russ["name"];
                            $Rlog["account"]=$russ["user_name"];
                            $Rlog["ip"]=get_client_ip();
                            $Rlog["doc"]="删除id为".$replay_id."的跑马灯";
                            $Rlog["time"]=date("Y-m-d H:i:s",time());
                            $r=D("rlog")->add($Rlog);
                            $nums = 1;
                        } else {
                            $nums = 0;
                        }
                    }
                }
                echo $nums;
            }
    }

    // 发送跑马灯
    public  function bobao(){
        if(isset($_GET["sysnotice_id"])){
            $sysnotice_id=I("get.sysnotice_id");
            $M = D("sysnotice");
            $rus = $M->where("sysnotice_id=$sysnotice_id")->find();
            var_dump($rus);
        }
    }

}