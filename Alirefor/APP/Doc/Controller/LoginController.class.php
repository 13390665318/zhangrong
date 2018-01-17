<?php
namespace Doc\Controller;

use Think\Controller;

class LoginController extends Controller
{

    public function index()
    {
        $this->display();
    }

    /**
     * 登录
     */
    public function doLogin()
    {
        $user = I('post.account');
        $pass = I('post.password');

        if ($user=='admin' && $pass=='admin') {
            session('user_id', $user);
            $this->redirect('IndexEdit/index');
        } else {
            $this->error('账号或密码错误');
        }
    }

    /**
     * 注销
     */
    public function logout()
    {
        session('user_id', null);

        $this->redirect('Login/index');
    }
}