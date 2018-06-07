<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 上午 10:06
 */

namespace Home\Controller;

header("Content-type: text/html; charset=utf-8");
class ArticleController extends BaseController
{
    public function index()
    {
        if(isset($_GET['id'])){
            $ru['id']=I('get.id');
        }

        $article=D('Article')->field('title,time,content')->where($ru)->find();

        $this->assign('article',$article);
        $this->display();
    }
    public function show(){
        if(isset($_GET["id"])){
            $id=I("get.id");
            $ru=D("activity")->where("id=$id")->find();
            $type=$ru["type"];
            $data=D("activity")->where("type = $type")->select();


            for($i=0;$i<count($data);$i++){
                if($data[$i]["item1"]!=0){
                    $itemid=$data[$i]["item1"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["it1"]=$rus["itemname"].":".$data[$i]["num1"];
                }
                if($data[$i]["item2"]!=0){
                    $itemid=$data[$i]["item2"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["it2"]=$rus["itemname"].":".$data[$i]["num2"];
                }
                if($data[$i]["item3"]!=0){
                    $itemid=$data[$i]["item3"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["it3"]=$rus["itemname"].":".$data[$i]["num3"];
                }
                if($data[$i]["item4"]!=0){
                    $itemid=$data[$i]["item4"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["it4"]=$rus["itemname"].":".$data[$i]["num4"];
                }

                if($data[$i]["costitem1"]!=0){
                    $itemid=$data[$i]["costitem1"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["cit1"]=$rus["itemname"].":".$data[$i]["costnum1"];
                }

                if($data[$i]["costitem2"]!=0){
                    $itemid=$data[$i]["costitem2"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["cit2"]=$rus["itemname"].":".$data[$i]["costnum2"];
                }

                if($data[$i]["costitem3"]!=0){
                    $itemid=$data[$i]["costitem3"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["cit3"]=$rus["itemname"].":".$data[$i]["costnum3"];
                }

                if($data[$i]["costitem4"]!=0){
                    $itemid=$data[$i]["costitem4"];
                    $rus=D("goods")->where("itemid=$itemid")->find();
                    $data[$i]["cit4"]=$rus["itemname"].":".$data[$i]["costnum4"];
                }
            }
            //   var_dump($data);
            $this->assign("data", $data);
            $this->display();
        }
    }
    public function show2(){
        if(isset($_GET["id"])){
            $id=I("get.id");
            $data=D("activity")->where("id = $id")->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    public function add()
    {
        if (isset($_POST["sub"])) {


            $filename = $_FILES['file']['tmp_name'];
            //  var_dump($filename);exit;
            if (empty ($filename)) {

                $this->error("请选择要导入的CSV文件", U("Activity/add"));exit;

            }
            $handle = fopen($filename, 'r');
            $result = input_csv($handle); //解析csv
            $len_result = count($result);
            if($len_result==0){

                $this->error("没有任何数据", U("Activity/add"));exit;
            }

            $ret = D("activity")->where('1')->delete();



            for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值
                $arr[$i]["id"] = (int)iconv('gb2312', 'utf-8', $result[$i][0]); //中文转码
                $arr[$i]["sort"] = iconv('gb2312', 'utf-8', $result[$i][1]);
                $arr[$i]["mode"] = iconv('gb2312', 'utf-8', $result[$i][2]);
                $arr[$i]["type"] = iconv('gb2312', 'utf-8', $result[$i][3]);
                $arr[$i]["name"] = $result[$i][4]; //中文转码
                $arr[$i]["backicon"] = iconv('gb2312', 'utf-8', $result[$i][5]);
                $arr[$i]["nameicon"] = iconv('gb2312', 'utf-8', $result[$i][6]);
                $arr[$i]["dec"]= $result[$i][7];
                $arr[$i]["step"] = iconv('gb2312', 'utf-8', $result[$i][8]); //中文转码
                $arr[$i]["txt"] =  $result[$i][9];
                $arr[$i]["tasktypes"] = $result[$i][10];
                $arr[$i]["n1"] = iconv('gb2312', 'utf-8', $result[$i][11]); //中文转码
                $arr[$i]["n2"] = iconv('gb2312', 'utf-8', $result[$i][12]);
                $arr[$i]["n3"] = iconv('gb2312', 'utf-8', $result[$i][13]);
                $arr[$i]["n4"] = iconv('gb2312', 'utf-8', $result[$i][14]); //中文转码
                $arr[$i]["item1"] = iconv('gb2312', 'utf-8', $result[$i][15]);
                $arr[$i]["num1"] = iconv('gb2312', 'utf-8', $result[$i][16]);
                $arr[$i]["item2"] = iconv('gb2312', 'utf-8', $result[$i][17]); //中文转码
                $arr[$i]["num2"] = iconv('gb2312', 'utf-8', $result[$i][18]);
                $arr[$i]["item3"] = iconv('gb2312', 'utf-8', $result[$i][19]);
                $arr[$i]["num3"] = iconv('gb2312', 'utf-8', $result[$i][20]); //中文转码
                $arr[$i]["item4"] = iconv('gb2312', 'utf-8', $result[$i][21]);
                $arr[$i]["num4"] = iconv('gb2312', 'utf-8', $result[$i][22]);
                $arr[$i]["costitem1"] = iconv('gb2312', 'utf-8', $result[$i][23]); //中文转码
                $arr[$i]["costnum1"] = iconv('gb2312', 'utf-8', $result[$i][24]);
                $arr[$i]["costitem2"] = iconv('gb2312', 'utf-8', $result[$i][25]);
                $arr[$i]["costnum2"] = iconv('gb2312', 'utf-8', $result[$i][26]); //中文转码
                $arr[$i]["costitem3"] = iconv('gb2312', 'utf-8', $result[$i][27]);
                $arr[$i]["costnum3"] = iconv('gb2312', 'utf-8', $result[$i][28]);
                $arr[$i]["costitem4"] = iconv('gb2312', 'utf-8', $result[$i][29]); //中文转码
                $arr[$i]["costnum4"] = iconv('gb2312', 'utf-8', $result[$i][30]);
                $arr[$i]["start"] = iconv('gb2312', 'utf-8', $result[$i][31]);
                $arr[$i]["continued"] = iconv('gb2312', 'utf-8', $result[$i][32]); //中文转码
                $arr[$i]["cd"] = iconv('gb2312', 'utf-8', $result[$i][33]);
                $arr[$i]["show"] = iconv('gb2312', 'utf-8', $result[$i][34]);
                $arr[$i]["renovate"] = iconv('gb2312', 'utf-8', $result[$i][35]); //中文转码
                $arr[$i]["reset"] = iconv('gb2312', 'utf-8', $result[$i][36]);
                $arr[$i]["status"]=iconv('gb2312', 'utf-8', $result[$i][37]);
                //导入


                $ru=D("activity")->add($arr[$i]);
                if($ru==null){
                    $this->error("CSV导入失败", U("Activity/add"));exit;
                }else{
                    $num=1;
                }


            }

            fclose($handle); //关闭指针
//var_dump($arr);exit;


            if ($num==1){
                $this->success("导入成功", U("Activity/index"));
            } else {
                $this->error("新增失败", U("Activity/add"));
            }

        }else{
            $this->display();
        }
    }

    public function edit(){
        if(isset($_POST["id"])){
            $id=I("post.id");

            $rus=D("activity")->where("id=$id")->save($_POST);
            if($rus ==1){
                $this->success("修改成功",U("Activity/index"));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("修改失败", U("Activity/edit",array("id"=>$id)));
            }
        }else if(isset($_GET["id"])){
            $id=I("get.id");
            $data=D("activity")->where("id = $id")->find();
            $this->assign("data", $data);
            $this->display();
        }

    }

    public function add2(){
        if(isset($_POST["id"])){
            $data=$_POST;
            $id=$data["id"];
            $rus=D("activity")->add($data);
            if($rus){
                $this->success("添加成功",U("Activity/show",array("id"=>$id)));
            }else{
                //  $this->redirect('Reward/index');
                $this->error("添加失败", U("Activity/add2",array("id"=>$id)));
            }
        }else if(isset($_GET["id"])){
            $id=I("get.id");
            $data=D("activity")->where("id = $id")->find();
            $this->assign("data", $data);
            $this->display();
        }
    }


    public function del(){

        if (isset($_GET["ids"])) {
            $ids = I("get.ids");
            $arr = array();
            $str = explode(',', $ids);
            $sdb=I("get.sdb");
            $edb=I("get.edb");
            if($sdb==0 && $edb==0){
                $db=D("db")->select();
            }else{
                $db=D("db")->where("clothes_num>=$sdb and clothes_num<=$edb")->select();
            }
            $game_id=1;
            //  var_dump($str);exit;
            for($i=0;$i<count($db);$i++){
                $db_id=$db[$i]["db_id"];
                $connection=db($game_id,$db_id);
                $ret = M('san_activitymask','',$connection)->where('id != 1007')->delete();
//echo $ret;exit;
                for($j=0;$j<count($str);$j++){
                    if($str[$j]!=null){
                        $id=$str[$j];
                        $data=D("activity")->where("id=$id")->find();
                        $stu["id"]=(int)round($data["id"]/100);
                        $arr["id"]=(int)round($data["id"]/100);
                        $arr["btn_type"]=(int)$data["type"];
                        $arr["status"]=(int)$data["status"];
                        $arr["name"]=$data["name"];
                        $arr["tasktype"]=(int)$data["tasktypes"];
                        $arr["start"]=$data["start"];
                        $arr["continued"]=(int)$data["continued"];
                        $arr["cd"]=(int)$data["cd"];
                        $arr["show"]=(int)$data["show"];
                        $arr["renovate"]=(int)$data["renovate"];
                        $arr["reset"]=(int)$data["reset"];
                        $arr["sort"]=(int)$data["sort"];
                        $arr["mode"]=(int)$data["mode"];
                        $arr["backicon"]=$data["backicon"];
                        $arr["nameicon"]=$data["nameicon"];
                        $arr["dec"]=$data["dec"];
                        // info
                        $stu["info"]=json_encode($arr,JSON_UNESCAPED_UNICODE);
                        $type=$data["type"];
                        $data2=D("activity")->where("type=$type")->select();
                        $item=array();
                        for($z=0;$z<count($data2);$z++){
                            $item[$z]["id"]=(int)$data2[$z]["id"];
                            $item[$z]["step"]=(int)$data2[$z]["step"];
                            $item[$z]["txt"]=$data2[$z]["txt"];
                            $item[$z]["n"]=array((int)$data2[$z]["n1"],(int)$data2[$z]["n2"],(int)$data2[$z]["n3"],(int)$data2[$z]["n4"]);
                            //  $item[$z]["n1"]=$data2[$z]["n1"];
                            // $item[$z]["n2"]=$data2[$z]["n2"];
                            // $item[$z]["n3"]=$data2[$z]["n3"];
                            // $item[$z]["n4"]=$data2[$z]["n4"];
                            $item[$z]["item"]=array((int)$data2[$z]["item1"],(int)$data2[$z]["item2"],(int)$data2[$z]["item3"],(int)$data2[$z]["item4"]);
                            $item[$z]["num"]=array((int)$data2[$z]["num1"],(int)$data2[$z]["num2"],(int)$data2[$z]["num3"],(int)$data2[$z]["num4"]);
                            $item[$z]["costitem"]=array((int)$data2[$z]["costitem1"],(int)$data2[$z]["costitem2"],(int)$data2[$z]["costitem3"],(int)$data2[$z]["costitem4"]);
                            $item[$z]["costnum"]=array((int)$data2[$z]["costnum1"],(int)$data2[$z]["costnum2"],(int)$data2[$z]["costnum3"],(int)$data2[$z]["costnum4"]);
                        }
                        //items
                        $stu["items"]=json_encode($item,JSON_UNESCAPED_UNICODE);
                        $stu["topfight"]="null";
                        $stu["toplevel"]="null";

                        if($stu["id"]==1007){
                            continue;
                        }else{
                            $r= M('san_activitymask','',$connection)->add($stu);
                            if($r){
                                $num=1;

                                //http://43.254.151.195:2222/reloadactivity?op=1

                                $datas=D("db")->where("db_id=$db_id")->find();
                                $ip=$datas["ip"];
                                $port=$datas["db_port"];
                                $token=$_SESSION["token"];
                                $md=md5($token);
                                $url="http://$ip:$port/reloadactivity?op=1&token=$md";

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                $output = curl_exec($ch);
                                $num=1;
                            }else{
                                echo -1;exit;
                            }
                        }
                        //var_dump($stu);exit;



                    }
                 //   dump($stu["id"]);
                }
             //   exit;
            }
               if($num==1){
                    echo 1;
             }


        }
    }
    public function send(){
        if(isset($_GET["id"])) {
            $id = I("get.id");
            $type = I("get.type");
            $arr = D("activity")->where("id=$id")->find();
            $name=$arr["name"];
            if ($type == 0) {
                $data["status"] = 0;
            } else {
                $data["status"] = 1;
            }
            $ru = D("activity")->where("name='$name'")->save($data);
            //  echo $ru;
            if ($ru) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }



}