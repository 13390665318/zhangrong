<?php

namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * GM工具模块
 * @author gongyun
 */
class GmToolController extends CommonController
{
    /**
     * 查询用户信息
     */
	public function getUserInfo(){
		if(IS_POST){
            $flag = $_POST["flag"];
            $acttype = $_POST["acctype"];
            $account = $_POST["account"];
            $mpnumber = "";
            switch($flag){
                case "1":$mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_ROLE_BASE_INFO");break;//基础信息
                case "2":$mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_ROLE_GAME_INFO");break;//游戏数据
                //case "3":$mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_ROLE_GAME_INFO");break;//游戏数据
                //case "4":$mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_ROLE_MAIL_INFO");break;//游戏数据
            }
            $data = array("type" => $mpnumber, "qtype" => $acttype,"qvalue"=>$account);
            //130010 2 12000000
            $retjson = $this->GetGmToolApi($data);
            $retjson = json_decode($retjson,true);
            if($retjson['last_pass_stage']!=""){
                if ($retjson['legion']['id']=='-1'){
                    $retjson['legion']['id'] = L('prop_info_type_name');
                    $retjson['legion']['name'] = L('prop_info_type_name');
                }
                $retjson['complete'] =  implode(',', $retjson['task']['complete']);
                $retjson['active'] =  implode(',', $retjson['task']['active']);
                $type = array(
                    '0'=>L('PLAYER_BOX_FREE'),
                    '1'=>L('PLAYER_BOX_WIN'),
                    '2'=>L('PLAYER_BOX_SPORT'),
                    '3'=>L('PLAYER_BOX_SEASON'),
                    '4'=>L('PLAYER_BOX_SHOP'),
                    '5'=>L('PLAYER_BOX_LEGION'),
                    '6'=>L('PLAYER_BOX_DUEL'),
                    '7'=>L('PLAYER_BOX_SIGNIN'),
                );
                foreach ($retjson['box'] as $key=>$val){
                    $retjson['box'][$key]['type'] = $type[$retjson['box'][$key]['type']];
                    if ( $retjson['box'][$key]['unlocktime']=="-1"){
                        $retjson['box'][$key]['unlocktime'] = L('public_chest_off');
                    }
                }
            }else{
                $retjson['last_pass_stage'] = "***";
            }
            $retjson = json_encode($retjson);

            echo $retjson;
		}else{
            $menuid     = I('get.menuid');
            $menu_db    = D('Menu');
            $currentpos = $menu_db->currentPos($menuid);//栏目位置
            $this->assign('title', $currentpos);
            $acctype = $_GET['acctype'];
            $account = $_GET['account'];
            $this->assign('acctype', $acctype);
            $this->assign('account', $account);
            $this->assign('thinklanguage', strtolower(cookie('think_language')));
			$this->display('userinfo');
		}
	}
    /**
     * 修改角色游戏信息
     */
    public function upgameuserinfo(){
        if(IS_POST){
            $roleid = $_POST["roleid"];
            $level = $_POST["level"];
            $level_old = $_POST["level_old"];
            $gold = $_POST["gold"];
            $gold_old = $_POST["gold_old"];
            $diamond = $_POST["diamond"];
            $diamond_old = $_POST["diamond_old"];
            $freediamond = $_POST["freediamond"];
            $freediamond_old = $_POST["freediamond_old"];
            $reason = $_POST["reason"];
            $rolecode = $_POST['rolecode'];
            if($roleid!='' && $reason!=""){
                $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_MODIFY_ROLE_GAME_INFO");
                $data = array("type" => $mpnumber, "roleid" => $roleid);
                $change = "";//变动记录
                if($level != "" && $level_old!="" && $level != $level_old){
                    $change .= "&*public_use_level&".$level_old." "."&*public_instead&".$level.",";
                    $data["level"] = $level;
                }else{
                    $data["level"] = "-1";
                }
                if($gold != "" && $gold_old!="" && $gold != $gold_old){
                    $change .= "&*public_gold&".$gold_old." "."&*public_instead&".$gold.",";
                    $data["gold"] = $gold;
                }else{
                    $data["gold"] = "-1";
                }
                if($diamond!="" && $diamond_old != "" && $diamond != $diamond_old){
                    $change .= "&*public_rechargeable_diamond&".$diamond_old." "."&*public_instead&".$diamond.",";
                    $data["diamond"] = $diamond;
                }else{
                    $data["diamond"] = "-1";
                }
                if($freediamond != "" && $freediamond_old!="" && $freediamond != $freediamond_old){
                    $change .= "&*public_free_diamond&".$freediamond_old." "."&*public_instead&".$freediamond.",";
                    $data["freediamond"] = $freediamond;
                }else{
                    $data["freediamond"] = "-1";
                }
                $change = (strlen($change)>0? substr($change,0,strlen($change)-1):"");
                $retjson = $this->GetGmToolApi($data);
                $retarr = json_decode($retjson);
                if($retarr->code == "100"){
                    //记录变更记录
                    $userInfo = user_info();

                    $change_data = explode('&',$change);
                    $changes = '';
                    foreach ($change_data as $val){
                        if (substr($val , 0 , 1)=='*'&&$val!=''){
                            $changes .= L(substr($val , 1));
                        }else if ($val!=''){
                            $changes .= $val;
                        }
                    }

                    $this->addUserChangeLog($rolecode,$roleid,$changes,$reason,$userInfo["username"]);
                    //记录操作日志
                    $log = array(
                               "platform"=>"",//平台信息(string)
                               "roleCode"=>$rolecode,//角色编号(string)
                               "operationType"=>1,//操作类别(int)
                               "operationContent"=>$change,//操作内容(string)
                               "operationReason"=>$reason//操作原因(string)
                           );
                    $this->configOperation($log);
                }
                echo $retjson;
            }else{
                echo '{"code":-100}'; 
            }
        }
    }
    /**
     * 恢复角色
     */
    public function unlockrole(){
        if(IS_POST){
            $roleid = $_POST["roleid"];
            $timeid = $_POST["timeid"];
            $reason = $_POST["reason"];
            $rolecode = $_POST['rodecode'];
            if($roleid!='' && $timeid!='' && $reason!=''){
                $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_RECOVERY_USER");
                $data = array("type" => $mpnumber, "roleid" => $roleid,"targeted"=>$timeid,"reason"=>$reason);
                $retjson = $this->GetGmToolApi($data);
                $retarr = json_decode($retjson);
                if($retarr->code == "100"){
                    //记录变更记录
                    $userInfo = user_info();
                    $this->addUserChangeLog($rolecode,$roleid,L("public_restore_roles"),$reason,$userInfo["username"]);
                    //记录操作日志
                    $log = array(
                               "platform"=>"",//平台信息(string)
                               "roleCode"=>$rolecode,//角色编号(string)
                               "operationType"=>1,//操作类别(int)
                               "operationContent"=>"public_restore_roles",//操作内容(string)
                               "operationReason"=>$reason//操作原因(string)
                           );
                    $this->configOperation($log);
                }
                echo $retjson;
            }
        }else{
            $delhistory = json_decode($_GET["delhistory"]);
            $roleid = $_GET["roleid"];
            $delhistoryarr = array();
            for ($i = 0; $i < count($delhistory); $i++){
                $delhistoryarr[$delhistory[$i]->id] = $delhistory[$i]->time."&nbsp;&nbsp;&nbsp;".$delhistory[$i]->code;
            }
            $rolecode = $_GET["rolecode"];
            $this->assign('rolecode',$rolecode);
            $this->assign('delhistory', $delhistoryarr);
            $this->assign('roleid',$roleid);
            $this->display('recovery_up');
        }
    }
    /**
     * 踢玩家下线
     */
    public function kickPlayerffline(){
        $roleid = $_POST["roleid"];
        $rolecode = $_POST["rolecode"];
        if(!empty($roleid)){
            $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_KICK_PLAYER_OFFLINE");
            $data = array("type" => $mpnumber, "roleid" => $roleid);
            $retjson = $this->GetGmToolApi($data);
            
            $retarr = json_decode($retjson);
            if($retarr->code == "100"){
                //记录操作日志
                $log = array(
                           "platform"=>"",//平台信息(string)
                           "roleCode"=>$rolecode,//角色编号(string)
                           "operationType"=>1,//操作类别(int)
                           "operationContent"=>"&*user_player_offline&".",roleid:".$roleid,//操作内容(string)
                           "operationReason"=>""//操作原因(string)
                       );
                $this->configOperation($log);
            }
            echo $retjson;
        }
    }
    /**
     * 删除玩家排行榜
     */
    public function deleteranking(){
        $roleid = $_POST["roleid"];
        $rolecode = $_POST["rolecode"];
        if(!empty($roleid)){
            $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_RELOAD_CLEAR_PLAYER_RANK");
            $data = array("type" => $mpnumber, "roleid" => $roleid);
            $retjson = $this->GetGmToolApi($data);
            
            $retarr = json_decode($retjson);
            if($retarr->code == "100"){
                //记录变更记录
                $userInfo = user_info();
                $this->addUserChangeLog($rolecode,$roleid,L("user_scavenging_player_rankings"),L("user_scavenging_player_rankings"),$userInfo["username"]);
                //记录操作日志
                $log = array(
                           "platform"=>"",//平台信息(string)
                           "roleCode"=>$rolecode,//角色编号(string)
                           "operationType"=>1,//操作类别(int)
                           "operationContent"=>"&*user_scavenging_player_rankings&".",roleid:".$roleid,//操作内容(string)
                           "operationReason"=>""//操作原因(string)
                       );
                $this->configOperation($log);
            }
            echo $retjson;
        }
    }
    public function recovery(){
        $roleid = $_POST["roleid"];
        $oldaccount = $_POST["oldaccount"];
        $qiangzhi = $_POST["qiangzhi"];
        if(!empty($roleid)&&!empty($oldaccount)){
            $mpnumber = C("MESSAGE_PROTOCOL_NUMBER");
            $mpnumber =130021;
            $data = array("type" => $mpnumber,"newRoleId" => $roleid,'oldRoleId'=>$oldaccount,'IsForcibly'=>$qiangzhi);

            $retjson = $this->GetGmToolApi($data);

            $retarr = json_decode($retjson);
            if($retarr->code == "100"){
                //记录变更记录
                $userInfo = user_info();
                $this->addUserChangeLog($rolecode,$roleid,L("user_scavenging_player_rankings"),L("user_scavenging_player_rankings"),$userInfo["username"]);
                //记录操作日志
                $log = array(
                    "platform"=>"",//平台信息(string)
                    "roleCode"=>$rolecode,//角色编号(string)
                    "operationType"=>1,//操作类别(int)
                    "operationContent"=>"&*user_scavenging_player_rankings&".",roleid:".$roleid,//操作内容(string)
                    "operationReason"=>""//操作原因(string)
                );
                $this->configOperation($log);
            }
            echo $retjson;
        }
    }
    /**
     * 记录变更记录
     */
    public function addUserChangeLog($rolecode,$roleid,$content,$reason,$operator){
        $zdzhg_email_log = M('zdzhg_user_change_log',NULL);
        $res = $zdzhg_email_log->add(array(
            'rolecode'=>$rolecode,
            'roleid'=>$roleid,
            'content'=>$content,
            'reason'=>$reason,
            'operator'=>$operator,
            ));
        //$res ? $this->success(L("public_successful_operation")) : $this->error(L("public_return_add_error"));
    }
    /**
     * 封号/解封
     */
    public function setRequestUnlock(){
        if(IS_POST){
            $roleid = $_POST["roleid"];
            $timeid = $_POST["timeid"];
            $reason = $_POST["reason"];
            $isunlock = $_POST["isunlock"];//1封号2解封
            $rolecode = $_POST['rolecode'];
            if($isunlock == "1"){
                if(!empty($roleid) && !empty($timeid) && !empty($reason)){
                    //封号
                    $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_LOCK_ROLE");
                    $data = array("type" => $mpnumber, "roleid" => $roleid,"times"=>$timeid,"lockreason"=>$reason);
                    $retjson = $this->GetGmToolApi($data);
                    $retarr = json_decode($retjson);
                    if($retarr->code == "100"){
                        //记录变更记录
                        $userInfo = user_info();
                        $content_log = "";
                        if($timeid == "-1"){
                            $content_log = L("user_users_are_permanently_registered");
                        }else{
                            $content_log = L("user_user_title").($timeid/60/60/24).L("public_day");
                        }
                        $this->addUserChangeLog($rolecode,$roleid,$content_log,$reason,$userInfo["username"]);
                        //记录操作日志
                        $log = array(
                                   "platform"=>"",//平台信息(string)
                                   "roleCode"=>$rolecode,//角色编号(string)
                                   "operationType"=>3,//操作类别(int)
                                   "operationContent"=>"public_disable",//操作内容(string)
                                   "operationReason"=>$reason//操作原因(string)
                               );
                        $this->configOperation($log);
                    }
                    echo $retjson;
                }
            }else if($isunlock == "2"){
                if(!empty($roleid) && !empty($reason)){
                    //解封
                    $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_UNLOCK_ROLE");
                    $data = array("type" => $mpnumber, "roleid" => $roleid);
                    $retjson = $this->GetGmToolApi($data);
                    $retarr = json_decode($retjson);
                    if($retarr->code == "100"){
                        //记录变更记录
                        $userInfo = user_info();
                        $this->addUserChangeLog($rolecode,$roleid,L("user_the_user_is"),$reason,$userInfo["username"]);
                        //记录操作日志
                        $log = array(
                                   "platform"=>"",//平台信息(string)
                                   "roleCode"=>$rolecode,//角色编号(string)
                                   "operationType"=>3,//操作类别(int)
                                   "operationContent"=>"public_enable",//操作内容(string)
                                   "operationReason"=>$reason//操作原因(string)
                               );
                        $this->configOperation($log);
                    }
                    echo $retjson;
                }
            }else{
                echo '{"code":-100}';
            }
        }else{
            $isunlock = $_GET["isunlock"];
            $roleid = $_GET["roleid"];
            $rolecode = $_GET["rolecode"];
            $this->assign('rolecode',$rolecode);
            $this->assign('roleid',$roleid);
            $this->assign('isunlock',$isunlock);
            $this->display('unlockrolerol_up');
        }
    }
    /**
     * 获取变更记录
     */
    public function getChangeLog($page = 1, $rows = 20, $search = array(), $sort = '', $order = 'desc'){
        $acctype = $_POST['acctype'];
        $account = $_POST['account'];
        $wherestr = "";
        if (empty($acctype) || empty($account)) {
            $this->ajaxReturn(array('total' => 0, 'rows' => array()));
        }
        if($acctype == "2"){
            $wherestr = " roleid='".$account."'";
        }else if($acctype == "1"){
            $wherestr = " rolecode='".$account."'";
        }
        $zdzhg_user_change_log = M('zdzhg_user_change_log',NULL);
        $limit = ($page - 1) * $rows . "," . $rows;
        $querysql = "select id,inserttime,content,reason,operator,roleid,rolecode from zdzhg_user_change_log where $wherestr order by id desc";
        $count_sql = "SELECT count(*) as con FROM zdzhg_user_change_log where $wherestr";
        $total = $zdzhg_user_change_log->query($count_sql);
        $total = $total[0]['con'];
        $querysql = $querysql . " limit " . $limit;
        $list = $zdzhg_user_change_log->query($querysql);
        $list = $total ? $list : array();
        $data = array('total' => $total, 'rows' => $list);
        $this->ajaxReturn($data);
    }
     /**
     * 获取玩家邮件列表
     */
    public function getUserEmailLog($page = 1, $rows = 10, $search = array(), $sort = '', $order = 'desc'){
        $acctype = $_POST['acctype'];
        $account = $_POST['account'];
        if (empty($acctype) || empty($account)) {
            $this->ajaxReturn(array('total' => 0, 'rows' => array()));
        }
        $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_QUERY_ROLE_MAIL_INFO");
        $data = array("type" => $mpnumber, "qtype" => $acctype,"qvalue"=>$account,"from"=>(($page-1)*$rows),"count"=>$rows);
        $retjson = $this->GetGmToolApi($data);
        //$retjson = '{"code":100,"roleid":13000581,"mails":[{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-12,"content":"有黄金1000，宝石30","type":1,"status":1,"taketime":"2017-10-14 15:18:24","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":2,"id":2,"count":30},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]},{"id":-11,"content":"有黄金1000，宝石1000","type":1,"status":0,"taketime":"2017-10-14 15:17:28","operatetime":"1970-01-01 08:00:00","awards":[{"type":1,"id":1,"count":1000},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0},{"type":-1,"id":0,"count":0}]}]}';
        $emailarr = array();
        $retarr = json_decode($retjson);
        $emailsum = "0";
        $text = M('language_text',null);
        $langSet = strtolower(cookie('think_language'));
        $language = "chinese";
        switch ($langSet){
            case "zh-cn":$language ='chinese';break;
            case "ko-kr":$language ='korean';break;
            case "en-us":$language ='english';break;
        }

        if($retarr->code == "100"){
            $mails = $retarr->mails;
            $emailsum = $retarr->sum;
            for ($i = 0; $i < count($mails); $i++)
            {
                $emailinfoarr = array();
            	$emailinfoarr["id"] = $mails[$i]->id;
                $emailinfoarr["type"] = $mails[$i]->type;
                $sql = "select $language as con from language_text where tid='".$mails[$i]->content."'";
                $language_text = $text->query($sql);
                //$emailcontent = $text->where(array('tid'=>$mails[$i]->content))->find()
                $emailinfoarr["content"] = $language_text[0]['con'];
                $emailinfoarr["status"] = $mails[$i]->status."|".$mails[$i]->type;
                $emailinfoarr["taketime"] = $mails[$i]->taketime;
                $emailinfoarr["operatetime"] = $mails[$i]->status."|".$mails[$i]->operatetime;
                $emailinfoarr["recovery"] = $mails[$i]->id."|".$mails[$i]->status."|".$retarr->roleid."|".$mails[$i]->type;
                $awards = $mails[$i]->awards;
                $awardsval = "";
                for ($j = 0; $j < count($awards); $j++)
                {
                    if($awards[$j]->type != "-1"){
                        //echo $this->getItemTypeName($awards[$j]->type)."<br />";
                        //$awardsval = $this->getItemTypeName($awards[$j]->type)."_".$this->getItemName($awards[$j]->id)."(".$awards[$j]->id.")&nbsp;&nbsp;&nbsp;".$awards[$j]->count;
                        $awardsval = L($this->getItemName($awards[$j]->id,$awards[$j]->type))."_".$awards[$j]->count;
                    }
                }
                $emailinfoarr["awards"] = $awardsval;
                array_push($emailarr,$emailinfoarr);
            }
        }
        //print_r($emailarr);
        $this->ajaxReturn(array('total' => $emailsum, 'rows' => $emailarr));
    }
    /**
     * 通过物品ID获取物品名称
     */
    public function getItemName($itemid,$type){
        $zdzhg_email_log = M('config_item',NULL);
        $querysql = "select itemname from config_item where itemid=".$itemid." and itemtype=".$type;
        $list = $zdzhg_email_log->query($querysql);
        if(count($list) > 0){
            return $list[0]["itemname"];
        }else{
            return "";
        }
    }
    /**
     * 通过物品类型ID获取物品名称
     */
    public function getItemTypeName($itemtypeid){
        $zdzhg_email_log = M('config_item',NULL);
        $querysql = "select typename from config_item_type where typeid=".$itemtypeid;
        $list = $zdzhg_email_log->query($querysql);
        if(count($list) > 0){
            return $list[0]["typename"];
        }else{
            return "";
        }
    }
    /**
     * 获取卡牌，宝箱道具列表
     */
    public function getItemCardOrFourList(){
        $zdzhg_email_log = M('config_item',NULL);
        $querysql = "select itemid,itemname from config_item where itemtype in(2,3)";
        $list = $zdzhg_email_log->query($querysql);
        return $list;
    }
    /**
     * 回收邮件
     */
    public function setRecovery(){
        $id = $_POST["id"];
        $roleid = $_POST["roleid"];
        $rolecode = $_POST["rolecode"];
        if(!empty($id)){
            $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_DEL_MAIL_BY_ID");
            $data = array("type" => $mpnumber, "mailid" => $id,"roleid"=>$roleid);
            $retjson = $this->GetGmToolApi($data);
            $retarr = json_decode($retjson);
            if($retarr->code == "100"){
                //记录操作日志
                $log = array(
                           "platform"=>"",//平台信息(string)
                           "roleCode"=>$rolecode,//角色编号(string)
                           "operationType"=>4,//操作类别(int)
                           "operationContent"=>"&*public_recovery_hs&"."&*public_email&".",id:".$id,//操作内容(string)
                           "operationReason"=>""//操作原因(string)
                       );
                $this->configOperation($log);
            }
            echo $retjson;
        }else{
            echo '{"code":-100}';
        }
    }
    ///**
    // * 获取注销/制裁列表
    // */
    //public function getCanceSanctionlist(){
    //    $flag = $_POST["flag"];//1注销,2制裁
    //    if($flag == "1"){
    //        $acctype = $_POST['acctype'];
    //        $account = $_POST['account'];
    //        if (empty($acctype) || empty($account)) {
    //            $this->ajaxReturn(array('total' => 0, 'rows' => array()));
    //        }
    //        $mpnumber = C("MESSAGE_PROTOCOL_NUMBER.IDIP_REQUEST_QUERY_USER_DEL_RECORD");
    //        $data = array("type" => $mpnumber, "qtype" => $acctype,"qvalue"=>$account,"from"=>(($page-1)*30),"count"=>$rows);
    //        $retjson = $this->GetGmToolApi($data);
    //        $emailarr = array();
    //        $retarr = json_decode($retjson);
    //        $emailsum = "0";
    //        if($retarr->code == "100"){
    //            $mails = $retarr->mails;
    //            $emailsum = $retarr->sum;
    //            for ($i = 0; $i < count($mails); $i++)
    //            {
    //                $emailinfoarr = array();
    //                $emailinfoarr["id"] = $mails[$i]->id;
    //                $emailinfoarr["type"] = $mails[$i]->type;
    //                $emailinfoarr["content"] = $mails[$i]->content;
    //                $emailinfoarr["status"] = $mails[$i]->status;
    //                $emailinfoarr["taketime"] = $mails[$i]->taketime;
    //                $emailinfoarr["operatetime"] = $mails[$i]->operatetime;
    //                $emailinfoarr["recovery"] = $mails[$i]->id."|".$mails[$i]->status."|".$retarr->roleid;;
    //                $awards = $mails[$i]->awards;
    //                $awardsval = "";
    //                for ($j = 0; $j < count($awards); $j++)
    //                {
    //                    if($awards[$j]->type != "-1"){
    //                        //echo $this->getItemTypeName($awards[$j]->type)."<br />";
    //                        $awardsval = $this->getItemTypeName($awards[$j]->type)."_".$this->getItemName($awards[$j]->id)."(".$awards[$j]->id.")&nbsp;&nbsp;&nbsp;".$awards[$j]->count;
    //                    }
    //                }
    //                $emailinfoarr["awards"] = $awardsval;
    //                array_push($emailarr,$emailinfoarr);
    //            }
    //        }
    //    }
    //    $this->ajaxReturn(array('total' => count($emailarr), 'rows' => $emailarr));
    //}
    /*--------------------------------------  用户日志操作 begin  --------------------------------------*/
    /**
     * 查询用户LOG
     */
    public function getUserInfolog($page = 1, $rows = 20, $search = array(), $sort = '', $order = 'desc'){
        $connection=db();
        IF(IS_POST){

            $account = $_POST["account"];
            $tabname = $_POST["tabname"];
            $tabnamelikeage = $_POST["tabnamelikeage"];
            $begtime = $_POST["begtime"];
            $endtime = $_POST["endtime"];
            if(!empty($account) && !empty($tabname)){
                $table = $tabname;
                if(!empty($tabnamelikeage)){
                    $table =$tabnamelikeage;
                }
                if(empty($begtime)){
                    $begtime = "1970-01-01";
                }
                if(empty($endtime)){
                    $endtime = date("Y-m-d",strtotime("1 day"));
                }
                $day=count_days($begtime,$endtime);//天数
                $list=array();
                for($i=0;$i<=$day;$i++){//循环取值
                    $Strtime = date('Y-m-d 00:00:00', strtotime("+$i day", strtotime($begtime)));
                    $sqltime=date('Ymd', strtotime("+$i day", strtotime($begtime)));
                    $sqltable=$table.'_'.$sqltime;
                    $arr=M($sqltable,null,$connection)->where("roleid='$account'")->select();
                    if(!$arr){
                        $arr=array();
                    }
                    $list=array_merge($list,$arr);
                }
                $total=count($list);
                $list=array_slice($list,($page-1)*$rows,$rows);//分页
                $list = $total ? $list : array();
                $data = array('total' => $total, 'rows' => $this->getGameLogItemName($table,$list));//返回
                $this->ajaxReturn($data);





                //M($table,null,$connection)

                //$m_table = $this->ZD_DBModel($table,null);
                /*$where = $this->getUserLogWhere($table,$account,$begtime,$endtime);//" RoleId='".$account."' and UpdateTime>='".$begtime."' and UpdateTime<='".$endtime."'";
                $limit = ($page - 1) * $rows . "," . $rows;
                $querysql = "select * from ".$table." where ".$where;
                $count_sql = "SELECT count(*) as con FROM ".$table." where ".$where;
                $total = $m_table->query($count_sql);
                $total = $total[0]['con'];
                $querysql = $querysql . " limit " . $limit;
                $list = $m_table->query($querysql);*/

            }else{
                $this->ajaxReturn(array('total' => 0, 'rows' => array()));
            }
        }else{
            $menuid     = I('get.menuid');
            $menu_db    = D('Menu');
            $currentpos = $menu_db->currentPos($menuid);//栏目位置
            $this->assign('title', $currentpos);
            $lang=array(
                l("config_novice_guidance")=>L("config_novice_guidance"),
                l("config_combat_record")=>L("config_combat_record"),
                l("config_social_record")=>L("config_social_record"),
                L("config_game_props")=>L("config_game_props"),
                L("config_role_cue_change")=>L("config_role_cue_change"),
                L("config_currency_record")=>L("config_currency_record"),
                L("config_merchandise_sales_record")=>L("config_merchandise_sales_record"),
                L("config_login_and_retention")=>L("config_login_and_retention"),
                //L("config_novice_boot")=>L("config_novice_boot"),
                //L("config_tournament_record")=>L("config_tournament_record"),
                //L("config_daily_task_record")=>L("config_daily_task_record"),
                //L("config_player_online_record")=>L("config_player_online_record"),
                L("config_share")=>L("config_share"),
            );

            $this->assign('logtable',$lang);
            $this->assign('begintime',date("Y-m-d ",strtotime("-7 day")));
            $this->assign('endtime',date("Y-m-d"));
            $acctype = $_GET['acctype'];
            $account = $_GET['account'];
            $this->assign('acctype', $acctype);
            $this->assign('account', $account);
			$this->display('userinfolog');
        }
    }
    /**
     * 根据道具ID替换道具名称
     * @param mixed $itemid 
     */
    public function getGameLogItemName($table,$list){
        if($table == "card_change"){ //CardId
            $itemlist = $this->getItemCardOrFourList();
            foreach ($list as $k=>$v)
            {
            	$list[$k]["cardid"] = $this->getItemidReplaceItemName($list[$k]["cardid"],$itemlist);
                $list[$k]["chestid"] = $this->getItemidReplaceItemName($list[$k]["chestid"],$itemlist);
            }            
        }else if($table == "Skin"){//BoxID,SkinId
            $itemlist = $this->getItemCardOrFourList();
            foreach ($list as $k=>$v)
            {
            	$list[$k]["boxid"] = $this->getItemidReplaceItemName($list[$k]["boxid"],$itemlist);
                $list[$k]["skinid"] = $this->getItemidReplaceItemName($list[$k]["skinid"],$itemlist);
            }  
            
        }else if($table == "mail"){//AttachmentID1,AttachmentID2,AttachmentID3,AttachmentID4
            $itemlist = $this->getItemCardOrFourList();
            foreach ($list as $k=>$v)
            {
            	$list[$k]["attachmentid1"] = $this->getItemidReplaceItemName($list[$k]["attachmentid1"],$itemlist);
                $list[$k]["attachmentid2"] = $this->getItemidReplaceItemName($list[$k]["attachmentid2"],$itemlist);
                $list[$k]["attachmentid3"] = $this->getItemidReplaceItemName($list[$k]["attachmentid3"],$itemlist);
                $list[$k]["attachmentid4"] = $this->getItemidReplaceItemName($list[$k]["attachmentid4"],$itemlist);
            }  
        }else if($table == "Chest"){//ChestID,ChestID1,ChestID2,ChestID3,ChestID4,ChestID5,ChestID6,ChestID7,ChestID8
            $itemlist = $this->getItemCardOrFourList();
            foreach ($list as $k=>$v)
            {
            	$list[$k]["chestid"] = $this->getItemidReplaceItemName($list[$k]["chestid"],$itemlist);
                $list[$k]["cardid1"] = $this->getItemidReplaceItemName($list[$k]["cardid1"],$itemlist);
                $list[$k]["cardid2"] = $this->getItemidReplaceItemName($list[$k]["cardid2"],$itemlist);
                $list[$k]["cardid3"] = $this->getItemidReplaceItemName($list[$k]["cardid3"],$itemlist);
                $list[$k]["cardid4"] = $this->getItemidReplaceItemName($list[$k]["cardid4"],$itemlist);
                $list[$k]["cardid5"] = $this->getItemidReplaceItemName($list[$k]["cardid5"],$itemlist);
                $list[$k]["cardid6"] = $this->getItemidReplaceItemName($list[$k]["cardid6"],$itemlist);
                $list[$k]["cardid7"] = $this->getItemidReplaceItemName($list[$k]["cardid7"],$itemlist);
                $list[$k]["cardid8"] = $this->getItemidReplaceItemName($list[$k]["cardid8"],$itemlist);
            }  
        }
        return $list;
    }
    public function getItemidReplaceItemName($itemid,$list){
        if(empty($itemid))
            return "";
        foreach ($list as $info)
        {
            if($info["itemid"] == $itemid){
                return $info["itemname"];
            }
        }
        return "";
    }
    
