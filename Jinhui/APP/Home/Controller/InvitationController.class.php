<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 下午 8:09
 */

namespace Home\Controller;

header ( "Content-type:text/html;charset=utf-8" );
class InvitationController extends BaseController
{
    public function index(){
        $count      =D("code_type")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
        $show       = $Page->show();// 分页显示输出
        $this->assign("page",$show);// 赋值分页输出
      //  $arr=D("code_type")->order("code_type_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
 // $arr=D("code_type as a")->join("qudao as b on a.channel=b.cid")->field("a.code_type_id,a.code_type_name,a.goods_ids,a.type,a.time,a.channel,b.name")->order("code_type_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
 $arr=D("code_type")->order("code_type_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
 for($i=0;$i<count($arr);$i++){
           $channel=$arr[$i]["channel"];
		if($channel!=0){	
           $ru=D("qudao")->where("cid=$channel")->find();
		}else{
 $ru=null;
		}
           if($ru){
               $arr[$i]["name"]=$ru["name"];
           }else{
               $arr[$i]["name"]="通用";
           }
        }
        $this->assign("arr",$arr);
        $this->display();
    }
    public function add(){
        if(isset($_POST["sub"])){
            $arr=array();
            $arr["code_type_name"]=I("post.code_type_name");
            $arr["type"]=I("post.type");
            $arr["time"]=date("Y-m-d H:i:s",time());
            if(I("channel")==null){
                $arr["channel"]="通用";
            }else{
                $arr["channel"]=I("channel");
            }
            $arr["goods_ids"]=I("post.content");
            $goods_ids=I("post.content");
            if($goods_ids!=null){
                if(strstr($goods_ids, "；")){
                    echo "<script>alert('存在中文:；');window.history.go(-1);</script>";exit;
                }else if(strstr($goods_ids, "：")){
                    echo "<script>alert('存在中文:：');window.history.go(-1);</script>";exit;
                }else{
                    $bag=explode(";",$goods_ids);

                    for($i=0;$i<count($bag);$i++){
                        $baGarr=$bag[$i];
                        $ru=explode(':',$baGarr);
                        if(count($ru)!=2){
                            echo "<script>alert('物品格式不正确');window.history.go(-1);</script>";exit;
                        }
                    }
                }}

           // var_dump($arr);exit;
                    $rus=D("code_type")->add($arr);
            if($rus!=null){
                    $userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="增加id为".$rus."的邀请码类别";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Invitation/index"));
            }else {
                $this->error("新增失败", U("Invitation/add"));
            }

        }else{
	$data=D("qudao")->select();
          //  dump($data);
            $this->assign("data", $data);
           $this->display();

        }
    }
    public function edit(){
        if (isset($_GET["id"])){

                $data=D("qudao")->select();
            //  dump($data);
            $this->assign("data", $data);
                $code_type_id = I("get.id");
                $arr = D("code_type")->where("code_type_id=$code_type_id")->find();
                $channel=$arr["channel"];
            $this->assign("channel", $channel);
                $this->assign("arr", $arr);
                $this->display();

        }else if(isset($_POST["code_type_id"])){
//VAR_DUMP($_POST);EXIT;
            $arr=array();
            $arr["code_type_name"]=I("post.code_type_name");
            $arr["type"]=I("post.type");
            $arr["time"]=date("Y-m-d H:i:s",time());
            if(I("channel")==null){
                $arr["channel"]="通用";
            }else{
                $arr["channel"]=I("channel");
            }
            $arr["goods_ids"]=I("post.content");
            $goods_ids=I("post.content");
            if($goods_ids!=null){
                if(strstr($goods_ids, "；")){
                    echo "<script>alert('存在中文:；');window.history.go(-1);</script>";exit;
                }else if(strstr($goods_ids, "：")){
                    echo "<script>alert('存在中文:：');window.history.go(-1);</script>";exit;
                }else{
                    $bag=explode(";",$goods_ids);

                    for($i=0;$i<count($bag);$i++){
                        $baGarr=$bag[$i];
                        $ru=explode(':',$baGarr);
                        if(count($ru)!=2){
                            echo "<script>alert('物品格式不正确');window.history.go(-1);</script>";exit;
                        }
                    }
                }}

            // var_dump($arr);exit;
          //  $rus=D("code_type")->add($arr);

            $code_type_id=I("post.code_type_id");
            $rus=D("code_type")->where("code_type_id=$code_type_id")->save($arr);
            if($rus==1){
                    $userid=$_SESSION["userID"];
                    $russ=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$russ["name"];
                    $Rlog["account"]=$russ["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="修改id为".$code_type_id."的邀请码类别";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("修改成功",U("Invitation/index"));
            }else {
                $this->error("修改失败", U("Invitation/edit",array("id"=>$code_type_id)));
            }
        }
    }
    public function show(){
        $id=$_SESSION["userID"];
        $rus=D('admin')->where("id=$id")->find();
        $user2=$rus["code2"];
        if($user2==1) {
        if(isset($_GET["id"])) {
            $code_type_id = I("get.id");
            $arr = D("code_page")->where("code_type_id=$code_type_id")->select();
            $stu = D("code_type")->where("code_type_id=$code_type_id")->find();
            $this->assign("arr", $arr);
            $this->assign("stu", $stu);
            $this->display();
        }else{
            $this->error('没有操作权限',U("Invitation/index"));
        }
        }

    }
    public function codeadd(){
        if(isset($_POST["sub"])){
            // 类别入库
            $arr=array();
            $arr["code_type_id"]=I("post.code_type_id");
            $arr["begin_time"]=I("post.begin_time");
            $arr["end_time"]=I("post.end_time");
            $arr["number"]=I("post.number");
            $code_type_id=I("post.code_type_id");
            $Rus=D("code_type")->where("code_type_id=$code_type_id")->find();
            $arr["code_type_name"]=$Rus["code_type_name"];
            $arr["time"]=date("Y-m-d H:i:s",time());
          //  var_dump($arr);exit;
            $Kus=D("code_page")->add($arr);
            if($Kus!=null){
                // 生成邀请码 0-9 a-z A-Z 随机数 9 位
                $InArr["code_page_id"]=$Kus;
                $InArr["status"]=0;
                $InArr["type"]=$Rus["type"];
                $number=(int)I("post.number");
                $lenid= strlen($Kus);
                for($i=0;$i<$number;$i++){
                    $len=4-$lenid;
                    $chars1=GetRandStr(5);
                    $chars2=GetRandStr2($len);
                    $InArr["code_number"]=$Kus.$chars1.$chars2;
                    $k=D("code")->add($InArr);
                }
                $code_type_id=I("post.code_type_id");
                    $userid=$_SESSION["userID"];
                    $rus=D('admin')->where("$userid=$userid")->find();
                    $Rlog["user"]=$rus["name"];
                    $Rlog["account"]=$rus["user_name"];
                    $Rlog["ip"]=get_client_ip();
                    $Rlog["doc"]="新增id为".$Kus."的邀请码";
                    $Rlog["time"]=date("Y-m-d H:i:s",time());
                    $r=D("rlog")->add($Rlog);
                $this->success("新增成功",U("Invitation/show",array("id"=>$code_type_id)));
            }else {
                $this->error("新增失败", U("Invitation/codeadd"));
            }
        }else{

                $stu = D("code_type")->select();
                $this->assign("stu", $stu);
                $this->display();

        }
    }
    public function exl(){
        if(isset($_GET["code_page_id"])){
            $code_page_id=I("get.code_page_id");
            $arr=D("code")->where("code_page_id=$code_page_id")->select();

            header("Content-Type:text/html; charset=utf-8");
            Vendor('PHPExcel.PHPExcel');

            $objPHPExcel=new \PHPExcel();

            //设置excel列名
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','序号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','邀请码');


            //把数据循环写入excel中
            $i=1;
            foreach($arr as $key => $value){
                $key+=2;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,($key-1));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['code_number']);




            }
            //导出代码
            $name=time();
            $objPHPExcel->getActiveSheet()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
            ob_end_clean();//清除缓冲区,避免乱码
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

    }
    public function goodsselect(){
        if(isset($_GET["goods_name"])){
            $goods_name=I("get.goods_name");
            $arr=D("goods")->where("itemname like '%$goods_name%'")->field("itemid,itemname,itemdescription")->select();
		if($arr!=null){
$data=json_encode($arr);
	}else{
$data=null;
}
  
          //  $data=json_encode($arr);
            echo $data;
        }
    }
    // 邀请码请求
    public function code_post(){
        if(isset($_POST["code_number"])){
            $code_number=I("post.code_number");
            $uid=I("post.uid");
            $channel=I("post.channel");
            // 判断 该用户是否已经领取该礼包
            $rus=D("code_receive")->where("uid=$uid and code_number='$code_number'")->find();
            if($rus==null){
                // 判断礼包对应渠道
                if($channel==null){
                    // 为空  通用礼包
                    $channel='通用';
                }
                $cus=D("code")->where("code_number='$code_number'")->find();
                $code_page_id=$cus["code_page_id"];  // 邀请码列表id
                $vus=D("code_page")->where("code_page_id=$code_page_id")->find();
                $code_type_id=$vus["code_type_id"];//礼包邀请码类别id
                $bus=D("code_type")->where("code_type_id=$code_type_id")->find();
                $code_channel=$bus["$channel"];
                if($code_channel==$channel){
                    // 判断时间 是否已经失效
                    $time=strtotime(date("Y-m-d",time()));
                    $begin_time=strtotime($vus["begin_time"]);
                    $end_time=strtotime($vus["end_time"]);
                    if($time>=$begin_time&&$time<=$end_time){
                        // 判断邀请码 类型   1 通用  2  唯一
                        $type=$cus["type"];
                        if($type==1){
                            // 可以领用  入库  返回 道具ID 数量

                            $arr["uid"]=$uid;
                            $arr["code_number"]=$code_number;
                            $arr["channel"]=$channel;
                            $arr["time"]=date("Y-m-d H:i:s",time());
                            $rus=D("code_Receive")->add($arr);
                            if($rus!=null){
                                $goods_ids=$bus["goods_ids"];
                                $goods_nums=$bus["goods_nums"];
                                $goods=explode(',',$goods_ids);
                                $nums=explode(',',$goods_nums);
                                for($i=0;$i<count($goods);$i++){
                                    $stu[$i]["goods_id"]=$goods[$i];
                                    $stu[$i]["num"]=$nums[$i];
                                }
                                $data["errcode"]=0;
                                $data["list"]=$stu;
                            }else{
                                echo "1005,接口奔溃";
                            }
                        }else if($type==2){
                            // 判断 该邀请码 是否已经被领用   唯一礼包
                            $nus=D("code_receive")->where("code_number='$code_number'")->find();
                            if($nus==null){
                                // 可以领用  入库  返回 道具ID 数量
                                $arr["uid"]=$uid;
                                $arr["code_number"]=$code_number;
                                $arr["channel"]=$channel;
                                $arr["time"]=date("Y-m-d H:i:s",time());
                                $rus=D("code_Receive")->add($arr);
                                if($rus!=null){
                                    $goods_ids=$bus["goods_ids"];
                                    $goods_nums=$bus["goods_nums"];
                                    $goods=explode(',',$goods_ids);
                                    $nums=explode(',',$goods_nums);
                                    for($i=0;$i<count($goods);$i++){
                                        $stu[$i]["goods_id"]=$goods[$i];
                                        $stu[$i]["num"]=$nums[$i];
                                    }
                                    $data["errcode"]=0;
                                    $data["list"]=$stu;
                                }else{
                                    $data["errcode"]=1005;
                                    $data["list"]="接口奔溃";
                                }
                            }else{
                                $data["errcode"]=1004;
                                $data["list"]="邀请码已经被领用";
                            }
                        }
                    }else{
                        $data["errcode"]=1003;
                        $data["list"]="邀请码已经过期";
                    }
                }else{
                    $data["errcode"]=1002;
                    $data["list"]="礼包渠道不对";
                }
            }else{
                $data["errcode"]=1001;
                $data["list"]="该用户已经领取该礼包";
            }

            $data=json_encode($data);
            return $data;



        }
    }
    // 邀请码查找
    public function find(){
        if(isset($_GET["code"])){
            $codes=I("get.code");
            $arr=explode(',',$codes);
            $arr=array_filter($arr);
            for($i=0;$i<count($arr);$i++){
                $code=$arr[$i];
                $data[$i]["code"]=$code;
                $coStu=D("code")->where("code_number='$code'")->find();
                $data[$i]["code_id"]=$coStu["code_id"];
                if($coStu["type"==1]){
                    $data[$i]["type"]="通用";
                }else{
                    $data[$i]["type"]="唯一";
                }
                $code_page_id=$coStu["code_page_id"];
                $paStu=D("code_page")->where("code_page_id=$code_page_id")->find();
                $data[$i]["begin_time"]=$paStu["begin_time"];
                $data[$i]["end_time"]=$paStu["end_time"];
                $code_type_id=$paStu["code_type_id"];
                $tyStu=D("code_type")->where("code_type_id=$code_type_id")->find();
                $data[$i]["channel"]=$tyStu["channel"];


                $data[$i]["bag"]=$tyStu["goods_ids"];
                $lyStu=D("code_receive")->where("code_number='$code'")->find();
                if($lyStu==null){
                    $data[$i]["status"]="未领用" ;
                }else{
                    $data[$i]["status"]="已领用" ;
                }



            }
            $rus=json_encode($data);
            echo $rus;
        }else{
             $this->display();


        }
    }
}