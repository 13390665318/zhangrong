<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 下午 6:22
 */

namespace Home\Controller;


class NoticeController extends BaseController
{
    public function index(){

$redis = new \Redis();
  /** $redis->connect("127.0.0.1","6379");
          //  $redis->set('test','hello world!');
            $notice=$redis->keys("noticeAlirefor"."*");   
var_dump($notice);     
$redis->delete("noticeAlirefor_1727375215883386");   **/        
        $count      =D("notice")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
        $arr=D("notice")->order("priority asc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if (isset($_POST["sub"])){

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

            $data["begin_time"]=I("post.begin_time");
            $data["end_time"]=I("post.end_time");
            $data["title"]=I("post.title");
            $str=I("post.content");
            $qian=array("\t","\n","\r");
            $data["content"]=str_replace($qian, '', $str);
            $data["type"]=I("post.type");
            $data["url"]=I("post.url");
            $data["priority"]=I("post.priority");
            $data["begin_clothes"]=I("post.begin_clothes");
            $data["end_clothes"]=I("post.end_clothes");
            $begin_clothes=I("post.begin_clothes");
            $end_clothes=I("post.end_clothes");
            $clostu=D("db")->where("game_id=$game_id and clothes_num>=$begin_clothes and clothes_num<=$end_clothes ")->order("db_id asc")->select();
            if($clostu==null){
                $stu=null;
            }else{
                $stu=null;
                for($i=0;$i<count($clostu);$i++){
                    $stu=$clostu[$i]["db_id"].','.$stu;
                }
            }
            $data["clothes"]=$stu;
            $data["time"]=date("Y-m-d H:i:s",time());
            $data["status"]=0;
            $data["num"]=0;
            $rus=D("notice")->add($data);
            if($rus !=null){
                //  增加到redis 库
                $notice_id="noticeAlirefor_".$rus;
                $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                $redis = new \Redis();
                $redis->connect("127.0.0.1","6379");
                $redis->set($notice_id,$value);

                $userid=$_SESSION["userID"];
                $ruds=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$ruds["name"];
                $Rlog["account"]=$ruds["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="增加id为".$rus."的系统公告";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                //$this->redirect('Notice/index');
                $this->success("新增成功",U("Notice/index"));
            }else{
                $this->error("新增失败", U("Notice/add"));
            }
        }else{

            // 查找所有区/服
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
            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("clostu", $clostu);
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
        if(isset($_GET["id"])){

            $notice_id = I("get.id");
            $arr = D("notice")->where("notice_id=$notice_id")->find();
            $this->assign("arr", $arr);
            $clothes = $arr["clothes"];
            $data = explode(',', $clothes);
            $data = array_filter($data);
            $data = json_encode($data);
            $this->assign("data", $data);

            $clostu = D("db")->where("game_id=$game_id")->order("db_id asc")->select();
            $this->assign("clostu", $clostu);
            $this->display();


        }elseif(isset($_POST["notice_id"])){
            $notice_id=I("post.notice_id");
            $data["begin_time"]=I("post.begin_time");
            $data["end_time"]=I("post.end_time");
            $data["title"]=I("post.title");
            //  $data["content"]=str_replace(array("/r/n", "/r", "/n"), '', I("post.content"));
            $str=I("post.content");
            $qian=array("\t","\n","\r");
            $data["content"]=str_replace($qian, '', $str);
            $data["url"]=I("post.url");
            $data["priority"]=I("post.priority");
            $data["begin_clothes"]=I("post.begin_clothes");
            $data["end_clothes"]=I("post.end_clothes");
            $begin_clothes=I("post.begin_clothes");
            $end_clothes=I("post.end_clothes");
            $data["type"]=I("post.type");
            $clostu=D("db")->where("game_id=$game_id and clothes_num>='$begin_clothes' and clothes_num<='$end_clothes' ")->order("db_id asc")->select();
//var_dump($data)   ;exit;
            if($clostu==null){
                $stu=null;
            }else{
                $stu=null;
                for($i=0;$i<count($clostu);$i++){
                    $stu=$clostu[$i]["db_id"].','.$stu;
                }
            }
            //var_dump($stu);exit;
            $data["clothes"]=$stu;
            $rus=D("notice")->where("notice_id=$notice_id")->save($data);
            if($rus !=0){
                $data["status"]=0;
                $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                $redis = new \Redis();
                $redis->connect("127.0.0.1","6379");
                $redis->delete("noticeAlirefor_".$notice_id);
                $redis->set("noticeAlirefor_".$notice_id,$value);


                $userid=$_SESSION["userID"];
                $rus=D('admin')->where("$userid=$userid")->find();
                $Rlog["user"]=$rus["name"];
                $Rlog["account"]=$rus["user_name"];
                $Rlog["ip"]=get_client_ip();
                $Rlog["doc"]="修改id为".$notice_id."的系统公告";
                $Rlog["time"]=date("Y-m-d H:i:s",time());
                $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Notice/index"));
            }else{
                $this->error("修改失败", U("Notice/edit",array('id'=>$notice_id)));
            }
        }
    }

    public function nostop(){
        if(isset($_GET["notice_id"])){
            $notice_id=I("get.notice_id");
            $rus=D("notice")->where("notice_id=$notice_id")->find();
            if($rus["type"]!=2){
                $clothes=$rus["clothes"];
                if($clothes==null){
                    //全服
                    $db=D("db")->select();
                }else{
                    //选定服务器
                    $dclothes=explode(",",$clothes);
                    $dclothes=array_filter($dclothes);
                    for($i=0;$i<count($dclothes);$i++){
                        $db[$i]=D("db")->where("db_id=$dclothes[$i]")->find();
                    }

                }
                // var_dump($db);
                for($i=0;$i<count($db);$i++){
                    $ip=$db[$i]["ip"];
                    $port=$db[$i]["db_port"];
                    $type=$rus["type"];
                    $content=$rus["content"];
                    $jsData= json_encode($content,JSON_UNESCAPED_UNICODE);
                    $ruSdata=base64_encode($jsData);
                    $ruSdata = str_replace(array('+','/'),array('-','_'),$ruSdata);
$token=$_SESSION["token"];
                    $md=md5($token);
                    $url="http://$ip:$port/notice?context=$ruSdata&type=$type&token=$md";
                    //$url="http://$ip:$port/notice?context=$ruSdata&type=$type";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $output = curl_exec($ch);
                    curl_close($ch);
                    $a=json_decode($output);
                    if($a->code==0){
                        //改变状态
                        $where["status"]=1;
                        $ru=D("notice")->where("notice_id=$notice_id")->save($where);
                        if($rus==1){
                            $userid=$_SESSION["userID"];
                            $rus=D('admin')->where("$userid=$userid")->find();
                            $Rlog["user"]=$rus["name"];
                            $Rlog["account"]=$rus["user_name"];
                            $Rlog["ip"]=get_client_ip();
                            $Rlog["doc"]="发送id为".$notice_id."公告";
                            $Rlog["time"]=date("Y-m-d H:i:s",time());
                            $r=D("rlog")->add($Rlog);

                        }
                    }
                }
                echo 1;
            }else{
                $type=I("get.type");
                //	var_dump($_GET);//;
                if($type==2){
                    $data["status"]=-1;

                    $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="停用id为".$notice_id."的系统公告";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                }else{
                    $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="播报id为".$notice_id."的系统公告";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                    $data["status"]=0;
                }
                //var_dump($data);EXIT;
                $ru=D("notice")->where("notice_id=$notice_id")->save($data);
                $arr=D("notice")->where("notice_id=$notice_id")->find();
                $value=json_encode($arr,JSON_UNESCAPED_UNICODE);
                $redis = new \Redis();
                $redis->connect("127.0.0.1","6379");
                $redis->delete("noticeAlirefor_".$notice_id);
                $redis->set("noticeAlirefor_".$notice_id,$value);
                if($ru ==1){
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }

    public function del(){
//var_dump($_GET);
        $id=$_SESSION["userID"];
        $rus=D('admin')->where("id=$id")->find();
        $user2=$rus["sysnotice2"];
        if($user2==1) {
            if (isset($_GET["ids"])) {
                $ids = I("get.ids");
                $arr = array();
                $str = explode(',', $ids);
//var_dump($str);
                $M = D("notice"); // 实例化User对象
                for ($i = 0; $i < count($str); $i++) {
                    $id = $str[$i];
                    if ($id != null) {


//echo $user2;exit;
                        $num = $M->where("notice_id=$id")->delete();
                        if ($num == 1) {
                            $redis = new \Redis();
                            $redis->connect("127.0.0.1","6379");
                            $redis->delete("noticeAlirefor_".$id);

                            $userid=$_SESSION["userID"];
                            $rus=D('admin')->where("$userid=$userid")->find();;
                            $Rlog["user"]=$rus["name"];
                            $Rlog["account"]=$rus["user_name"];
                            $Rlog["ip"]=get_client_ip();
                            $Rlog["doc"]="删除id为".$id."的系统公告";
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
        }else{
            echo -2;
        }
    }

}