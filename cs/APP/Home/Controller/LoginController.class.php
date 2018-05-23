<?php
namespace Home\Controller;
use Think\Controller;
header ( "Content-type:text/html;charset=utf-8" );
class LoginController extends Controller {
    // 登陆验证
    private $db = null; // 私有成员变量
    public function __construct() {
        parent::__construct (); // 调用父类的构造函数
        $this->db = M('log'); // 创建数据表访问对象 new Model('admin');
    }
    public  function index(){
 $_SESSION=array();
        $this->display();
    }
    public function login(){
        if(!isset ($_POST)){
            $this->error('页面不存在');
        }else{
            $username=$_POST['username'];
            $password=$_POST['password'];
            $num=D("admin")->where("user_name='$username' and password='$password'")->find();
            $status=$num["status"];
            if($status==-1){
                echo "<script>alert('该账号已禁用')</script>";
                echo "<script>javascript:parent.window.location.href= 'http://106.15.137.174/index.php?m=Admin&c=Login&a=login'</script>";
            }else{
                if($num !=null){
                    $_SESSION["userID"]=$num["id"];
                    $_SESSION["role"]=$num["role"];
                    $_SESSION['username']=$username;
                    $_SESSION['password']=$password;
                    $_SESSION['name']=$num['name'];
                    $data=array();
                    $data["name"]=$_SESSION["username"];
                    $data['ip']=get_client_ip();
                    $data['time']=date('Y-m-d H:i:s',time());
                    $M = D("log");
                    $num=$M->add($data);
                    $this->success ( "登陆成功", U ( "Index/index" ) );
                }else{
                    $this->error ( "登陆失败" );
                }
            }
        }
    }



    public function dologin(){
        if(isset($_SESSION['username'])){
            unset($_SESSION["username"]);
            if(!isset($_SESSION['username'])){
                $this->success ( "退出成功", U ( "Login/index" ) );
            }

        }
    }

}
//退出



?>