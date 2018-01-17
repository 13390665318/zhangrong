<?php
namespace Api\Controller;

use Think\Controller;
class BaseController extends Controller
{
    public function _initialize() {
        header('Content-Type:text/html;charset=utf-8');
    }
    
    /**
     * 作者：wanglidong
     * 功能：不存的接口
     */
    public function _empty() {
        echo_error(-2, '接口不存在');
    }
   
}

?>