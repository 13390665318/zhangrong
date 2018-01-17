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
        $json = file_get_contents($ymlog);
        $json = explode(PHP_EOL, $json);

        foreach ($json as $v) {
            $data[] = json_decode($v, 1);
        }


        foreach ($data as $k=>$value){
            if($value['Operation'] == 'OnlineRoleNum'){
                $linestatus[]=$value;
            }elseif($value['Operation']=='LoginRole'){
                $LoginRole[]=$value;
            }elseif($value['Operation']=='CreateRole'){
                $CreateRole[]=$value;
            }elseif($value['Operation']=='Prepaid'){
                $Prepaid[]=$value;
            }
        }
        foreach ($Prepaid as $k=>$value){
            $Prepaid[$k]['pay_number']=$value['cash'];
            $Prepaid[$k]['game_user_name']=$value['role_name'];
            $Prepaid[$k]['db_id']=$value['serverID'];
            $Prepaid[$k]['game_user_id']=$value['role_id'];
            $Prepaid[$k]['level']=$value['role_level'];
            $Prepaid[$k]['user_id']=$value['account_id'];
            unset($Prepaid[$k]['cash']);
            unset($Prepaid[$k]['role_name']);
            unset($Prepaid[$k]['serverID']);
            unset($Prepaid[$k]['role_id']);
            unset($Prepaid[$k]['role_level']);
            unset($Prepaid[$k]['account_id']);
        }
        $model=D('Playeronline');
        $model->payadd($Prepaid);


        foreach ($CreateRole as $k=>$value){
            $CreateRole[$k]['register_time']=$value['create_time'];
            $CreateRole[$k]['game_user_name']=$value['role_name'];
            $CreateRole[$k]['db_id']=$value['serverID'];
            $CreateRole[$k]['game_user_id']=$value['role_id'];
            unset($CreateRole[$k]['create_time']);
            unset($CreateRole[$k]['role_name']);
            unset($CreateRole[$k]['serverID']);
            unset($CreateRole[$k]['role_id']);
        }
        $model=D('Playeronline');
        $model->createadd($CreateRole);
      /*  foreach ($data as $k=>$value){
            if(isset($value['account_id'])){
                $account[]=$value;
            }
        }*/

        foreach ($LoginRole as $k=>$value){
            $LoginRole[$k]['start_time']=$value['LogTime'];
            $LoginRole[$k]['game_user_id']=$value['role_id'];
            $LoginRole[$k]['level']=$value['role_level'];
            unset($LoginRole[$k]['LogTime']);
            unset($LoginRole[$k]['role_id']);
            unset($LoginRole[$k]['role_level']);
        }
        //dump($LoginRole);exit;
        $model=D('Playeronline');
        $model->loginadd($LoginRole);

        //dump($LoginRole);exit;

        /*foreach ($LoginRole as $k=>$value){
            $new_array[$k]=$value['account_id'];
        }
        $account_id=array_unique($new_array);
        foreach ($account_id as $k=>$value){
            $last_array[]=$LoginRole[$k];
        }*/


        //dump($last_array);exit;



        foreach ($linestatus as $key=>$value){
            $linestatus[$key]['time']=substr($date,0,11);
            $linestatus[$key]['db_id']=$value['serverID'];
            $linestatus[$key]['num']=$value['OnLinePlayerNum'];
            unset($linestatus[$key]['OnLinePlayerNum']);
            unset($linestatus[$key]['serverID']);
            $linestatus[$key]['f_time']=substr($linestatus[$key]['LogTime'],11,5);
        }

        $model=D('Playeronline');
        $model->onlineadd($linestatus);
        $this->display();



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
         $this->assign("arr",$arr);
         $this->display();*/
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
           echo $data;
        }
    }
}