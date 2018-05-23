<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/10/16
 * Time: 17:12
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;


/**
 * 公告管理
 * @author
 */
class NoticeController  extends CommonController
{
    /**
     * 公告管理页面
     *
     * */
    public function noticeManage(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');

        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->assign('textType', textTypeList(3));
        $this->assign('platlist',C("PLAT_LIST"));
        $this->display('notice_manage');
    }

    /**
     *公告添加提交
     * @return 返回值为int代表错误
     * @(int)8 数据库信息出错
     * @(int)9 成功发布
     * @(int)30 api接口出错
     */
    public function noticeAddPost(){
        if (IS_POST){
            $info =  $this->fieldFiltering(I('post.'));
            if (is_int($info)){
                $this->ajaxReturn($info);
            }
            $time = time();
            $userInfo = user_info();

            $notice = M('notice_info','zdzhg_');
            $data['operator'] = $userInfo['username'];
            $data['state'] = 0;
            $data['sort'] = $notice->where(array('minlv'=>$info['minlv'],'maxlv'=>$info['maxlv'],'interval'=>$info['interval']))->count()+1;
            $data['reason'] = I('post.operationreason');//操作原因(string)
            $id = $notice->add(array_merge($info,$data));
            if ($id!=false){
                if ($info['start']<$time+60&&$info['end']>$time){
                    $retjson = $this->noticeSaveApi($id,$info,1);
                    if ($retjson['code']==100){
                        $notice->where(array('id'=>$id))->save(array('state'=>1));
                    }else{
                        $notice->where(array('id'=>$id))->delete();
                        $this->ajaxReturn(30);
                    }
                }else{
                    //预定时间发送

                }
                
                $log = array(
                    "platform"=>I('post.platform'),//平台信息(int)
                    "operationType"=>6,//操作类别(int)
                    "operationContent"=>'notice_add_release',//操作内容(string)
                    "operationReason"=>I('post.operationreason')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(9);
            }
            $this->ajaxReturn(8);
        }
    }

    /**
     * 获取公告列表
     * */
    public function noticeListPost(){
        if (IS_POST){
            $notice = M('notice_info','zdzhg_');
            $list = $notice->where()->order('start desc,sort')->page(I('post.page').','.I('post.rows'))->select();

            $text = M('language_text',null);
            $langSet = strtolower(cookie('think_language'));
            $language = "chinese";
            switch ($langSet){
                case "zh-cn":$language ='chinese';break;
                case "ko-kr":$language ='korean';break;
                case "en-us":$language ='english';break;
            }

            foreach ($list as $key=>$val){
                $list[$key]['start'] = date("Y-m-d H:i:s",$list[$key]['start']);
                $list[$key]['end'] = date("Y-m-d H:i:s",$list[$key]['end']);

                $data =$this->outboundProcessing($list[$key]);

                $list[$key]['platform'] = $data['platform'];
                $list[$key]['target_player'] = $data['target_player'];
                $list[$key]['state'] = $data['state'];

                $text_info = $text->where(array('tid'=>$list[$key]['content']))->find();
                $list[$key]['content'] = $text_info[$language];

            }
            $this->ajaxReturn(array('rows'=>$list,'total'=>$notice->where()->count()));
        }
    }

    /**
     *公告移动
     *@return
     * 12:数据出现错误,
     * 13:该数据在同组中已经第一条
     * 14:该数据不能这样移动
     * 15:移动成功
     * */
    public function noticeMove(){
        if (IS_POST){
            $id = I('post.id','0','intval');
            $notice = M('notice_info','zdzhg_');
            $original =  $notice->where(array('id'=>$id,'state'=>0))->find();
            if ($original!=false){
                $sort = I('post.sort','0','intval');
                $where['state'] = 0;
                $where['sort'] = $sort==2?$original['sort']-1:$original['sort']+1;
                if ($where['sort']>0){
                    $where['start'] = $original['start'];
                    $where['end'] = $original['end'];
                    $where['interval'] = $original['interval'];
                   if ($exchange = $notice->where($where)->find()){
                       $notice->where(array('id'=>$exchange['id']))->save(array('sort'=>$original['sort']));
                       $notice->where(array('id'=>$original['id']))->save(array('sort'=>$exchange['sort']));
                       $this->ajaxReturn(15);
                   }
                    $this->ajaxReturn(14);
                }else{
                    $this->ajaxReturn(13);
                }
            }
            $this->ajaxReturn(12);
        }
    }

    /**
     * 公告编辑页面
     * @get id:公告id
     * */
    public function onticeEdit(){
        $id = I('get.id','0','intval');
        $notice = M('notice_info','zdzhg_')->where(array('id'=>$id))->find();
        $notice['start'] = date("Y-m-d H:i;s", $this->GetSystemTimes());
        $notice['end'] = date("Y-m-d H:i;s", $this->GetSystemTimes());
        $this->assign('notice',$notice);
        $text = textTypeList(3);
        switch ($notice['platform']){
            case -1:$plat=array('-1'=>L('public_use_platform_whole'),'0'=>L('public_use_platform_ios'),"1"=>L('public_use_platform_aos'));break;
            case 0:$plat=array('0'=>L('public_use_platform_ios'),'-1'=>L('public_use_platform_whole'),"1"=>L('public_use_platform_aos'));break;
            case 1:$plat=array("1"=>L('public_use_platform_aos'),'-1'=>L('public_use_platform_whole'),'0'=>L('public_use_platform_ios'));break;
        }
        $this->assign('platlist',$plat);
        $this->assign('textType', $text);
        $this->display('notice_edit');
    }

    /**
     * 公告修改
     * 20:公告修改失败,
     * 21:已成功修改该公告
     * */
    public function onticeEditPost(){

        if (IS_POST){
            $info =  $this->fieldFiltering(I('post.'));
            $ids = $info['id'];
            unset($info['id']);
            if (is_int($info)){
                $this->ajaxReturn($info);
            }
            $time = time();
            $userInfo = user_info();

            $notice = M('notice_info','zdzhg_');
            $data['operator'] = $userInfo['username'];
            $data['state'] = 0;
            $data['sort'] = $notice->where(array('minlv'=>$info['minlv'],'maxlv'=>$info['maxlv'],'interval'=>$info['interval']))->count()+1;
            $data['reason'] = I('post.operationreason');//操作原因(string)
            $id = $notice->add(array_merge($info,$data));
            if ($id!=false){
                if ($info['start']<$time+60&&$info['end']>$time){
                    $retjson = $this->noticeSaveApi($id,$info,1);
                    if ($retjson['code']==100){
                        $notice->where(array('id'=>$id))->save(array('state'=>1));
                    }else{
                        $notice->where(array('id'=>$id))->delete();
                        $this->ajaxReturn(30);
                    }
                }else{
                    //预定时间发送

                }
                $notice->where(array('id'=>I('post.id')))->delete();
                $log = array(
                    "platform"=>I('post.platform'),//平台信息(int)
                    "operationType"=>6,//操作类别(int)
                    "operationContent"=>'notice_add_release',//操作内容(string)
                    "operationReason"=>I('post.operationreason')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(9);
            }
            $this->ajaxReturn(8);
        }

    }

    /**
     * 公告结束
     *@return
     * 16:不存在该数据,
     * 17:已成功结束该公告
     * */
    public function noticeEnd(){
        if (IS_POST){
            $id = I('post.id','0','intval');
            $notice = M('notice_info','zdzhg_')->where(array('id'=>$id,'state'=>array('NEQ',2)))->find();
            if ($notice != false){
                $tem = "";
                if ($notice['state']==0){
                    $tem = M('notice_info','zdzhg_')->where(array('id'=>$id,'state'=>array('NEQ',2)))->save(array('state'=>2));
                }elseif ($notice['state']==1){
                    $retjson = $this->noticeEndApi($notice['id']);
                    if ($retjson['code']==100){
                        $tem = M('notice_info','zdzhg_')->where(array('id'=>$id,'state'=>array('NEQ',2)))->save(array('state'=>2));
                    }
                }
                if ( $tem!= false){
                    $log = array(
                        "platform"=>$notice['platform'],//平台信息(int)
                        "operationType"=>6,//操作类别(int)
                        "operationContent"=>'notice_no_release',//操作内容(string)
                        "operationReason"=>I('post.operationreason')//操作原因(string)
                    );
                    $this->configOperation($log);

                    $this->ajaxReturn(17);
                }
            }
            $this->ajaxReturn(16);
        }
    }

    /**
     * 公告删除
     * @return
     * 10:删除失败,
     * 11:删除成功
     * */
    public function noticeDelete(){
        if (IS_POST){
            $ids = I('post.id','0','intval');

            $log = array(
                "platform"=>-1,//平台信息(int)
                "operationType"=>6,//操作类别(int)
                "operationContent"=>'notice_delete_release',//操作内容(string)
                "operationReason"=>I('post.operationreason')//操作原因(string)
            );
            $this->configOperation($log);

            $notice = M('notice_info','zdzhg_')->where(array('id'=>array('in',$ids),'state'=>2))->delete();

            if ($notice!=false){
                $this->ajaxReturn(11);
            }
            $this->ajaxReturn(10);
        }
    }
    
    /**
     * 公告定时api
     *
     * */
    public function sendNoticeApi(){
//        if (IS_POST){
            $time = time();
            $notice=M('notice_info','zdzhg_');
            $notices = $notice->where(array('start'=>array('LT',$time),'state'=>array('NEQ',2)))->order('start desc,sort')->select();
            foreach ($notices as $val){
                if ($val['state']==1&&$val['end']<$time){
                    $notice->where(array('id'=>$val['id']))->save(array('state'=>2));
                }elseif ($val['state']==0){
                    $info = array(
                        'platform'=>$val['platform'],
                        'minlv'=>$val['minlv'],
                        'maxlv'=>$val['maxlv'],
                        'rankmin'=>$val['rankmin'],
                        'rankmax'=>$val['rankmax'],
                        'start'=>$val['start'],
                        'end'=>$val['end'],
                        'interval'=>$val['interval'],
                        'content'=>$val['content'],
                    );
                    $retjson = $this->noticeSaveApi($val['id'],$info,1);
                    if ($retjson['code']==100){
                        $notice->where(array('id'=>$val['id']))->save(array('state'=>1));
                        echo L("public_send_successfully")."<br />";
                    }
                }

            }

    }

    /**
     * 公告发布以及修改api
     * */
    private function noticeSaveApi($id,$info,$state){
        $data = array();
        switch ($state){
            case 1:  $data['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_ADD_PUBLISH_ANNOUNCEMENT");break;
            case 2:  $data['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_UPDATE_PUBLISH_ANNOUNCEMENT");break;
        }
        $data['noticeid'] = $id;

        $params =array_merge($data,$info);
        return json_decode(urldecode($this->GetGmToolApi($params)),true);

    }

    /**
     * 公告结束api
     * */
    private function noticeEndApi($id){
        $data['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_DEL_PUBLISH_ANNOUNCEMENT");
        $data['noticeid'] = $id;
        return json_decode(urldecode($this->GetGmToolApi($data)),true);
    }

    /**
     * 数据出站处理
     * */
    private function outboundProcessing($list){
        $number =  ''; //编号
        if ($list['platform']==-1&&$number==''){
            $data['platform'] = L('public_use_platform_whole');
        }elseif ($list['platform']==1&&$number==''){
            $data['platform'] =  L('public_use_platform_aos');
        }elseif ($list['platform']==0&&$number==''){
            $data['platform'] =  L('public_use_platform_ios');
        }else{
            $data['platform'] = L('public_use_platform_number');
        }

        switch ($list['state']){
            case 0:$data['state']=L('public_use_notice_state_one');break;
            case 1:$data['state']=L('public_use_notice_state_two');break;
            case 2:$data['state']=L('public_use_notice_state_three');break;
        }

       $state = true;
        if ($list['minlv']!=""&&$list['maxlv']!=""&&$number==''){
            $data['target_player'] = L('public_use_level')."： ".$list['minlv']." <= ".L('public_use_player_level')." <= ".$list['maxlv']."<br/>";
            $state=false;
        }
        if ($list['rankmin']!=""&&$list['rankmax']!=""&&$number==''){
            $data['target_player'] .= L('public_use_rank')."： ".$list['rankmin']." <= ".L('public_use_player_rank')." <= ".$list['rankmax'];
            $state=false;
        }
        if ($state){
            $data['target_player'] = L('public_use_player_all');
        }
        return $data;
    }


    /**
     * 字段过滤以及转型
     * @return 返回值为int代表错误
     * (int)1 平台信息出错
     * (int)2 等级信息错误
     * (int)3 段位信息错误
     * (int)5 时间信息错误
     * (int)6 间隔时间信息错误
     * (int)7 公告内容信息错误
     * */
    private function fieldFiltering($data){
        $info = array();

        switch($data['platform']){
            case "0": $info['platform'] = 0;break;//ios
            case "1": $info['platform'] = 1;break;//android
            case "-1": $info['platform'] = -1;break;//全平台
            default:return 1;
        }
        
        if ($data['minlv']!=""&&$data['maxlv']!=""){
            $info['minlv'] = (int)$data['minlv'];
            $info['maxlv'] = (int)$data['maxlv'];
        }elseif ($data['minlv']==""&&$data['maxlv']==""){
            $info['minlv'] = 1;
            $info['maxlv'] = 150;
        }else{
            return 2;
        }//最小等级以及最大等级

        if ($data['rankmin']!=""&&$data['rankmax']!=""){
            $info['rankmin'] = (int)$data['rankmin'];
            $info['rankmax'] = (int)$data['rankmax'];
        } elseif ($data['rankmin']==""&&$data['rankmax']==""){
            $info['rankmin'] = 1;
            $info['rankmax'] = 20;
        }else{
            return 3;
        }//最小段位以及最大段位

        if ($data['start']!=""&&$data['end']!=""){
            $info['start'] = strtotime($data['start']);
            $info['end'] = strtotime($data['end']);
        }elseif ($data['start']!=$data['end']){
            return 5;
        }//开始时间以及结束时间

        if ($data['interval']!=""){
            $info['interval'] = (int)$data['interval'];
        } else{
            return 6;
        }//间隔时间

        if ($data['content']!=""){
            $info['content'] = (string)$data['content'];
        } else{
            return 7;
        }//公告内容

        if ($data['worldid']!=""){
            $info['worldid'] = (int)$data['worldid'];
        } else{
            return 8;
        }//地区id


        return $info;
    }
}