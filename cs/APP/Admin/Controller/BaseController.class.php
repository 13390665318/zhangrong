<?php
namespace Admin\Controller;

use Think\Controller;
class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    
       // $_SESSION["user_id"]=admin;
        if(isset($_SESSION["username"])){
            $username=$user_name=$_SESSION["username"];
            $_SESSION['username'];
            $password=$_SESSION['password'];
            $ru=D("admin")->where("user_name='$user_name' and password='$password'")->find();
            if($ru==null){
                $this->redirect('Login/index');
            }else{
                $auth=$ru["auth"];
                $arr=explode(",", $auth);
                $con_name=CONTROLLER_NAME;
                $rus=D("admin_auth_rule")->where("name='$con_name'")->find();
                $id=$rus["id"];
                for($i=0;$i<count($arr);$i++){
                    if($arr[$i]==$id){
                        $num=$id;
                        break;
                    }else{
                        $num=0;
                    }
                }
                if($num==0){
                    echo "<script>alert('您没有此项操作的权限'); </script>";
                  //  echo "<script>javascript:parent.window.location.href= 'http://www.text.com/CT/index.php?m=Admin&c=Index&a=index'</script>";
                    echo "<script>javascript:parent.window.location.href= 'http://106.15.137.174/Alirefor/index.php?m=Admin&c=Index&a=index'</script>";

                    // echo "<script>javascript:window.history.go(-1);'</script>";
                }
            }
         }else{
            $this->redirect('Login/index');
        }
    
    }
    
}

?>