<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 公共控制器
 * @author wangdong
 *
 * TODO
 * 后缀带_iframe的ACTION是在iframe中加载的，用于统一返回格式
 */
class CommonController extends Controller {

//    protected $platform;//平台信息
//    protected $roleCode;//角色编号
//    protected $operationType;//操作类别
//    protected $operationContent;//操作内容
//    protected $operationReason;//操作原因

	function _initialize(){
		if(IS_AJAX && IS_GET) C('DEFAULT_AJAX_RETURN', 'html');
		self::checkLogin();
		self::checkPriv();
		self::operateLog();
		self::cookie();
	}
	private function checkLogin(){
		if(CONTROLLER_NAME =='Index' && in_array(ACTION_NAME, array('login', 'code'))) return true;
        if(CONTROLLER_NAME =='Email' && in_array(ACTION_NAME, array('sendemailapi'))) return true;
        if(CONTROLLER_NAME =='Notice' && in_array(ACTION_NAME, array('sendnoticeapi'))) return true;
		if(!user_info('userid') || !user_info('roleid')){
			//针对iframe加载返回
			if(IS_GET && strpos(ACTION_NAME,'_iframe') !== false){
				exit('<style type="text/css">body{margin:0;padding:0}a{color:#08c;text-decoration:none}a:hover,a:focus{color:#005580;text-decoration:underline}a:focus,a:hover,a:active{outline:0}</style><div style="padding:6px;font-size:12px">请先<a target="_parent" href="'.U('Index/login').'">登录</a>后台管理</div>');
			}
			if(IS_AJAX && IS_GET){

				exit('<div style="padding:6px">请先<a href="'.U('Index/login').'">登录</a>后台管理</div>');
			}else {
				$this->error('请先登录后台管理', U('Index/login'));
			}
		}
	}

	/**
	 * 权限控制
	 */
	private function checkPriv(){


		if(user_info('roleid') == 1) return true;

		//过滤不需要权限控制的页面
		switch (CONTROLLER_NAME){
			case 'Index':
				switch (ACTION_NAME){
					case 'index':
					case 'login':
					case 'code':
					case 'logout':
						return true;
						break;
				}
				break;
			case 'Upload':
			case 'Content':
				return true;
				break;
		}
		if(strpos(ACTION_NAME, 'public_')!==false) return true;

		$priv_db = M('admin_role_priv');
		$res     = $priv_db->where(array('c'=>CONTROLLER_NAME, 'a'=>ACTION_NAME, 'roleid'=>user_info('roleid')))->find();
        $config = C('CONTROLLER_NAME');
		if(!$res||in_array($res['c'],$config)){
            if (in_array(CONTROLLER_NAME,$config)){
                if (in_array(ACTION_NAME,C('CONTROLLER_NAMES'))){
                    $res     = $priv_db->where(array('c'=>CONTROLLER_NAME, 'a'=>'save', 'roleid'=>user_info('roleid')))->find();
                    if (!$res){
                        $this->error(L('public_jurisdiction_error'));
                    }
                }
                return true;
            }

			//兼容iframe加载
			if(IS_GET && strpos(ACTION_NAME,'_iframe') !== false){
				exit('<style type="text/css">body{margin:0;padding:0}</style><div style="padding:6px;font-size:12px">'.L('public_jurisdiction_error').'</div>');
			}
			if(IS_AJAX && IS_GET){
				exit('<div style="padding:6px">'.L('public_jurisdiction_error').'</div>');
			}else {
				$this->error(L('public_jurisdiction_error'));
			}
		}
	}

    /**
     * 记录操作日志
     */
    protected function configOperation($info){
        $ip        = get_client_ip(0, true);
        $username  = user_info('username');
        $userid    = user_info('userid');
        $time      = date('Y-m-d H-i-s');

        $log_db    = M('config_operation',NULL);
        $log = array(
            'username'    => $username,
            'userid'      => $userid,
            'time'        => $time,
            'ip'          => $ip
        );
        if ($info['platform']!="")
            $log['platform']=$info['platform'];
        if ($info['roleCode']!="")
            $log['rolecode']=$info['roleCode'];
        if ($info['operationType']!="")
            $log['operationtype']=$info['operationType'];
        if ($info['operationContent']!="")
            $log['operationcontent']=$info['operationContent'];
        if ($info['operationReason']!="")
            $log['operationreason']=$info['operationReason'];
        $log_db->add($log);
    }

	/**
	 * 记录日志
	 */
    private function operateLog($info){
		//判断是否记录
		if(C('SAVE_LOG_OPEN')){
			$action = ACTION_NAME;
			if($action == '' || strchr($action,'public') || (CONTROLLER_NAME =='Index' && in_array($action, array('login','code'))) ||  CONTROLLER_NAME =='Upload') {
				return false;
			}else {
				$ip        = get_client_ip(0, true);
				$username  = user_info('username');
				$userid    = user_info('userid');
				$time      = date('Y-m-d H-i-s');
				$data      = array('GET'=>$_GET);
				if(IS_POST) $data['POST'] = $_POST;
				$data      = var_export($data, true);

				$log_db    = M('log');
				$log = array(
                    'username'    => $username,
                    'userid'      => $userid,
                    'controller'  => CONTROLLER_NAME,
                    'action'      => ACTION_NAME,
                    'querystring' => $data,
                    'time'        => $time,
                    'ip'          => $ip
                );
				$log_db->add($log);
			}
		}
	}

