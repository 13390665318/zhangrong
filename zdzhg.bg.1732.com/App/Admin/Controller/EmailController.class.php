<?php

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * EmailController short summary.
 *
 * EmailController description.
 *
 * @version 1.0
 * @author admin
 */
class EmailController extends CommonController
{
    /**
     * 发送Email页面
     */
    public function sendEmails(){
            $menuid     = I('get.menuid');
            $menu_db    = D('Menu');
            $currentpos = $menu_db->currentPos($menuid);//栏目位置
            $this->assign('title', $currentpos);
            $this->assign('platlist',C("PLAT_LIST"));
            $this->assign('itemtype',$this->getItemType());
            $this->assign('textemailtitlelist',textTypeList("1"));
            $this->assign('textemailcontentlist',textTypeList("2"));
            $this->display('sendemail');
    }

    /**
     * 发送Email
     */
    public function sendEmail(){
        if(IS_POST){
            $isedit = $_POST["isedit"];
            $id = $_POST["id"];
            $sendtype = $_POST["sendtype"];
            $senduser = urldecode($_POST["senduser"]);
            $platid = $_POST["platid"];
            $sendtime = $_POST["sendtime"];
            $levelbegin = $_POST["levelbegin"];
            $levelend = $_POST["levelend"];
            $deadline = $_POST["deadline"];
            $danbegin = $_POST["danbegin"];
            $danend = $_POST["danend"];
            $expiretime = $_POST["expiretime"];
            $title = $_POST["title"];
            $operationreason = $_POST["operationreason"];
            $sendcontent = $_POST["sendcontent"];
            $awardcount = $_POST["awardcount"];
            $enclosure = $_POST["enclosure"];
            $enclosurecontent = $_POST["enclosurecontent"];
            $data = array();
            if($sendtype == "1"){
                $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_SEND_MAIL_BY_CONDITION");
                $data = array("type" => $mpnumber, 
                    "apptype" => $platid,
                    "minlv" => $levelbegin, 
                    "maxlv" => $levelend,
                    "rankmin" => $danbegin,
                    "rankmax" => $danend,
                    "sendtime" => $sendtime,
                    "deltime" => $deadline,
                    "expiretime" => $expiretime,
                    "title" => $title, 
                    "content" => $sendcontent,
                    "awardcount" => $awardcount);
                $senduser = $levelbegin." <= ".L("public_use_level")." >=".$levelend."<br />";
                $senduser .= $danbegin." <= ".L("public_use_rank")." >=".$danend;
            }else if($sendtype == "2"){
                $platid = "3";//发送范围编号发送
                $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_SEND_MAIL_BY_ROLES_NUMBER");
                $data = array("type" => $mpnumber, 
                    //"apptype" => $platid,
                    "roles" => $senduser, 
                    "sendtime" => $sendtime,
                    "deltime" => $deadline,
                    "expiretime" => $expiretime,
                    "title" => $title, 
                    "content" => $sendcontent,
                    "awardcount" => $awardcount);
               if(strlen($senduser) > 4000){
                   $senduser = substr($senduser,0,4000);
               }
            }
            $enclosureJson = json_decode($enclosure);
            //foreach($enclosureJson as $key=>$value){
            //    foreach ($value as $e_key=>$e_value)
            //    {
            //        if($e_key == "type" || $e_key == "id" || $e_key == "count"){
            //            $data[$e_key] = $e_value;
            //        }
            //    }
            //}
            $data["operationreason"] = $operationreason;
            $data["enclosurelist"] = $enclosureJson;
            $userInfo = user_info();
            $log = array();
            //写入发送日志
            if($isedit == "1"){
                $log = array(
                           "platform"=>$platid,//平台信息(string)
                           "roleCode"=>"",//角色编号(string)
                           "operationType"=>4,//操作类别(int)
                           "operationContent"=>"&*email_send_mail&".','."&*public_title&".':'.$title,//操作内容(string)
                           "operationReason"=>$operationreason//操作原因(string)
                       );
                //写入操作日志
                $this->configOperation($log);
                $this->addEmailLog($sendtime,$platid,$senduser,$title,$enclosurecontent,$userInfo["username"],"0",json_encode($data),$operationreason);
            }else{
                $log = array(
                           "platform"=>$platid,//平台信息(string)
                           "roleCode"=>"",//角色编号(string)
                           "operationType"=>4,//操作类别(int)
                           "operationContent"=>"&*public_use_edit"."&*public_email&".','."&*public_title&".':'.$title,//操作内容(string)
                           "operationReason"=>$operationreason//操作原因(string)
                       );
                //写入操作日志
                $this->configOperation($log);
                $this->editEmailLog($sendtime,$platid,$senduser,$title,$enclosurecontent,$userInfo["username"],json_encode($data),$id,$operationreason);
            }

            
            //$retjson = $this->GetGmToolApi($data);
            //echo $retjson;
        }
    }
    /**
     * 记录Email发送日志
     */
    public function addEmailLog($sendtime,$apptype,$targetuser,$title,$enclosure,$operator,$state,$apijson,$operationreason){
        $zdzhg_email_log = M('zdzhg_email_log',NULL);
        $res = $zdzhg_email_log->add(array(
            'sendtime'=>$sendtime,
            'apptype'=>$apptype,
            'targetuser'=>$targetuser,
            'title'=>$title,
            'enclosure'=>$enclosure,
            'operator'=>$operator,
            'state'=>$state,
            'apijson'=>$apijson,
            'operationreason'=>$operationreason,
            ));
        $res ? $this->success(L("public_successful_operation")) : $this->error(L("public_return_add_error"));
    }
    /**
     * 编辑Email发送日志
     */
    public function editEmailLog($sendtime,$apptype,$targetuser,$title,$enclosure,$operator,$apijson,$id,$operationreason){
        $zdzhg_email_log = M('zdzhg_email_log',NULL);
        $res = $zdzhg_email_log->where(array('id'=>$id, 'state'=>0))->save(array(
            'sendtime'=>$sendtime,
            'apptype'=>$apptype,
            'targetuser'=>$targetuser,
            'title'=>$title,
            'enclosure'=>$enclosure,
            'operator'=>$operator,
            'apijson'=>$apijson,
            'operationreason'=>$operationreason,
            ));
        $res ? $this->success(L("public_successful_operation")) : $this->error(L("public_operation_failed"));
    }
    /**
     * 删除Email发送日志
     */
    public function delEmailLog(){
        $id = $_POST['id'];
        $apptype = $_POST["apptype"];
        if(!empty($id)){
            $zdzhg_email_log = M('zdzhg_email_log',NULL);
            $res = $zdzhg_email_log->where(array('id'=>$id))->delete();
            if($res){
                //记录操作日志
                $log = array(
                           "platform"=>$apptype,//平台信息(string)
                           "roleCode"=>"",//角色编号(string)
                           "operationType"=>4,//操作类别(int)
                           "operationContent"=>"&*public_use_delete"."&*public_email&".",ID:".$id,//操作内容(string)
                           "operationReason"=>""//操作原因(string)
                       );
                $this->configOperation($log);
            }
            $res ? $this->success(L("public_successful_operation")) : $this->error(L("public_operation_failed"));
        }else{
            $this->error(L("public_parameter_error"));
        }
    }
    /**
     * 获取Email发送详情
     */
    public function getEmailInfo(){
        $id = $_POST['id'];
        if(!empty($id)){
            $zdzhg_email_log = M('zdzhg_email_log',NULL);
            $querysql = "select apijson from zdzhg_email_log where id=".$id;
            $list = $zdzhg_email_log->query($querysql);
            $data = $list[0]["apijson"];
            $this->ajaxReturn($data);
        }else{
            $this->error(L("public_parameter_error"));
        }
    }
    /**
     * 获取Email发送日志
     */
    public function getEmailLog($page = 1, $rows = 20, $search = array(), $sort = '', $order = 'desc'){
        $begintime = $search['begintime'];
        $endtime = $search['endtime'];
        $usernumber = $search['usernumber'];
        $wherestr = " 1=1 ";
        if (!empty($begintime)) {
            $wherestr .= " and sendtime>='".$begintime."'";
        }
        if(!empty($endtime)){
            $wherestr .= " and sendtime<='".$endtime."'";
        }
        if(!empty($usernumber)){
            $wherestr .= " and targetuser like '%".$usernumber."%'";
        }
        $zdzhg_email_log = M('zdzhg_email_log',NULL);
        $limit = ($page - 1) * $rows . "," . $rows;
        $langSet = strtolower(cookie('think_language'));
        $language = "chinese";
        switch ($langSet){
            case "zh-cn":$language ='chinese';break;
            case "ko-kr":$language ='korean';break;
            case "en-us":$language ='english';break;
        }
        
        $querysql = "select id,sendtime,apptype,targetuser,b.$language title,enclosure,a.operator,state,CONCAT(state,'|',id,'|',apptype) as opstate,operationreason from zdzhg_email_log a
left join language_text b on a.title=b.tid and  type=1 where $wherestr";
        $count_sql = "SELECT count(*) as con FROM zdzhg_email_log where $wherestr";
        $total = $zdzhg_email_log->query($count_sql);
        $total = $total[0]['con'];
        $querysql = $querysql . " limit " . $limit;
        $list = $zdzhg_email_log->query($querysql);
        $list = $total ? $list : array();
        $data = array('total' => $total, 'rows' => $list);
        $this->ajaxReturn($data);
    }
    
