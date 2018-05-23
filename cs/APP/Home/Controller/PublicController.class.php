<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 下午 4:02
 */

namespace Home\Controller;


use Think\Controller;
header ( "Content-type:text/html;charset=utf-8" );
class PublicController extends Controller
{
        public function header(){
        /**    var_dump($_SESSION);
            $userID=$_SESSION["userID"];
            $ru=D("admin")->where("id=$userID")->find();
            $auth = $ru["auth"];
            $list = D("admin_auth_type")->where("id=$auth")->find();
            $auths = $list["auth"];
            $arr = explode(",", $auths);

            for($i=0;$i<count($arr);$i++){
                $auth_id=$arr[$i];
                $data[$i]=D("admin_auth_rule")->where("id=$auth_id and status=1")->find();
            }
            $data=array_filter($data);
            $data=array_values($data);


            $rus=D("admin_auth_rule")->where("type=-1")->select();
            for($i=0;$i<count($rus);$i++){
                for($j=0;$j<count($data);$j++){
                    if($rus[$i]["status"]==$data[$j]["type"]){
                        $stu[$i]=$rus[$i];
                    }
                }
            }
//var_dump($data);
            $this->assign("stu",$stu);
            $this->assign("data",$data);**/
            $this->display();
        }

         public function header2(){
        $this->display();
     }

        public function footer(){
            $this->display();
        }
}