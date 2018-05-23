<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12 0012
 * Time: 下午 4:34
 */

namespace Home\Controller;


class PlayeronlineController extends BaseController
{
    public function index(){
      //
        $date=I('post.date');
        $day=I('post.day');



        if($date){
            $yearmonth= substr($date,0,10);
            //获取初始化的年月
            $ym=str_replace('-','',$yearmonth);
            $ymlog="public/Log/".$ym.".log";
            $nyr=substr($date,0,10);
        }else {
            $date = date("Y-m-d H:i:s", time());
            $yearmonth = substr($date, 0, 10);
            //获取初始化的年月
            $ym = str_replace('-', '', $yearmonth);
            $ymlog = "public/Log/" . $ym . ".log";
            $nyr=substr($date,0,10);
        }
        if(!$day){
            $day=date("Y-m-d 00:00:00", time());
        }
        //判断筛选是否为空
        if(!empty($_POST['role_id'])||!empty($_POST['role_name'])){

            $role_id=I('post.role_id');

            $role_name = I('post.role_name');

            //获取年月

            //获取日志数据
            $json = file_get_contents($ymlog);
            $json = explode(PHP_EOL, $json);
            foreach ($json as $v) {
                $data[] = json_decode($v, 1);
            }
           
        //判断筛选条件
        if(!empty($_POST['role_id']) && !empty($_POST['role_name'])){

            foreach ($data as $value) {
                if ($value['Operation'] == $role_name && $value['role_id'] == $role_id) {
                    $behavior[] = $value;
                }
            }

        }else {

        //判断在线人数

            if(empty($_POST['role_id']) && !empty($_POST['role_name'])) {
                foreach ($data as $value) {
                    if (($value['Operation'] == $role_name)) {
                        $behavior[] = $value;
                    }
                }
            }else{
                foreach ($data as $value){
                    if (($value['role_id'] == $role_id) ) {
                        $behavior[] = $value;
                    }
                }
            }
        }
        //对行为进行判断
            foreach ($data as $value) {
                if ($value['Operation'] == 'LoginRole') {
                    $loginrole[] = $value;
                } elseif ($value['Operation'] == 'YuanbaoUse') {
                    $yuanbaouse[] = $value;
                } elseif ($value['Operation'] == 'ItemBuy') {
                    $itembuy[] = $value;
                } elseif ($value['Operation'] == 'Chat') {
                    $chat[] = $value;
                }
                elseif ($value['Operation']=='OnlineRoleNum'){
                    $linestatus[]=$value;
                }
            }


            //调用模型方法
            /*$model=D('Playeronline');
            $model->onlineadd($linestatus);
            //dump($model->getLastSql());exit;
            $model->loginroleadd($loginrole);
            $model->yuanbaouseadd($yuanbaouse);
            $model->itembuyadd($itembuy);
            $model->chatadd($chat);*/
           /* foreach ($data as $k=>$value){
                if (($value['Operation'] == 'OnlineRoleNum')) {
                    if ($data[$k]['OnLinePlayerNum'] == $data[$k - 1]['OnLinePlayerNum']) {
                        continue;
                    }
                    $arr[] = $value;
                }
            }
            foreach($arr as $k=>$value) {
                if ($arr[$k]['OnLinePlayerNum'] == $arr[$k-1]['OnLinePlayerNum']) {
                    continue;
                }else{
                    $loglinestatus[]=$value;
                }
            }*/

           // dump($linestatus[0]['LogTime']);exit;


            //dump($linestatus);exit;
            //取当前在线人数
           // $online = end(array_column($data, 'OnLinePlayerNum'));

            //dump($online);exit;
            /* foreach ($data as $value){
                 if(in_array('OnLinePlayerNum',$value)) continue;
                 $shuju[]=$value;
             }*/


            //判断分页
            /*$count = count($linestatus);//计算总页数
            $page2 = new \Think\Page($count, 10);
            $show2 = $page2->show();
            $linestatus = array_splice($linestatus, $page2->firstRow, $page2->listRows);*/

            /* $count=count($behavior);
             $page=new \Think\Page($count,10);
             $show=$page->show();
             $behavior=array_splice($behavior,$page->firstRow,$page->listRows);*/
            $this->assign("linestatus",$linestatus);
            $this->assign("data",$behavior);
           // $this->assign("page2",$show2);
            // $this->assign("page",$show);
            //$this->assign("online",$online);
            //dump($data);exit;





            /* if(isset($_SESSION["game_id"])) {
                 $game_id = $_SESSION["game_id"];
             } else {
                 $game_id = 1;
              }
             // 游戏区/服
             $clostu=D("db")->where("game_id=$game_id")->order("db_id desc")->select();
             $this->assign("clostu",$clostu);
             // 默认 最新服
            if(isset($_GET["db_id"])){
                 $db_id=I("get.db_id");
                 $_SESSION["db_id"]=$db_id;
             }else{
                 if(isset($_SESSION["db_id"])){

                     $db_id= $_SESSION["db_id"];
                 }else{
                     $db_id=$clostu[0]["db_id"];
                     $_SESSION["db_id"]=$db_id;
                 }

             }
         //$_SESSION["db_id"]=1;
             $this->assign("db_id",$db_id);
             if($_GET["game_user_name"]!=null) {
                 $uname = I("get.game_user_name");
                 $where["uname"] = array('like', "%$uname%");
             }
             if(isset($_GET["game_user_id"])){
                 if(I("get.game_user_id")!=null){
                     $where["uid"]=I("get.game_user_id");
                 }else{
                     $where["uid"]=null;
                 }
             }else{
                 $where["uid"]=null;
             }
          $lastupdtime = date("Y-m-d H:i:s",time()-3600*12);
             $where['_string'] = "lastlogintime>=lastupdtime and lastupdtime >'$lastupdtime'";
         //var_dump($where);
             $con=array_filter($where);
             $connection2=db2($game_id,$db_id);
         //var_dump($connection2);
             $User=M("User",'',$connection2);
             $connection=db($game_id,$db_id);
             $Userbase = M('San_userbase','',$connection);
             $count      =$Userbase->where($con)->count();// 查询满足要求的总记录数
             $Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(20)
             $show       = $Page->show();// 分页显示输出
             $this->assign("page",$show);// 赋值分页输出
             $arr=$Userbase->where($con)->limit($Page->firstRow.','.$Page->listRows)->select();
     //var_dump($arr);
             for($i=0;$i<count($arr);$i++){
                 $uid=$arr[$i]["uid"];
                 $Rus=$User->where("user_id=$uid")->find();

                 $arr[$i]["creator"]=$Rus["source"];
                 $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
             }
             $sum=count($arr);
             $this->assign("sum",$sum);
             $this->assign("arr",$arr);*/
        }else {/////////////////////////////////////////////////////////////////////////////////////////为空
            //读取日志文件
            $json = file_get_contents($ymlog);
            $json = explode(PHP_EOL, $json);

            foreach ($json as $v) {
                $data[] = json_decode($v, 1);
            }




            //取出除去在线的数据
         /*   $where['LogTime']=array('like','%'.$yearmonth.'%');//判断获取到的数据是否和日志同步
                $linestatus=D('Online')->where($where)->select();
                $linecount=count($linestatus);*/
            /*foreach ($data as $k=>$value){
                if (($value['Operation'] == 'OnlineRoleNum')){
                    if ($data[$k]['OnLinePlayerNum'] == $data[$k - 1]['OnLinePlayerNum']) {
                        continue;
                    }
                    $arry[] = $value;
                }
            }
            foreach ($arry as $k=>$value) {
                if ($arry[$k]['OnLinePlayerNum'] == $arry[$k-1]['OnLinePlayerNum']) {
                    continue;
                }else{
                    $logline[]=$value;
                }
            }*/
            foreach ($data as $k=>$value){
                if($value['Operation'] == 'OnlineRoleNum'){
                    $linestatus[]=$value;
                }
            }



            foreach ($linestatus as $key =>$value){
                $linestatus[$linestatus]['time']=substr($linestatus[$key]['LogTime'],11,10);
                $linestatus[$key]['date']=substr($linestatus[$key]['LogTime'],5,5);
            }

                $loglinecount=count($linestatus);

           //将数据入库
    /*        if($linecount!==$loglinecount){
                echo 123;exit;
                dump($linestatus);exit;

                $model->onlineadd($logline);
            }*/




                        /*foreach ($data as $k=>$value){
                            if (($value['Operation'] == 'OnlineRoleNum')) {

                                $linestatus[] = $value;
                            }
                        }*/
               /*     foreach($arr as $k=>$value) {
                        if ($arr[$k]['OnLinePlayerNum'] == $arr[$k-1]['OnLinePlayerNum']) {
                            continue;
                        }else{
                            $linestatus[]=$value;
                        }
                    }*/

                    foreach ($linestatus as $key =>$value){
                        $linestatus[$key]['time']=substr($linestatus[$key]['LogTime'],11,10);
                        $linestatus[$key]['date']=substr($linestatus[$key]['LogTime'],5,5);
                        $linestatus[$key][$nyr]=$linestatus[$key]['OnLinePlayerNum'];
                        unset($linestatus[$key]['LogTime']);
                    }

                    //将数据入库

                    $model=D('Playeronline');
                    //dump($linestatus);exit;
                    $model->onlineadd($linestatus,$nyr);


            foreach ($data as $k=>$value) {
                if (!($value['Operation'] == 'OnlineRoleNum')) {
                    $behavior[] = $value;
                }
            }
    //dump($linestatus);exit;
            //去除重复的在线数据



            //反排序
            $linestatus = array_reverse($linestatus);



            //dump($linestatus);exit;

            //$operation=array_column($data,'Operation');
            //dump($operation);
            //$sum=count($data);
            //dump($data);exit;
            /*   foreach($data as $value){
                   if(($value['Operation']=='OnlineRoleNum')){
                       $linestatus[]=$value;
                   }
               }*/
            //判断Operation类型
            foreach ($data as $value) {
                if ($value['Operation'] == 'LoginRole') {
                    $loginrole[] = $value;
                } elseif ($value['Operation'] == 'YuanbaoUse') {
                    $yuanbaouse[] = $value;
                } elseif ($value['Operation'] == 'ItemBuy') {
                    $itembuy[] = $value;
                } elseif ($value['Operation'] == 'Chat') {
                    $chat[] = $value;
                }
            }

            //调用模型方法入库


            //dump($model->getLastSql());exit;
           /* $model->loginroleadd($loginrole);
            $model->yuanbaouseadd($yuanbaouse);
            $model->itembuyadd($itembuy);
            $model->chatadd($chat);*/


            //dump($linestatus);exit;
            //取当前在线人数
            //$online = end(array_column($data, 'OnLinePlayerNum'));

            //dump($online);exit;
            /* foreach ($data as $value){
                 if(in_array('OnLinePlayerNum',$value)) continue;
                 $shuju[]=$value;
             }*/

            // dump($linestatus);exit
            //D('Online')->getLastSql();
            //$email=D("online")->select();
            //dump($email);exit;
            //判断分页

           /* $count = count($linestatus);
            $page2 = new \Think\Page($count, 10);
            $show2 = $page2->show();
            $linestatus = array_splice($linestatus, $page2->firstRow, $page2->listRows);*/


            /* $count=count($behavior);
             $page=new \Think\Page($count,10);
             $show=$page->show();
             $behavior=array_splice($behavior,$page->firstRow,$page->listRows);*/
            //去除变更的数据
            foreach ($data as $k=>$value){
                if(($value['Operation']=='OnlineRoleNum')){
                    $linestatus[]=$value;
                }
             /*   if (($value['Operation'] == 'OnlineRoleNum')) {
                    if ($data[$k]['OnLinePlayerNum'] == $data[$k - 1]['OnLinePlayerNum']) {
                        continue;
                    }
                    $arr[] = $value;
                }*/
            }
            /*foreach($arr as $k=>$value) {
                if ($arr[$k]['OnLinePlayerNum'] == $arr[$k-1]['OnLinePlayerNum']) {
                    continue;
                }else{
                    $loglinestatus[]=$value;
                }
            }*/


              /*  $time=substr($day,11);
                $date=substr($day,5,5);

                $tian=substr($day,8,2);
                //$where['LogTime']=array('like','%'.$time.'%');
                $linestatus=D('Online')->where("time<='$time'")->order('time desc')->select();
                foreach ($linestatus as $key =>$value){
                    $linestatus[$key]['LogTime']=substr($linestatus[$key]['LogTime'],8,2);
                }*/

            //dump($linestatus);exit;
             /*$arrwish=array();
                foreach ($linestatus as $k=>$v) {
                    dump($arrwish[$k]['date']);
                    if ($arrwish[$k]['date']>$v['date']){
                        $arrwish['date']=$v;
                    }
                    continue;
                }*/
               // dump($arrwish);exit;




             /*   foreach ($linestatus as $key=>$value) {
                    foreach ($value)
                    if ($linestatus[$key]['date'] == $date){
                        $array[] = $value;
                    }
                }*/
                //dump(reset($array));exit;
            $sql = "desc online";
            $Model = D('online');
            $list = $Model->query($sql);

           foreach ($list as $k => $val){
               if($val['Field']=='id'||$val['Field']=='serverID'||$val['Field']=='time'||$val['Field']=='Operation'){
                   continue;
               }
               $da[]=$val['Field'];
           }
           //dump($da);exit;



//count这个表有多少个字段


           $daa=implode(',',$da);


            $linestatusday=D('Online')->field("time,$daa")->select();
           // dump($linestatusday);exit;




            //$this->assign('tian',$tian);
            //$this->assign('time',$time);
            //$this->assign('loglinestatus',$loglinestatus);
            $this->assign('da',$da);
            $this->assign('linestatusday',$linestatusday);
            //$this->assign("lin", $linestatus);
            $this->assign("data", $behavior);
            //$this->assign("page2", $show2);
            // $this->assign("page",$show);
            //$this->assign("online", $online);
            //dump($data);exit;
        }
        $this->display();
    }
   /* public function cx(){

    }
    public function playselect(){
        if (isset($_GET["db_id"])){
            if(isset($_SESSION["game_id"])) {
                $game_id = $_SESSION["game_id"];
            } else {
                $game_id = 1;
             }
            $db_id=I("get.db_id");
            $game_user_name=I("get.game_user_name");
            $game_user_id=I("get.game_user_id");
            $account=I("get.account");
            if($account==null){
                if($game_user_name==null){
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);

                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>=lastupdtime and  uid = $game_user_id")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }else if($game_user_id==null){
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);
                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>=lastupdtime  and uname like '%$game_user_name%' ")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }else{
                    $connection=db($game_id,$db_id);
                    $Userbase = M('San_userbase','',$connection);
                    $San_account = M('San_account','',$connection);
                    $arr=$Userbase->where("lastlogintime>=lastupdtime  and uname like '%$game_user_name%' and  uid = $game_user_id")->select();
                    for($i=0;$i<count($arr);$i++){
                        $uid=$arr[$i]["uid"];
                        $Rus=$San_account->where("uid=$uid")->find();
                        $arr[$i]["account"]=$Rus["account"];
                        $arr[$i]["creator"]=$Rus["creator"];
                        $arr[$i]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");

                    }
                }

            }else{
                $connection=db($game_id,$db_id);
                $Userbase = M('San_userbase','',$connection);
                $San_account = M('San_account','',$connection);
                $Rus=$San_account->where("account='$account'")->find();

                if($Rus!=null){
                    $uid=$Rus["uid"];
                    $arr=$Userbase->where("lastlogintime>=lastupdtime  and   uid = $uid")->select();
                    $arr[0]["account"]=$account;
                    $arr[0]["creator"]=$Rus["creator"];
                    $arr[0]["Summoney"]=D("pay")->where("game_user_id=$uid")->sum("money");
                }else{
                    $arr=null;
                }

            }
           $data=json_encode($arr);
           echo $data;*/
    /*    }
    }*/
}