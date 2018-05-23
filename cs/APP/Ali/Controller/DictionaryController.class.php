<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9 0009
 * Time: 上午 10:09
 */

namespace Ali\Controller;


use Think\Controller;

class DictionaryController extends Controller
{
        public function getServerList(){
                if(isset($_POST["ids"])){
                    if($_POST["ids"])  {//echo 12;exit;
                        $ids=I("post.ids");
                        $ids=explode(",",$ids);
                        $id=array_filter($ids);
                        $where=null;
                        for($i=0;$i<count($id);$i++){
                            $name=$id[$i];
                            $where = "db_id = '$name' or "  .$where;
                        }
                     $con=substr($where,0,strlen($where)-3);//echo $con;exit;
                    }
                }
                if(isset($_POST["names"])){
                    if($_POST["names"]){
                        $names=I("post.names");
                        $names=explode(",",$names);
                        $names=array_filter($names);
                        $stu=null;
                        for($i=0;$i<count($names);$i++){
                            $name=$names[$i];
                            $stu = "clothes = '$name' or "  .$stu;
                        }
                        $con2=substr($stu,0,strlen($stu)-3);
                    }
                }
            $size=$_POST["size"];
            if(!isset($_POST["number"])){
                $number=0;
            }else{
                $number=(int)$_POST["number"];
            }
            $num= $size*$number;
//echo $number;exit;
           $ru['_string']="$con ."or". $con2 ";
		if($con){
			$aaa=$con;
		}else if($con2){
			$aaa=$con2;
		}else{
			$aaa=null;
		}
            $totalCount=D("db")->where($aaa)->count();
            $list=D("db")->where($aaa)->field("db_id,clothes")->limit("$num,$size")->select();
		for($i=0;$i<count($list);$i++){
			$datas[$i]["id"]=$list[$i]["db_id"];
$datas[$i]["name"]=$list[$i]["clothes"];
		}

            $lists["list"]=$datas;
            $lists["totalCount"]=$totalCount;
            $rst=json_encode($lists, JSON_UNESCAPED_UNICODE);
            echo $rst;
        }
}