    /**
     * 调用接口定时发送
     */
    function sendEmailApi(){
        $zdzhg_email_log = M('zdzhg_email_log',NULL);
        $order = "sendtime asc";
        $list = $zdzhg_email_log->where('state=0 and sendtime<NOW()')->field("id,apijson")->order($order)->limit(1)->select();
        foreach ($list as $info)
        {
            $data = array();
        	$id = $info["id"];
            $apijson = json_decode($info["apijson"]);            
            foreach($apijson as $key=>$value){
                if($key == "operationreason"){
                    continue;
                }
                else if($key == "sendtime" || $key == "deltime"){
                    $data[$key] = strtotime($value);
                }
                else if($key == "enclosurelist"){
                    $index = 0;
                    foreach ($value as $e_key=>$e_value)
                    {
                        foreach ($e_value as $l_key=>$l_value)
                        { 
                            if($l_key == "type" || $l_key == "id" || $l_key == "count"){
                                $data[$l_key.$index] = $l_value;
                            }
                        }
                        $index++;
                    }
                }else{
                    $data[$key] = $value;
                }
            }
            $retjson = json_decode($this->GetGmToolApi($data));
            $state = -1;
            if($retjson->code == "100"){
                $state = 1;
            }
            //修改邮件发送状态
            $res = $zdzhg_email_log->where(array('id'=>$id))->save(array(
                'state'=>$state,
            ));
            echo L("public_send_successfully")."<br />";
        } echo L("ok100");
    }
    /**
     * 获取Email发送日志
     */
    function setUpLoad(){
        if(IS_POST){
            
		}else{
			$this->display('upload');
		}
    }
}
