<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/10/25
 * Time: 17:20
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;
use Admin\Controller\GmToolController;

/**
 *注销/制裁
 * @author      xiangxing
 */
class AdjudicationController extends CommonController
{

    /**
     * 注销/制裁 列表
     * */
    public function adjudicationList(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $server = M('config_server',null);
        $list = $server->where()->select();
        $this->assign('server', $list);
        $this->display("adjudication_list");
    }

    /**
     * 查询账号注销历史记录
     * @return int
     * 2:查询出错
     * 3:无查询结果
     * 4:无条件查询
     * */
    public function accountCancellation(){

        if (IS_POST) {
            $where = I('post.');
            if ($where['qtype']!=""&&$where['qvalue']!=""){
                $data = array();
                $data['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_QUERY_USER_DEL_RECORD");
                $data['qtype'] = (int)$where['qtype'];
                $data['qvalue'] = $where['qvalue'];
                $data['qvalue'] = $where['qvalue'];
                $data['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
                $data['count'] = (int)I('post.page')*(int)I('post.rows');
                $info = json_decode(urldecode($this->GetGmToolApi($data)), true);
                $data = array();
                if ($info['code']==100){
                    $data = $info['result'];
                    foreach ($data as $key=>$val){
                        $data[$key]['normalid'] =$info['normalid'];
//                        $data[$key]['deltime'] =date('Y-m-d H:i:s',$val['deltime']);
                        switch ($data[$key]['status']){
                            case 0:$data[$key]['status']= L('public_use_normal');$data[$key]['deltime']= L('prop_info_type_name');break;
                            case 1:$data[$key]['status']= L('public_use_cancellation');break;
                        }
                    }
                    $this->ajaxReturn(array('rows'=>$data,'total'=>$info['sum']));
                }
                $this->ajaxReturn(array('rows'=>3,'total'=>0));
            }
            $this->ajaxReturn(array('rows'=>4,'total'=>0));
        }
        $this->ajaxReturn(array('rows'=>2,'total'=>0));
    }

    /**
     * 查询账号制裁历史记录
     * @return int
     * 2:查询出错
     * 3:无查询结果
     * 4:无条件查询
     * */
    public function sanctionCancellation(){

        if (IS_POST) {
            $server = I('post.zoneid','','intval');
            if ($server!=""){
                $data = array();
                $data['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_LOCK_ROLES");
                $data['zoneid'] = $server;
                $data['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
                $data['count'] = (int)I('post.page')*(int)I('post.rows');
                $info = json_decode(urldecode($this->GetGmToolApi($data)), true);

                $data = array();
                if ($info['code']==100){
                    $data = $info['result'];
                    foreach ($data as $key=>$val){
                        $data[$key]['serverid'] = $server;
//                        $data[$key]['time'] =date('Y-m-d H:i:s',$val['time']);
                    }
                    $this->ajaxReturn(array('rows'=>$data,'total'=>$info['sum']));
                }
                $this->ajaxReturn(array('rows'=>3,'total'=>0));
            }
            $this->ajaxReturn(array('rows'=>4,'total'=>0));
        }
        $this->ajaxReturn(array('rows'=>2,'total'=>0));
    }

    /**
     * 恢复角色
     */
    public function unlockrole(){
        $unlockrole = new GmToolController();
        $unlockrole->unlockrole();
    }

    /**
     * 封号/解封
     */
    public function setRequestUnlock(){
        $setRequestUnlock = new GmToolController();
        $setRequestUnlock->setRequestUnlock();
    }

}