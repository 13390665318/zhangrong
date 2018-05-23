<?php
namespace Doc\Controller;

use Think\Controller;
class ForumController extends Controller
{
    public function  index(){
        if(isset($_GET["risk_id"])){
            $risk_id=I("get.risk_id");
            $user_id=I("get.user_id");
            // 风评
            $ru=D("risk")->where("risk_id=$risk_id")->find();
            // 风评评
            $data=D("comment")->where("risk_id=$risk_id and type=0")->order("id desc")->select();
            // 查找 评论的评论的评论
            for($i=0;$i<count($data);$i++){
                $id=$data[$i]["id"];
                $str[$i]=D("comment")->where("risk_id=$id and type=1")->select();
            }
	  // 判断是否已经点赞
            $rus=D("risk_zan")->where("risk_id=$risk_id and user_id=$user_id")->find();
            if($rus!=null){
                $status=1;
            }else{
                $status=0;
            }
            $this->assign("status",$status);
            $this->assign("str",$str);
            $this->assign("data",$data);
            $this->assign("risk_id",$risk_id);
            $this->assign("user_id",$user_id);
            $this->assign("ru",$ru);
            $this->display();
        }
    }
    // 新增评论
    public function add(){
        if(isset($_GET["risk_id"])){
            $risk_id=I("get.risk_id");
            $user_id=I("get.user_id");
            $content=I("get.content");
            $list=D("user")->where("user_id=$user_id")->find();
            $arr=array();
            $arr["user_id"]=$user_id;
            $arr["user_logo"]=$list["user_logo"];
            $arr["user_name"]=$list["username"];
            $arr["risk_id"]=$risk_id;
            $arr["content"]=$content;
            $arr["time"]=date('Y-m-d H:i:s',time());
            $arr["type"]=0;
            $num=M('comment')->add($arr);
            if($num !=0){
                // 评论次数
                $ru=D("risk")->where("risk_id=$risk_id")->find();
                $company_id=$ru["company_id"];
                $ru=D("company")->where("company_id=$company_id")->find();
                $str["comments"]=(int)$ru["comments"]+1;
                $r=D("company")->where("company_id=$company_id")->save($str);
               
               echo 1;
            }else{
                echo -1;
            }
        }
    }
    /**
     * 回复评论的评论
     * 
     */
    public function adds(){
        if(isset($_GET["risk_id"])){
          $risk_id=I("get.risk_id");
            $user_id=I("get.user_id");
            $content=I("get.content");
            $list=D("user")->where("user_id=$user_id")->find();
            $arr=array();
            $arr["user_id"]=$user_id;
            $arr["user_logo"]=$list["user_logo"];
            $arr["user_name"]=$list["username"];
            $arr["risk_id"]=$risk_id;
            $arr["content"]=$content;
            $arr["time"]=date('Y-m-d H:i:s',time());
            $arr["type"]=1;
            $num=M('comment')->add($arr);
		
         if($num !=0){
                // 评论次数
        /**        $ru=D("risk")->where("risk_id=$risk_id")->find();
                $company_id=$ru["company_id"];
                if($company_id==null){
                    $rus=D("comment")->where("risk_id=$risk_id")->find();
                    //$risk_id=$rus["risk_id"];
                    $company_id=$rus["company_id"];
                    $ru=D("company")->where("company_id=$company_id")->find();
                    $str["comments"]=(int)$ru["comments"]+1;
                    $r=D("company")->where("company_id=$company_id")->save($str);
                }else{
                    $ru=D("company")->where("company_id=$company_id")->find();
                    $str["comments"]=(int)$ru["comments"]+1;
                    $r=D("company")->where("company_id=$company_id")->save($str);
                }**/
                
                 
                echo 1;
           	 }else{
                echo -1;
             }

        }
    }
}

?>