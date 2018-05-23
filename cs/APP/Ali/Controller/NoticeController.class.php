<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 上午 11:14
 */
namespace Ali\Controller;
use Think\Controller;

class NoticeController extends  Controller
{
        public function set(){
            if(isset($_POST["notice_id"])){
                $notice_id=I("post.notice_id");
                $r=D("notice")->where("notice_id=$notice_id")->find();
                if($r==null){
                    // 新增
                    $data=$_POST;
                    if($data["clothes"]==0){
                        $data["begin_clothes"]=0;
                        $data["end_clothes"]=0;

                    }else{
                        $stu=explode(",",$data["clothes"]);
                        if($stu){
                            $data["begin_clothes"]=array_search(min($stu), $stu);
                            $data["end_clothes"]=array_search(max($stu), $stu);
                        }else{
                            $data["begin_clothes"]=$data["clothes"];
                            $data["end_clothes"]=$data["clothes"];
                        }

                    }
                    $r=D("notice")->add($data);
                    if($r){
                        //  $notice_id="noticeAlirefor_".$r;
                        $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                        $redis = new \Redis();
                        $redis->connect("127.0.0.1","6379");
                        $redis->set("noticeAlirefor_".$notice_id,$value);
                        echo 0;
                    }else{
                        echo -1;
                    }

                }else{
                    // 更新
                    $data=$_POST;
                    $r=D("notice")->where("notice_id=$notice_id")->save($data);
                    if($r){
                        $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                        $redis = new \Redis();
                        $redis->connect("127.0.0.1","6379");
                        $redis->delete("noticeAlirefor_".$notice_id);
                        $redis->set("noticeAlirefor_".$notice_id,$value);
                        echo 0;
                    }else{
                        echo -1;
                    }
                /**    if($data["clothes"]==0){
                        $data["begin_clothes"]=0;
                        $data["end_clothes"]=0;
                        $r=D("notice")->save($data);
                        if($r){
                            $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                            $redis = new \Redis();
                            $redis->connect("127.0.0.1","6379");
                            $redis->delete("noticeAlirefor_".$notice_id);
                            $redis->set("noticeAlirefor_".$notice_id,$value);
                            echo 0;
                        }else{
                            echo -1;
                        }
                    }else{
                        $stu=explode(",",$data["clothes"]);
                        $data["begin_clothes"]=array_search(min($stu), $stu);
                        $data["end_clothes"]=array_search(max($stu), $stu);
                        $r=D("notice")->add($data);
                        if($r){
                           // $notice_id="noticeAlirefor_".$r;
                            $value=json_encode($data,JSON_UNESCAPED_UNICODE);
                            $redis = new \Redis();
                            $redis->connect("127.0.0.1","6379");
                            $redis->delete("noticeAlirefor_".$notice_id);
                            $redis->set("noticeAlirefor_".$notice_id,$value);
                            echo 0;
                        }else{
                            echo -1;
                        }
                    }**/
                }

            }
        }
}