	private function cookie(){
		//记录上次每页显示数
		if(I('get.grid') && I('post.rows')){
			switch(I('get.grid')){
				case 'datagrid':
					cookie('datagrid-pageSize', I('post.rows', 20, 'intVal'));
					break;
				case 'treegrid':
					cookie('treegrid-pageSize', I('post.rows', 2, 'intVal'));
					break;
				case 'propertygrid':
					cookie('propertygrid-pageSize', I('post.rows', 20, 'intVal'));
					break;
			}
		}
	}

	/**
	 * 空操作，用于输出404页面
	 */
	public function _empty(){
		//针对后台ajax请求特殊处理
		if(!IS_AJAX) send_http_status(404);
		if (IS_AJAX && IS_POST){
			$data = array('info'=>'请求地址不存在或已经删除', 'status'=>0, 'total'=>0, 'rows'=>array());
			$this->ajaxReturn($data);
		}else{
			$this->display('Common:404');
		}
	}

	/**
	 * 通用型datagrid页面
	 * @param array $param
	 */
	public function datagrid($param = array()){
		if(CONTROLLER_NAME == 'Common') return $this->_empty();

		$option = array(
			'db'      => null,
			'page'    => 1,
			'rows'    => 10,
			'where'   => array(),
			'sort'    => '',
			'order'   => '',
			'display' => '',
		);
		$option = array_merge($option, $param);

		if(IS_POST){
			$db = $option['db'];

			//排序，支持多个字段
			$sorts  = explode(',', $option['sort']);
			$orders = explode(',', $option['order']);
			$order  = array();
			foreach($sorts as $k=>$sort){
				$order[$sort] = $orders[$k];
			}

			$limit  = ($option['page'] - 1) * $option['rows'] . "," . $option['rows'];
			$total  = $db->where($option['where'])->count();
			$list   = $total ? $db->where($option['where'])->order($order)->limit($limit)->select() : array();

			if(isset($option['formatter'])){
				foreach($list as $key=>&$info){
					foreach($info as $key2=>&$value){
						$option['formatter']($key2, $value, $info);
					}
				}
			}

			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menuid     = I('get.menuid');
			$menu_db    = D('Menu');
			$currentpos = $menu_db->currentPos($menuid);  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);

			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);

			if(isset($option['assign']) && is_array($option['assign'])){
				$this->assign($option['assign']);
			}