    /**
     * 查询用户LOG
     */
    public function getUserInfologs($page = 1, $rows = 20, $search = array(), $sort = '', $order = 'desc'){
        IF(IS_POST){

            $account = $_POST["account"];
            $tabname = $_POST["tabname"];
            $tabnamelikeage = $_POST["tabnamelikeage"];
            $begtime = $_POST["begtime"];
            $endtime = $_POST["endtime"];
            if(!empty($account) && !empty($tabname)){
                $table = $tabname;
                if(!empty($tabnamelikeage)){
                    $table =$tabnamelikeage;
                }
                if(empty($begtime)){
                    $begtime = "1970-01-01";
                }
                if(empty($endtime)){
                    $endtime = date("Y-m-d",strtotime("1 day"));
                }
                $m_table = $this->ZD_DBModel($table,null);

                $where = $this->getUserLogWhere($table,$account,$begtime,$endtime);//" RoleId='".$account."' and UpdateTime>='".$begtime."' and UpdateTime<='".$endtime."'";
                $limit = ($page - 1) * $rows . "," . $rows;
                $querysql = "select * from ".$table." where ".$where;
                $count_sql = "SELECT count(*) as con FROM ".$table." where ".$where;
                $total = $m_table->query($count_sql);
                $total = $total[0]['con'];
                $querysql = $querysql . " limit " . $limit;
                $list = $m_table->query($querysql);
                $list = $total ? $list : array();
                $data = array('total' => $total, 'rows' => $list);

                $this->ajaxReturn($data);
            }else{
                $this->ajaxReturn(array('total' => 0, 'rows' => array()));
            }
        }else{
            $menuid     = I('get.menuid');
            $menu_db    = D('Menu');
            $currentpos = $menu_db->currentPos($menuid);//栏目位置

            $this->assign('title', $currentpos);
            $this->assign('logtable',C("USER_LOG_TABLE"));

            $this->assign('begintime',date("Y-m-d ",strtotime("-7 day")));
            $this->assign('endtime',date("Y-m-d"));
            $acctype = $_GET['acctype'];
            $account = $_GET['account'];
            $this->assign('acctype', $acctype);
            $this->assign('account', $account);
            $this->assign('thinklanguage', strtolower(cookie('think_language')));
            $this->display('userinfolog');
        }
    }
    public function getTableNameLikeage(){
        $tabname = $_POST["tabname"];

        if(!empty($tabname)){
            $data=array(
                L("config_share")=>array(
                    L("config_share")=>'sharelink',
                ),
                L("config_player_online_record")=>array(
                    L("config_player_online_record")=>'playeronline',
                ),
                L("config_daily_task_record")=>array(
                    L("config_daily_task_record")=>'dailymission',
                ),
                //L("config_novice_boot")=>array(
                //            L("config_novice_boot")=>'config_novice_boot',
                //            ),
                L("config_novice_guidance")=>array(
                    L("config_novice_guidance")=>'tutorial',
                ),
                L("config_combat_record")=>array(
                    L("config_campaign")=>'stage_pve',
                    L("config_duel")=>'stage_dual',
                    L("config_encounter_mode")=>'stage_encounter',
                    L("config_leisure_mode")=>'stage_leisure',
                    L("config_competitive_mode")=>'stage_sports',
                    L("config_matching_records")=>'matchinfo',
                ),
                L("config_social_record")=>array(
                    L("config_friend_operation")=>'friend',
                    //L("config_legion_records")=>'unioninfo',
                    //L("config_legion_members_change")=>'unionmember',
                ),
                L("config_game_props")=>array(
                    L("config_card_changes")=>'card_change',
                    L("config_appearance")=>'Skin',
                    L("public_email")=>'mail',
                    L("public_goods_type_four")=>'Chest',
                ),
                L("config_role_cue_change")=>array(
                    L("config_level_upgrade")=>'card_levelup',
                    L("config_experience_change")=>'player_exp',
                    L("config_dan_change")=>'rankchange',
                    L("config_changes_in_athletic_scores")=>'Scorechange',
                ),
                L("config_currency_record")=>array(
                    L("config_gold_change")=>'gold',
                    L("config_gem_changes")=>'Gem',
                ),
                L("config_merchandise_sales_record")=>array(
                    L("config_store_purchase_records")=>'Buying',
                    L("config_recharge_record")=>'payment',
                ),
                L("config_login_and_retention")=>array(
                    //L("config_installation_records")=>'Install',
                    L("config_player_registration")=>'player_register',
                    L("config_player_landing")=>'player_login',
                    L("config_player_logout")=>'Player_logout',
                ),
                L("config_tournament_record")=>array(
                    L("config_tournament_registration")=>'competition',
                    L("config_tournament_vs")=>'competition_info',
                    L("config_tournament_player_ranking")=>'competition_ranking',
                ),
            );

            $likeage = $data[$tabname];
            if($likeage == null){
                echo "-100";
            }else{
                echo json_encode($likeage);
            }
        }else{
            echo "-100";
        }
    }
    /**
     * 用户日志列表导出
     */
    public function exportUserInfolog(){
        if (!empty($_GET['vars']) && $_GET['vars'] != null) {
            $values = explode("|", $_GET["vars"]);
            $account = $values[0];
            $tabname = $values[1];
            $tabnamelikeage = $values[2];
            $begtime = $values[3];
            $endtime = $values[4];
            if(!empty($account) && !empty($tabname)){
                $table = $tabname;
                if(!empty($tabnamelikeage)){
                    $table =$tabnamelikeage;
                }
                if(empty($begtime)){
                    $begtime = "1970-01-01";
                }
                if(empty($endtime)){
                    $endtime = date("Y-m-d",strtotime("1 day"));
                }
                $m_table = $this->ZD_DBModel($table,null);
                $where = $this->getUserLogWhere($table,$account,$begtime,$endtime);//" RoleId='".$account."' and UpdateTime>='".$begtime."' and UpdateTime<='".$endtime."'";
                $querysql = "select * from ".$table." where ".$where;
                $list = $m_table->query($querysql);
                $title = array();
                foreach ($list[0] as $key=>$value)
                {
                    array_push($title,$key);
                }
                $filename = $table;
                $this->exportexcel($list, $title, $filename);
            }else{
                echo "-100";
            }
        } else{
            echo "-100";
        }
    }
    /**
     * 根据表名生成查询条件
     */
    public function getUserLogWhere($table,$account,$begtime,$endtime){
        $where = "";
        switch($table){
            case "Install":
                $where = " install_time>='".$begtime."' and install_time<='".$endtime."'";break;
            case "player_login":
                $where = " RoleId='".$account."' and login_time>='".$begtime."' and login_time<='".$endtime."'";break;
            case "player_register":
                $where = " RoleId='".$account."' and RegisterTime>='".$begtime."' and RegisterTime<='".$endtime."'";break;
            case "Player_logout":
                $where = " RoleId='".$account."' and logouttime>='".$begtime."' and logouttime<='".$endtime."'";break;
            case "tutorial":
                $where = " RoleId='".$account."'";break;
            case "competition":
                $where = " RoleId='".$account."'";break;
            case "competition_info":
                $where = " RoleId='".$account."'";break;
            //case "competition_ranking":
            //    $where = " RoleId='".$account."'";break;
            case "playeronline":
                $where = " dtEventTime>='".$begtime."' and dtEventTime<='".$endtime."'";break;
            case "Chest":
                $where = " RoleId='".$account."' and RecordTime>='".$begtime."' and RecordTime<='".$endtime."'";break;
            default:
                $where = " RoleId='".$account."' and UpdateTime>='".$begtime."' and UpdateTime<='".$endtime."'";break;
        }
        return $where;
    }
    /*--------------------------------------  用户日志操作 end  --------------------------------------*/
}
