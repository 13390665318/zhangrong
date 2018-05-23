<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/11/21
 * Time: 13:43
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 *特惠商品信息模块
 * @author xiangxing
 */
class PreferentialController extends CommonController
{

    protected $goods_type;
    protected $money_type;
    protected $open_status;


    function __construct(){
        parent::__construct();
       $this->goods_type=array(
           L('public_goods_type_one'),
           L('public_goods_type_two'),
           L('public_goods_type_three'),
           L('public_goods_type_four')
       );
       $this->money_type=array(
           L('preferential_money_type_one'),
           L('preferential_money_type_two'),
           L('preferential_money_type_three'),
       );
       $this->open_status=array(
           L('preferential_open_status_one'),
           L('preferential_open_status_two'),
           L('preferential_open_status_three'),
       );

    }

    /**
     * 特惠商品主页面
     * */
    public function preferentialGoods(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('preferential_goods');
    }

    /**
     * 特惠商品页面
     * */
    public function preferentialList(){
        $this->display('preferential_list');
    }

    /**
     * 特惠商品添加页面
     * */
    public function preferentialAdd(){
        $this->assign('shop_name_id',textTypeList(7));
        $this->assign('shop_multiple_id',textTypeList(6));
        $this->assign('goods_type',$this->goods_type);
        $this->assign('money_type',$this->money_type);
        $this->assign('open_status',$this->open_status);
        $this->display('preferential_add');
    }

    /**
     * 特惠商品修改页面
     * */
    public function preferentialEdit(){

        if (!($shop_name_id=textTypeList(7,1)))
            $shop_name_id=array();
        if (!($shop_multiple_id=textTypeList(6,1)))
            $shop_multiple_id=array();
        $this->assign('shop_name_id',addslashes(json_encode(array_merge(array(array('value'=>'-1','label'=>L("public_use_no_choice"))),$shop_name_id))));
        $this->assign('shop_multiple_id',addslashes(json_encode(array_merge(array(array('value'=>'-1','label'=>L("public_use_no_choice"))),$shop_multiple_id))));
        $this->assign('goods_type',$this->goods_type);
        $this->assign('money_type',$this->money_type);
        $this->assign('open_status',$this->open_status);
//        $this->assign('award_list',$this->rewardListAllPost());
        $this->display('preferential_edit');
    }

    /**
     * 特惠商品详细信息
     *
     * */
    public function preferentialInfo(){
        if (!($shop_name_id=textTypeList(7,1)))
            $shop_name_id=array();
        if (!($shop_multiple_id=textTypeList(6,1)))
            $shop_multiple_id=array();
        $this->assign('shop_name_id',addslashes(json_encode(array_merge(array(array('value'=>'-1','label'=>L("public_use_no_choice"))),$shop_name_id))));
        $this->assign('shop_multiple_id',addslashes(json_encode(array_merge(array(array('value'=>'-1','label'=>L("public_use_no_choice"))),$shop_multiple_id))));
        $this->assign('goods_type',$this->goods_type);
        $this->assign('money_type',$this->money_type);
        $this->assign('open_status',$this->open_status);
        $this->display('preferential_info');
    }

    /**
     * 特惠商品批量添加页面
     *
     * */
    public function batchAdd(){
       $this->display('batch_add');
    }

    /**
     * 特惠商品列表请求
     * */
    public function preferentialListPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_SHOP_GOODS");
            $info['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
            $info['count'] = (int)I('post.rows');
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $list = $returns['goods'];
                $sum = $returns['sum'];

                foreach ($list as $key=>$val){
                    $list[$key]['moneytypename'] = $this->money_type[$list[$key]['moneytype']];
                    $list[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
                }

                $this->ajaxReturn(array('rows'=>$list,'total'=>$sum));
            }
            $this->ajaxReturn(array('rows'=>$returns,'total'=>$sum));
        }
        $this->ajaxReturn(0);
    }

    /**
     * 特惠商品添加请求
     * 0 发布失败
     * 1 发布成功
     * 2 ID重复
     * */
    public function preferentialAddPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_ADD_SHOP_GOODS");
            $info['starttime'] = strtotime($info['starttime']);
            $info['endtime'] = strtotime($info['endtime']);
