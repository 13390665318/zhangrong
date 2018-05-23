<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/11/7
 * Time: 9:39
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 文本管理
 * @author
 */
class TextController  extends CommonController
{
    /**
     * 文本管理页面
     * */
    public function textList(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('text_list');
    }

    /**
     * 文本列表请求
     * */
    public function textListPost(){
        if (IS_POST){
            $text = M('language_text',null);
            $list = $text->where()->order('tid desc')->page(I('post.page').','.I('post.rows'))->select();
            $sum = $text->where()->count();
            foreach ($list as $key=>$val){
                $list[$key]['types'] = $list[$key]['type'];
                switch ($val['type']){
                    case 1:$list[$key]['type'] = L('text_option_type_one');break;
                    case 2:$list[$key]['type'] = L('text_option_type_two');break;
                    case 3:$list[$key]['type'] = L('text_option_type_three');break;
                    case 4:$list[$key]['type'] = L('text_option_type_four');break;
                    case 6:$list[$key]['type'] = L('text_option_type_six');break;
                    case 7:$list[$key]['type'] = L('text_option_type_seven');break;
                    case 8:$list[$key]['type'] = L('activity_limit_title');break;
                    case 9:$list[$key]['type'] = L('activity_limit_introduce');break;
                }
                $list[$key]['sumnum'] = $sum-((I('post.page')-1)*I('post.rows')+$key);
            }
            $this->ajaxReturn(array('rows'=>$list,'total'=>$sum));
        }
    }

    /**
     * 文本添加页面
     * */
    public function textAdd(){
        $this->display('text_add');
    }

    /**
     * 文本添加请求
     * */
    public function textAddPost(){
        if (IS_POST){
            $data = I('post.');
            $text = M('language_text',null);
            $userInfo = user_info();
            $data['operator'] = $userInfo['username'];
            $tid = $text->add($data);

            if ($tid>999&&!(in_array('', $data))){
                $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_ADD_LANGUAGE_TEXT");
                $info['tid'] = $tid;
                $info['Chinese'] = $data['chinese'];
                $info['Vietnamese'] = $data['vietnamese'];
                $info['ChineseTraditional'] = $data['chinese_traditional'];
                $info['English'] = $data['english'];
                $info['Korean'] = $data['korean'];
                $info['Japanese'] = $data['japanese'];
                $info['Thai'] = $data['thai'];
                $info['Spanish'] = $data['spanish'];
                $info['Portuguese'] = $data['portuguese'];
                $info['Russian'] = $data['russian'];
                $info['French'] = $data['french'];
                $info['German'] = $data['german'];
                $info['Arabic'] = $data['arabic'];
                $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
                if ($returns['code']==100){
                    $log = array(
                        "platform"=>-1,//平台信息(int)
                        "operationType"=>8,//操作类别(int)
                        "operationContent"=>'public_info_add_text',//操作内容(string)
                        "operationReason"=>L('public_info_add_text')//操作原因(string)
                    );
                    $this->configOperation($log);
                    $this->ajaxReturn(10);
                }
            }
            $text->where(array('tid'=>$tid))->delete();
            $this->ajaxReturn(11);
        }
    }


    /**
     * 文本编辑页面
     * */
    public function textEdit(){
        $this->display('text_edit');
    }

    /**
     * 文本编辑请求
     * */
    public function textEditPost(){
        if (IS_POST){
            $data = I('post.');
            $text = M('language_text',null);
            $userInfo = user_info();
            $data['operator'] = $userInfo['username'];
            $textlist = $text->where(array('tid'=>$data['tid']))->select();

            if (count($textlist)>0&&!(in_array('', $data))){

                $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_ADD_LANGUAGE_TEXT");
                $info['tid'] = $data['tid'];
                $info['Chinese'] = $data['chinese'];
                $info['Vietnamese'] = $data['vietnamese'];
                $info['ChineseTraditional'] = $data['chinese_traditional'];
                $info['English'] = $data['english'];
                $info['Korean'] = $data['korean'];
                $info['Japanese'] = $data['japanese'];
                $info['Thai'] = $data['thai'];
                $info['Spanish'] = $data['spanish'];
                $info['Portuguese'] = $data['portuguese'];
                $info['Russian'] = $data['russian'];
                $info['French'] = $data['french'];
                $info['German'] = $data['german'];
                $info['Arabic'] = $data['arabic'];
                $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
                if ($returns['code']==100){
                    $log = array(
                        "platform"=>-1,//平台信息(int)
                        "operationType"=>8,//操作类别(int)
                        "operationContent"=>'public_info_add_text',//操作内容(string)
                        "operationReason"=>L('public_info_add_text')//操作原因(string)
                    );

                    $text->where(array('tid'=>$data['tid']))->save($data);
                    $this->configOperation($log);
                    $this->ajaxReturn(10);
                }
            }
            $this->ajaxReturn(11);
        }
    }

    /**
     * 文本删除请求
     * */
    public function textDeletePost(){
        if (IS_POST){
            $tid = I('post.tid','','intval');
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_LANGUAGE_TEXT");
            $info['tid'] = $tid;
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                M('language_text',null)->where(array('tid'=>$tid))->delete();

                $log = array(
                    "platform"=>-1,//平台信息(int)
                    "operationType"=>8,//操作类别(int)
                    "operationContent"=>'public_info_delete_text',//操作内容(string)
                    "operationReason"=>L('public_info_delete_text')//操作原因(string)
                );
                $this->configOperation($log);
                $this->ajaxReturn(20);
            }
            $this->ajaxReturn(21);
        }
    }

    /**
     * 文本重载请求
     * */
    public function textReloadPost(){
        if (IS_POST){
            $info['type'] = C("MESSAGE_PROTOCOL_NUMBER.IDIP_RELOAD_LANGUAGE_TEXT");
            $returns = json_decode(urldecode($this->GetGmToolApi($info)),true);
            if ($returns['code']==100){
                $this->ajaxReturn(30);
            }
            $this->ajaxReturn(31);
        }
    }

}