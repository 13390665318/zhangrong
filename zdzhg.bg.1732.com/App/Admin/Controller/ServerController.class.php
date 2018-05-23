<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/10/24
 * Time: 17:18
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;


/**
 *服务器管理
 * @author      xiangxing
 */
class ServerController extends CommonController
{
    /**
     * 服务器列表页面
     *
     * */
    public function serverList(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('server_list');
    }

    /**
     * 新服务器添加页面
     * */
    public function serverAdd(){
        $this->display('server_add');
    }

    /**
     * 获取服务器列表
     * @access public
     * @param mixed page 请求的页码
     * @param mixed rows 每页的数量
     * @return array lsit 数据数组
     * @return int total 数据总个数
     * */
    public function serverListPost(){
        if (IS_POST){
            $server = M('config_server',null);
            $list = $server->where()->order('id desc')->page(I('post.page').','.I('post.rows'))->select();
            $sum = $server->where()->count();
            foreach ($list as $key=>$val){
                $list[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
            }
            $this->ajaxReturn(array('rows'=>$list,'total'=>$sum));
        }
    }

    /**
     * 新服务器添加
     *@return 返回值为int
     * @(int)2 服务器添加成功
     * @(int)3 服务器添加失败
     * @(int)4 服务器id重复
     * */
    public function serverAddPost(){
        if (IS_POST){
            $data = I('post.');
            $userInfo = user_info();
            $data['operator'] = $userInfo['username'];
            $server = M('config_server',null);
            if ($server->where(array('serverid'=>$data['serverid']))->count()>0){
                $this->ajaxReturn(4);
            }
            $return = $server->add($data);
            if ($return!=false){
                $this->ajaxReturn(2);
            }
            $this->ajaxReturn(3);
        }
    }
    
    /**
     * 服务器删除
     * @(int)2 服务器删除成功
     * @(int)3 服务器删除失败
     * */
    public function serverDeltePost(){
        $id = I('post.id','0','intval');
        if (is_int($id)&&$id>0){
            $server = M('config_server',null);
            if ($server->where(array('id'=>$id))->count()==1){
                $return = $server->where(array('id'=>$id))->delete();
                if ($return!=false){
                    $this->ajaxReturn(2);
                }
            }
        }
        $this->ajaxReturn(3);
    }

}