//            $this->ajaxReturn($info);
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>9,//操作类别(int)
                    "operationContent"=>'public_info_add_preferential',//操作内容(string)
                    "operationReason"=>L('public_info_add_preferential')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }else{
                $this->ajaxReturn($returns['code']);
            }
        }
    }

    /**
     * 特惠商品修改请求
     *
     * */
    public function preferentialEditPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_MODIFY_SHOP_GOODS");
            $info['starttime'] = strtotime($info['starttime']);
            $info['endtime'] = strtotime($info['endtime']);
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>9,//操作类别(int)
                    "operationContent"=>'public_info_edit_preferential',//操作内容(string)
                    "operationReason"=>L('public_info_edit_preferential')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
        }
        $this->ajaxReturn($returns['code']);
    }

    /**
     * 特惠商品删除请求
     * */
    public function preferentialDeletePost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_SHOP_GOODS");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if($returns['code']==100){
                $this->ajaxReturn(1);
            }
        }
        $this->ajaxReturn(0);
    }




    /**
     * 奖励列表页面
     * */
    public function rewardList(){
        $this->display('reward_list');
    }

    /**
     * 奖励添加页面
     * */
    public function rewardAdd(){
        $this->assign('itemtype',$this->getItemType());
        $this->display('reward_add');
    }

    /**
     * 奖励修改页面
     * */
    public function rewardEdit(){
        $this->assign('itemtype',$this->getItemType());
        $this->display('reward_edit');
    }

    /**
     * 奖励列表添加请求
     * @ return 2id重复
     * */
    public function rewardAddPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_ADD_SHOP_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>10,//操作类别(int)
                    "operationContent"=>'public_info_add_reward',//操作内容(string)
                    "operationReason"=>L('public_info_add_reward')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 奖励列表修改请求
     * @ return 2id重复
     * */
    public function rewardEditPost(){
        if (IS_POST){
            $info = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_MODIFY_SHOP_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>10,//操作类别(int)
                    "operationContent"=>'public_info_edit_reward',//操作内容(string)
                    "operationReason"=>L('public_info_edit_reward')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 奖励列表删除请求
     * */
    public function rewardDeletePost(){
        if (IS_POST){
            $info  = I('post.');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_SHOP_AWARD");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>10,//操作类别(int)
                    "operationContent"=>'public_info_delete_reward',//操作内容(string)
                    "operationReason"=>L('public_info_delete_reward')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }

    /**
     * 奖励列表请求
     * */
    public function rewardListPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_SHOP_AWARD");
            $info['from'] = (int)I('post.page')*(int)I('post.rows')-(int)I('post.rows');
            $info['count'] = 10;
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            $list = $returns['awards'];
            $sum = $returns['sum'];
            $type = $this->getItemType();
            $type['-1'] = L('prop_info_type_name');
            $prop = M('config_item',null);
            foreach ($list as $key=>$val){
                foreach ($list[$key]['items'] as $k=>$v){

                    $list[$key]['awardtype'.$k] =$type[$v['type']];
                    $list[$key]['type'.$k] =$v['type'];
                    $props = $prop->where(array('itemid'=>$v['id'],'itemtype'=>$v['type']))->find();
                    $list[$key]['awarditem'.$k] = $v['id']==-1?$type[$v['type']]:$props['itemname'];
//                    if (){
//
//                    }
                    $list[$key]['item'.$k] = $v['id'];

                    $list[$key]['awardcount'.$k] = $v['count'];
                    $list[$key]['count'.$k] = $v['count']==-1?L('prop_info_type_name'):$v['id'];

                }
                $list[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
            }
            $this->ajaxReturn(array('rows'=>$list,'total'=>$sum));
        }
    }

    /**
     * 商店重载请求
     * */
    public function shopReloadPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_RELOAD_SHOP_CONFIG");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $this->ajaxReturn(1);
            }
            $this->ajaxReturn(0);
        }
    }


    /**
     * 奖励列表全部请求
     * */
    public function rewardListAllPost(){
        $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_SHOP_AWARD");
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
            'value'=>'',
        );
        foreach ($data as $val){
            $id[] = array(
                'text'=>$val['id'],
                'value'=>$val['id'],
            );
        }
        $this->ajaxReturn($id);
    }

    public function sss(){
        phpinfo();
    }
    public function upLoadOpen(){
        $config = array(
//            'maxSize'    =>    3145728,
//            'autoSub'    =>    false,
//            'saveName'    =>    '',
//            'hash'    =>    false,
//            'exts'    =>    array('jpg', 'gif', 'png', 'jpeg'),
//            'rootPath'    =>    './Shop/',
            'maxSize'    =>     3145728,
            'rootPath'   =>     './Shop/',
            'savePath'   =>     '',
            'saveName'   =>     array('uniqid',''),
            'exts'       =>     array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>     true,
            'subName'    =>     array('date','Ymd'),
        );
        $ftpConfig     =    array(
            'host'     => '117.52.167.15', //服务器
            'port'     => 2121, //端口
            'timeout'  => 90, //超时时间
            'username' => '433-bfc', //用户名
            'password' => '433bfc!@#', //密码
//            'pasv'     => true, //是否开启被动模式,true开启,默认不开启
//            'ssl'     => false, //ssl连接,默认不开启
//            'host'     => '172.30.110.200', //服务器
//            'port'     => 2121, //端口
//            'pasv'     => true, //是否开启被动模式,true开启,默认不开启
//            'ssl'     => false, //ssl连接,默认不开启
//            'timeout'  => 90, //超时时间
//            'username' => '433live\bfc-svcop', //用户名
//            'password' => '433b!fc!@#11', //密码
            );
        $upload = new \Think\Upload($config,'Ftp',$ftpConfig);// 实例化上传类
//        $upload->maxSize   =     3145728 ;// 设置附件上传大小
//        $upload->autoSub = false;
//        $upload->saveName  = '';
//        $upload->replace = true;//同名覆盖
//        $upload->hash  = false;
//        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//        $upload->rootPath  =     './Public'; // 设置附件上传根目录
//        $upload->savePath  =     '/upload/'; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['icon']);
//        $this->error($info);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
//            $this->error(L('public_flie_upload_error'));
        }else{// 上传成功
            $this->success(L('public_flie_upload_success'));
        }
    }

}