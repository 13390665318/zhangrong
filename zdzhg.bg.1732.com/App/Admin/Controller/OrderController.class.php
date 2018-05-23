<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/12/8
 * Time: 15:11
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 订单查询
 * @author
 */
class OrderController  extends CommonController
{

    protected $pay_type;
    protected $order_status;
    protected $order_name;

    function __construct(){
        parent::__construct();
        $this->pay_type=array(
            L('prop_info_type_name'),
            L('order_search_google_platform'),
            L('order_search_apple_platform'),
        );
        $this->order_status=array(
            L('order_search_payment_no'),
            L('order_search_payment_yes'),
            L('order_search_reissue_yes'),
            L('order_search_refund_yes')
        );
        $this->order_name=array(
            'bc_ios_cash_001'=>L('goods_name_code_one'),
            'bc_ios_cash_002'=>L('goods_name_code_two'),
            'bc_ios_cash_003'=>L('goods_name_code_three'),
            'bc_ios_cash_004'=>L('goods_name_code_four'),
            'bc_ios_cash_005'=>L('goods_name_code_five'),
            'bc_ios_cash_006'=>L('goods_name_code_six'),
            'bc_aos_cash_01'=>L('goods_name_code_one'),
            'bc_aos_cash_02'=>L('goods_name_code_two'),
            'bc_aos_cash_03'=>L('goods_name_code_three'),
            'bc_aos_cash_04'=>L('goods_name_code_four'),
            'bc_aos_cash_05'=>L('goods_name_code_five'),
            'bc_aos_cash_06'=>L('goods_name_code_six'),
        );
    }

    /**
     * 订单主页面
     * */
    public function orderInfo(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('order_info');
    }

    /**
     * 订单列表页面
     * */
    public function orderList(){
        $this->display('order_list');
    }

    /**
     * 订单列表请求页面
     * */
    public function orderListPost(){
        if (IS_POST){
            if (I('post.type')){
                $type = I('post.type',0,'intval');
                $info['qtype'] = I('post.type',0,'intval');
                switch ($type){
                    case 0: $info['qtype']= $type;break;
                    case 1: $info['qtype']= $type;break;
                    case 6: $info['qtype']= 3;break;
                    case 7: $info['qtype']= 3;break;
                    default:$info['qtype'] = 2;
                }
            }else{
                $info['qtype'] = 0;
            }

            if (I('post.codeid')){
                $info['qvalue'] = I('post.codeid',0,'intval');
            }else{
                $info['qvalue'] = -1;
            }

            if (I('post.time')){
                $info['time'] = strtotime(I('post.time'));
            }else{
                $info['time'] = -1;
                $this->ajaxReturn(array('rows'=>false,'total'=>0));
            }

            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_PAY_ORDER");
            $info['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
            $info['count'] = (int)I('post.page')*(int)I('post.rows');
//            $this->ajaxReturn(array('rows'=>json_encode($info),'total'=>0));
//            $returns = array(
//                'code'=>100,
//                'sum'=>1,
//                'result'=>[
//                    array(
//                        'id'=>30013,
//                        'status'=>0,
//                        'goods'=>3,
//                        'paytype'=>0,
//                        'time'=>'2017-11-22 16:19:07',
//                        'product'=>'bc_aos_cash_03',
//                        'purchaseid'=>'sadfsadfas',
//                    )
//                ]
//            );
//
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);

            $sum =$returns['sum'];
            if ($returns['code']==100){
                $data = $returns['result'];
                $type = array(
                    L('goods_name_code_one')=>'80',
                    L('goods_name_code_two')=>'500',
                    L('goods_name_code_three')=>'1200',
                    L('goods_name_code_four')=>'2500',
                    L('goods_name_code_five')=>'6500',
                    L('goods_name_code_six')=>'14000',
                );
                foreach ($data as $key=>$val){
                    $data[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
                    $data[$key]['paytypename'] = $this->pay_type[$data[$key]['paytype']];
                    $data[$key]['statusname'] = $this->order_status[$data[$key]['status']];
                    $data[$key]['goodsname'] = $this->order_name[$data[$key]['product']];
                    $data[$key]['goodsnamenum'] = $type[$this->order_name[$data[$key]['product']]];
                }

                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>12,//操作类别(int)
                    "operationContent"=>'public_info_query_order',//操作内容(string)
                    "operationReason"=>L('public_info_query_order')//操作原因(string)
                );
                $this->configOperation($log);

                $this->ajaxReturn(array('rows'=>array_reverse($data),'total'=>$sum));
            }else{
                $this->ajaxReturn(array('rows'=>$returns,'total'=>0));
            }
        }
    }