			$this->display($option['display']);
		}
	}

    /** 
     * 发送HTTP请求方法 
     * @param  array  $params 请求参数 
     * @param  string $method 请求方法GET/POST 
     * @return array  $data   响应数据 
     */ 
    function GetGmToolApi($params){
        $sign = "";
        $data = array();

        foreach ($params as $key => $value)
        {
        	$sign .= $value;
            $data[$key] = $value;
        }
        $data["md5"] = md5($sign.C("GM_TOOL_API_KEY"));
        $post_data = array('data' => urlencode(json_encode($data)));
        return $this->http(C("GM_TOOL_API"),$post_data,"POST");
    }

    /** 
     * 发送HTTP请求方法 
     * @param  string $url    请求URL 
     * @param  array  $params 请求参数 
     * @param  string $method 请求方法GET/POST 
     * @return array  $data   响应数据 
     */  
    function http($url, $params, $method = 'GET', $header = array(), $multi = false){

        $opts = array(  
                CURLOPT_TIMEOUT        => 30,  
                CURLOPT_RETURNTRANSFER => 1,  
                CURLOPT_SSL_VERIFYPEER => false,  
                CURLOPT_SSL_VERIFYHOST => false,  
                CURLOPT_HTTPHEADER     => $header  
        );

        /* 根据请求类型设置特定参数 */  
        switch(strtoupper($method)){  
            case 'GET':  
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);  
                break;  
            case 'POST':  
                //判断是否传输文件  
                $params = $multi ? $params : http_build_query($params);  
                $opts[CURLOPT_URL] = $url;  
                $opts[CURLOPT_POST] = 1;  
                $opts[CURLOPT_POSTFIELDS] = $params;  
                break;  
            default:  
                throw new Exception('不支持的请求方式！');  
        }


        /* 初始化并执行curl请求 */
        $ch = curl_init();  
        curl_setopt_array($ch, $opts);  
        $data  = curl_exec($ch);  
        $error = curl_error($ch);

        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;  
    }

    /**
     * 获取物品分类列表
     **/
    function getItemType(){
        $admin_item_type_db = M('config_item_type',NULL);
        $item_type_list = $admin_item_type_db->order('typeid asc')->getField('typeid,typename', true);
        foreach($item_type_list as $k => $v){
            $item_type_list[$k] = L($v);
        }
        return $item_type_list;
    }

    /**
     * 获取物品分类列表
     **/
    function getItemList(){
        $typeid = $_POST["typeid"];
        if($typeid != ""){
            if ($typeid=='3'&&I('post.type')==1){
                $item_list = array(
                    1=>'무료보물함-브론즈III',
                    21=>'승리보물함-브론즈III',
                    41=>'용기보물함-브론즈III',
                    61=>'공훈보물함-브론즈III',
                    81=>'영광보물함-브론즈III',
                    101=>'영걸보물함-브론즈III',
                    121=>'정복보물함-브론즈III',
                    151=>'영웅보물함-브론즈III',
                    171=>'전설보물함-브론즈III',
                );
            }else{
                $admin_item_db = M('config_item',NULL);
                $item_list = $admin_item_db->where("itemtype='".$typeid."'")->getField('itemid,itemname');
            }
            foreach($item_list as $key =>$value){
                $item_list[$key]=$value.'  (ID:'.$key.')';
            }

                $platjson = "";
                if($typeid <> "0" && $typeid <> "1"){
                    $platjson .= '{"id":"","text":"'.L("email_please_select_items").'"},';
                }

                foreach ($item_list as $name=>$value)
                {

                if($name == "-1"){
                    $platjson .= '{"id":'.$name.',"text":"'.L($value).'"},';
                }else{

                    $platjson .= '{"id":'.$name.',"text":"'.$value.'"},';
                }
            }


            if(strlen($platjson) > 1)
            {
                $platjson = substr($platjson,0,strlen($platjson)-1);
            }
            //$platjson = '[{"id":0,"text":"所有","state" : "open","children":['.$platjson.']}]';
            $platjson = '['.$platjson.']';
            echo $platjson;
        }
    }

    /**
     * 动态构造模型（读取网站配置端口，默认为3306）
     * @param $tablename 表名
     * @param $tableprefix 表前缀
     * @return \Model model 实列
     */
    public function ZD_DBModel($tablename,$tableprefix)
    {
        //使用DB_DSN方式定义可以简化配置参数，DSN参数格式为：
        $connection=C('ZDZHG_DB_TYPE')."://".C('ZDZHG_DB_USER').":".C('ZDZHG_DB_PWD')."@".C("ZDZHG_DB_HOST").":".C("ZDZHG_DB_PORT")."/".C("ZDZHG_DB_NAME")."";
        return M($tablename,$tableprefix,$connection);
    }
    /**
     * 导出数据为excel表格
     *@param $data    一个二维数组,结构如同从数据库查出来的数组
     *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
     *@param $filename 下载的文件名
     *@examlpe 
    $stu = M ('User');
    $arr = $stu -> select();
    exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
     */
	public function exportexcel($data=array(),$title=array(),$filename='report'){
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel;charset=UTF-8");  
		header("Content-Disposition:attachment;filename=".$filename.date('YmdHis',time()).".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$table=$this->QueryListToTable_NoTable($title,$data);
		$result='
		<html xmlns:o="urn:schemas-microsoft-com:office:office"
		xmlns:x="urn:schemas-microsoft-com:office:excel"
		xmlns="http://www.w3.org/TR/REC-html40">
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		    <head>
		        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		        <style id="Classeur1_16681_Styles"></style>
		    </head>
		    <body>
		        <div id="Classeur1_16681" align=center x:publishsource="Excel">
		            <table x:str border=0 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">
		               '.$table.'
		            </table>
 		        </div>
		    </body>
		</html>';
		echo $result;
	}
    
    
    /**
     * 查询结果转换成HTML Table
     */	
	public function QueryListToTable_NoTable($title,$list)
	{
		$tablelist="";
		
		foreach($title as $skey=>$svalue)
		{
			$tablelist.="<td style='border:1px solid #e5e5e5;text-align:left;'>".$svalue."</td>";
		}
		foreach($list as $key=>$value)
		{
			$tablelist.="<tr>";
			
			foreach($value as $ckey=>$cvalue)
			{
				$tablelist.="<td style='border:1px solid #e5e5e5;text-align:left;'>".$cvalue."</td>";
			}
			$tablelist.="</tr>";
		}
		return $tablelist;
	}

    /*
     * 读取excel文件
     * */
    protected function excel($excel){
        vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new PHPExcel();
        return $objPHPExcel->load($excel);
    }
    public function GetSystemDate(){
        echo date("Y-m-d H:i:s");
    }
    public function GetSystemTime(){
        preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $langSet = $matches[1];
        if(strtolower($langSet) == "ko-kr"){
            echo time()-9*3600;
        }else{
            echo time()-8*3600;
        }
    }
    public function GetSystemTimes(){
        preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $langSet = $matches[1];
        if(strtolower($langSet) == "ko-kr"){
            return time()-9*3600;
        }else{
            return time()-8*3600;
        }
    }
}
