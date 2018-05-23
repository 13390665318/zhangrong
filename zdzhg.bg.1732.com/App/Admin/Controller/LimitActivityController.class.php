<?php
/**
 * Created by PhpStorm.
 * User: xiangxing
 * Date: 2017/11/28
 * Time: 11:59
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 限时活动管理模块
 * @author wangdong
 */class LimitActivityController extends CommonController
{

    protected $condition_type;

    function __construct(){
        parent::__construct();
        $this->condition_type=array(
            '-1'=>L('LIMIT_NO'),
            '0'=>L('LIMIT_EVERY_WIN_PVP'),
            '1'=>L('LIMIT_EVERY_SPORT_WIN'),
            '2'=>L('LIMIT_EVERY_PASTIME_WIN'),
            '3'=>L('LIMIT_EVERY_STAGE_WIN'),
            '4'=>L('LIMIT_TOTAL_SIGN'),
            '5'=>L('LIMIT_EVERY_UINT_UP'),
            '6'=>L('LIMIT_EVERY_OPEN_BOX_NUM'),
            '7'=>L('LIMIT_EVERY_OPEN_SPORT_BOX_NUM'),
            '8'=>L('LIMIT_ACTIVITY_RECHARGE'),
            '9'=>L('LIMIT_EVERY_EVERY_RECHARGE'),
        );
    }



    /**
     * 纯页面
     * */
    public function __call($method, $args)
    {
        $type = $this->getItemType();
        unset($type['5']);
        unset($type['4']);
        $this->assign('itemtype',$type);
        $this->assign('conditiontype',$this->condition_type);
        $this->assign('title_id',textTypeList(8));
        $this->assign('introduce_id',textTypeList(9));
//        if ($method == "limit_add"||$method == "limit_edit"){
//            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_EXCHANGE_AWARD");
//            $info['from'] = 0;k
//            $info['count'] = 2000;
//            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
//            $this->assign('task_list',$returns['awards']);
//        }
        $this->display($method);
    }

    public function limitActivityPage(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('limit_activity_page');
    }


    public function taskListPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_EXCHANGE_AWARD");
            $info['from'] = 0;
            $info['count'] = 10;
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['sum']>10){
                $data = $returns['awards'];
                for ($i=1;$i<intval($returns['sum']/10)+1;$i++){
                    $info['from'] = 10*$i;
                    $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
                    $data = array_merge($data,$returns['awards']);
                }
            }else{
                $data = $returns['awards'];
            }

            $id[] = array(
                'text'=>L('public_use_no_choice'),
                'value'=>'-1',
            );
            foreach ($data as $val){
                $id[] = array(
                    'text'=>$val['id'],
                    'value'=>$val['id'],
                );
            }
            $this->ajaxReturn($id);
        }
    }

    /**
     *查询兑换活动
     * */
    public function limitActivityList(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_EXCHANGE_ACTIVITY");
            $info['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
            $info['count'] = (int)I('post.page')*(int)I('post.rows');
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            $sum =$returns['sum'];
            if ($returns['code']==100){
                $data = $returns['activities'];
                $text = M('language_text',null);
                $langSet = strtolower(cookie('think_language'));
                $language = "chinese";
                switch ($langSet){
                    case "zh-cn":$language ='chinese';break;
                    case "ko-kr":$language ='korean';break;
                    case "en-us":$language ='english';break;
                }

                foreach ($data as $key=>$val){
                    $data[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
                    $text_info = $text->where(array('tid'=>$data[$key]['title']))->find();
                    $data[$key]['titlecomment'] = $text_info[$language];
                    $text_info = $text->where(array('tid'=>$data[$key]['introduce']))->find();
                    $data[$key]['introducecomment'] = $text_info[$language];
                }

                $this->ajaxReturn(array('rows'=>array_reverse($data),'total'=>$sum));
            }
        }

    }

    /**
     * 活动添加请求
     * */
    public function limitAddPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_CREATE_EXCHANGE_ACTIVITY");
            $info['starttime'] = strtotime($info['starttime']);
            $info['endtime'] = strtotime($info['endtime']);
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>11,//操作类别(int)
                    "operationContent"=>'public_info_add_activity',//操作内容(string)
                    "operationReason"=>L('public_info_add_activity')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 活动修改请求
     * */
    public function limitEditPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_UPDATE_EXCHANGE_ACTIVITY");
            $info['starttime'] = strtotime($info['starttime']);
            $info['endtime'] = strtotime($info['endtime']);
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>11,//操作类别(int)
                    "operationContent"=>'public_info_edit_activity',//操作内容(string)
                    "operationReason"=>L('public_info_edit_activity')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 活动删除请求
     * */
    public function limitDeletePost(){
        if (IS_POST){
            $info['activityid'] = I('post.activityid');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_EXCHANGE_ACTIVITY");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>11,//操作类别(int)
                    "operationContent"=>'public_info_delete_activity',//操作内容(string)
                    "operationReason"=>L('public_info_delete_activity')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }



    /**
     *查询兑换奖励
     * */
    public function taskActivityList(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_EXCHANGE_AWARD");
            $info['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
            $info['count'] = (int)I('post.page')*(int)I('post.rows');
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);

            if ($returns['code']==100){
                $sum =$returns['sum'];
                $data = $returns['awards'];
                $type = $this->getItemType();
                $prop = M('config_item',null);
                foreach ($data as $key=>$val){
                    $props1 = $prop->where(array('itemid'=>$data[$key]['id1']))->find();
                    $props2 = $prop->where(array('itemid'=>$data[$key]['id2']))->find();



                    $data[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
                    $data[$key]['type_name1'] =$type[$data[$key]['type1']]==""?L('prop_info_type_name'):$type[$data[$key]['type1']];
                    $data[$key]['type_name2'] =$type[$data[$key]['type2']]==""?L('prop_info_type_name'):$type[$data[$key]['type2']];
                    $data[$key]['ids1'] =  $data[$key]['id1']==-1? $data[$key]['type_name1']:$props1['itemname'];
                    $data[$key]['ids2'] =  $data[$key]['id2']==-1? $data[$key]['type_name2']:$props2['itemname'];
                    $data[$key]['conditioncontent'] = $this->condition_type[$data[$key]['conditiontype']];
                }
                $this->ajaxReturn(array('rows'=>$data,'total'=>$returns['sum']));
            }
        }

    }


    /**
     * 任务添加请求
     * */
    public function taskAddPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_CREATE_EXCHANGE_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
           
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>13,//操作类别(int)
                    "operationContent"=>'public_info_add_task',//操作内容(string)
                    "operationReason"=>L('public_info_add_task')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 任务修改请求
     * */
    public function taskEditPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_UPDATE_EXCHANGE_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>13,//操作类别(int)
                    "operationContent"=>'public_info_edit_task',//操作内容(string)
                    "operationReason"=>L('public_info_edit_task')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 任务删除请求
     * */
    public function taskDeletePost(){
        if (IS_POST){
            $info['awardid'] = I('post.awardid');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_EXCHANGE_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>13,//操作类别(int)
                    "operationContent"=>'public_info_delete_task',//操作内容(string)
                    "operationReason"=>L('public_info_delete_task')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    public function limitReloadPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_RELOAD_ACTIVITY_CONFIG");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

}