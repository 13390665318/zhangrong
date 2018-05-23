<?php
namespace  Doc\Controller;

use Think\Controller;
class BaseController extends Controller
{

    public function _initialize()
    {
        if (!session('user_id')) {
            $this->redirect('Login/index');
        } /*elseif (session('user_id') != 0) {
            //即时帐户锁定检查
            $m = M('ApiAdmin');
            $r = $m->where('id=' . session('user_id'))->find();
            if ($r['islock'] == 1) {
                $this->error('您的帐户已被锁定', U('Login/index'));
            }
            //权限检查
            $model = M('ApiAdmin');
            $result = $model->where("name='" . CONTROLLER_NAME . "'")->find();
            if ($result != null) {
                $auth = new Auth();//此函数在think目录下 为权限验证类
                if (!$auth->check(CONTROLLER_NAME, session('user_id'))) {
                    $this->error('您没有权限!');
                }
            }
        }*/
    }
    
    

    /**
     * 空操作
     
    public function _empty()
    {
        layout(false);
        $this->display('Public/404');
    }*/
}