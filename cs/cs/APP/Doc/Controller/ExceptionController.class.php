<?php


namespace Doc\Controller;


use Think\Controller;

class ExceptionController extends Controller {

    /**
     * 作者：wanglidong
     * 功能：api异常日志
     */
    public function api() {

        if (empty($_GET['p']))
            $_GET['p'] = 1;
        $r = M('ApiException')->order('id desc')->page($_GET['p'] . ',10')->select();

        foreach ($r as $k => $v) {
            $v['errmsg']     = json_decode($v['errmsg'], true);
            $r[$k]['errmsg'] = $v['errmsg']['message'] . '<br>' . $v['errmsg']['file'] . '（' . $v['errmsg']['line'] . '）';
            //$v['post_data']  = json_decode($v['post_data'], true);
            //foreach ($v['post_data'] as $key => $value) {
            //    $r[$k]['post_data'] .= json_encode($value) . '<br>';
            //}
        }
        $this->list = $r;


        $count = M('ApiException')->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show  = $Page->show();// 分页显示输出
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }

    /**
     * 作者：wanglidong
     * 功能：删除
     */
    public function apiDel() {
        foreach ($_POST as $k => $v) {
            if (!empty($where)) {
                $where .= ',' . "$k";
            } else {
                $where = "$k";
            }
        }
        M('ApiException')->delete($where);

        $this->success('成功');
    }
}