    /**
     * 订单状态修改页面
     * */
    public function orderEdit(){
        $this->assign('order_status',$this->order_status);
        $this->display('order_edit');
    }

    /**
     * 订单状态修改请求
     * */
    public function orderEditPost(){
        if (IS_POST){
            $info = I('post.');
            unset($info['role']);
            $info['querytime'] = strtotime($info['querytime']);
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_MODIFY_PAY_ORDER_STATUS");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']=100){
                $log = array(
                    "operationType"=>12,//操作类别(int)
                    "roleCode"=>I('post.role'),//角色编号(string)
                    "operationContent"=>'public_info_state_order',//操作内容(string)
                    "operationReason"=>L('public_info_state_order')//操作原因(string)
                );
                $this->configOperation($log);

                $this->ajaxReturn(1);
            }
            $this->ajaxReturn($returns);
        }
    }


    /**
     * 补偿记录页面
     * */
    public function compensateList(){
        $this->display('compensate_list');
    }

    /**
     * 补偿邮件
     * */
    public function compensateEdit(){
        $this->assign('textemailtitlelist',textTypeList("1"));
        $this->assign('textemailcontentlist',textTypeList("2"));
        $this->assign('goods_type',array(
            '80'=>L('goods_name_code_one'),
            '500'=>L('goods_name_code_two'),
            '1200'=>L('goods_name_code_three'),
            '2500'=>L('goods_name_code_four'),
            '6500'=>L('goods_name_code_five'),
            '14000'=>L('goods_name_code_six'),
        ));
        $this->display('compensate_edit');
    }

    /**
     * 补偿邮件提交
     * */
    public function compensateEditPost(){
        if (IS_POST){
            $receive = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_SEND_MAIL_BY_ROLES_NUMBER");
            $info['roles'] = $receive['roles'];
            $info['sendtime'] = time();
            $info['deltime'] = strtotime(date('Y-m-d H:i:s',strtotime('+1 month')));
            $info['title'] = $receive['title'];
            $info['content'] = $receive['content'];
            $info['awardcount'] =1;
            $info['type0'] =0;
            $info['id0'] =-1;
            $info['count0'] = $receive['count'];
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);

            if ($returns['code']=100){
                $add = array(
                    'role'=>$info['roles'],
                    'start_time'=>$info['sendtime'],
                    'goods_code'=>$receive['goods_code'],
                    'goods_name'=> $this->order_name[$receive['goods_code']],
                    'compensate'=>$receive['compensate_name'],
                );
                $compensate_record = M('compensate_record',null)->add($add);
                $log = array(
                    "roleCode"=>$receive['roles'],//角色编号(string)
                    "operationType"=>12,//操作类别(int)
                    "operationContent"=>'public_info_compensate_order',//操作内容(string)
                    "operationReason"=>$receive['reason']//操作原因(string)
                );
                $this->configOperation($log);
                if ($compensate_record>=1){
                    $this->ajaxReturn(1);
                }


            }
            $this->ajaxReturn($returns);
        }
    }

    /**
     * 补偿记录请求
     * */
    public function compensateListPost (){
        if (IS_POST){
            $compensate_record = M('compensate_record',null);
            $receive = I('post.');
            $where = array();
            if ($receive['type']==1){
                $where['role'] = $receive['codeid'];
            }
            if ($receive['starttime']){
                $where['start_time'] = array(
                    'GT',strtotime($receive['starttime'])
                );
            }
            if ($receive['endtime']){
                $where['start_time'] = array(
                    'LT',(strtotime($receive['endtime'])+3600*24)
                );
            }
            if ($receive['endtime']&&$receive['starttime']){
                $where['start_time'] = array(array('gt',strtotime($receive['starttime'])),array('lt',(strtotime($receive['endtime'])+3600*24))) ;
            }

            $list = $compensate_record->where($where)->page(I('post.page').','.I('post.rows'))->select();
            $sum = $compensate_record->where($where)->count();
            foreach ($list as $key=>$val){
                $list[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
                $list[$key]['start_time'] = date('Y-m-d H:i:s',$list[$key]['start_time']);
            }
            $this->ajaxReturn(array('rows'=>$list,'total'=>$sum));
        }
    }
}