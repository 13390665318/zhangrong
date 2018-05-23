<?php
namespace Home\Controller;

use Think\Controller;
class BaseController extends Controller
{



    public function __construct()
    {
        parent::__construct();



//  菜单筛选


// 登录判断

        if(isset($_SESSION["username"])){

            $_SESSION["token"]="alibabawansui";


            $username=$user_name=$_SESSION["username"];
            $_SESSION['username'];
            $password=$_SESSION['password'];
            $ru=D("admin")->where("user_name='$user_name' and password='$password'")->find();
            $urls="http://127.0.0.1/Alirefor/";
            $this->assign("urls",$urls);
            if($ru==null){
                //  $this->redirect('Login/index');
                $this->error("尚未登录", U("Login/index"));
            }else {





                $userID=$_SESSION["userID"];
                $ru=D("admin")->where("id=$userID")->find();
                $auth = $ru["auth"];
                $list = D("admin_auth_type")->where("id=$auth")->find();
                $auths = $list["auth"];
                $arr = explode(",", $auths);

                $where=" status=1 and ";
                for($i=0;$i<count($arr);$i++){
                    $id=$arr[$i];
                    $where = $where." id = $id or " ;
                }
                $con=substr($where,0,strlen($where)-3);
                $Mune2=D("admin_auth_rule")->where($con)->select();

                $Mune2=array_filter($Mune2);
                $Mune2=array_values($Mune2);

//var_dump($data);
                $ku=D("admin_auth_rule")->where("type=-1")->order("status asc")->select();

                for($i=0;$i<count($ku);$i++){
                    for($j=0;$j<count($Mune2);$j++){

                        if($ku[$i]["status"]==$Mune2[$j]["type"]){
                            $Mune1[$i]=$ku[$i];break;
                        }
                    }
                }

//var_dump($Mune1);
                $this->assign("Mune1",$Mune1);
                $this->assign("Mune2",$Mune2);
//var_dump($Mune2);







                $auth = $ru["auth"];
//var_dump($auth);exit;
                $list = D("admin_auth_type")->where("id=$auth")->find();
                $auths = $list["auth"];
                $arr = explode(",", $auths);
                $con_name = CONTROLLER_NAME . "/" . ACTION_NAME;
                //   var_dump($con_name);exit;
                if ($con_name != "Index/index") {
                    $rus = D("admin_auth_rule")->where("name='$con_name'")->find();
                    $id = $rus["id"];
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($arr[$i] == $id) {
                            $num = (int)$rus["type"];
                            $this->assign("num",$num);
//echo $id;
                            $this->assign("id",$id);
                            break;
                        } else {
                            $num = -1;
                        }
                    }
                } else {
                    $num =0;
                }
            }
            if($num==-1){
                $this->error("无操作权限", U("Index/index"));
            }
        }else{
            $this->redirect('Login/index');
        }






    }
/**
    private $qudao3=array();//这里声明
    public function _initialize(){
$game_id=1;
        $qudao2=array();
        $db=D("db")->select();
        for($i=0;$i<count($db);$i++){
            $db_ids=$db[$i]["db_id"];
            $connection=db2($game_id,$db_ids);
            $qudao[$i]=M('user','',$connection)->where("source!=' ' and source!='DS' and source!='guest ' ")->field("source")->group("source")->select();
        }

        $qudao2=arr_foreach($qudao);
        for($i=0;$i<count($qudao2);$i++){
            $cid=$qudao2[$i];
            $qudao3[$i]["cid"]=$cid;
            $ras=D("qudao")->where("cid=$cid")->find();
            $qudao3[$i]["name"]=$ras["name"];
        }

        $this->qudao3=$qudao3;//这里赋值
        
    }
**/

}

?>