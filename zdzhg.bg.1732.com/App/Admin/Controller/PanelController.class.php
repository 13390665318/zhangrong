<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 我的面板模块
 * @author wangdong
 */
class PanelController extends CommonController {

	/**
	 * 登录日志
	 */
	public function login($search = array(), $page = 1, $rows = 10, $sort = 'time', $order = 'desc'){
		$userid = user_info('userid');

		//搜索
		$where = array("`type` = 'login'", "`userid` = {$userid}");
		foreach ($search as $k=>$v){
			if(strlen($v) < 1) continue;
			switch ($k){
				case 'httpuseragent':
				case 'ip':
					$where[] = "`{$k}` like '%{$v}%'";
					break;
				case 'time.begin':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` >= '{$v}'";
					break;
				case 'time.end':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` <= '{$v}'";
					break;
			}
		}
		$where = implode(' and ', $where);

		$this->datagrid(array(
			'db'    => M('admin_log'),
			'where' => $where,
			'page'  => $page,
			'rows'  => $rows,
			'sort'  => $sort,
			'order' => $order,
		));
	}

	/**
	 * 删除一个月前数据
	 */
	public function loginDelete(){
		if(IS_POST){
			$userid       = user_info('userid');
			$admin_log_db = M('admin_log');
			$date         = date('Y-m-d', strtotime('last month'));
			$where        = "`type` = 'login' AND `userid` = {$userid} AND left(`time`, 10) <= '{$date}'";
			$result       = $admin_log_db->where($where)->delete();
			$result ? $this->success(L("public_return_delete_success")) : $this->error(L("public_data_was_not_checked"));
		}
	}

	/**
	 * 操作日志
	 */
	public function operate($search = array(), $page = 1, $rows = 10, $sort = 'time', $order = 'desc'){
		$userid = user_info('userid');

		//搜索
		$where = array("`userid` = {$userid}");
		foreach ($search as $k=>$v){
			if(strlen($v) < 1) continue;
			switch ($k){
				case 'controller':
				case 'action':
				case 'querystring':
				case 'ip':
					$where[] = "`{$k}` like '%{$v}%'";
					break;
				case 'time.begin':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` >= '{$v}'";
					break;
				case 'time.end':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` <= '{$v}'";
					break;
			}
		}
		$where = implode(' and ', $where);

		$this->datagrid(array(
			'db'    => M('log'),
			'where' => $where,
			'page'  => $page,
			'rows'  => $rows,
			'sort'  => $sort,
			'order' => $order,
		));
	}

	/**
	 * 删除一个月前数据
	 */
	public function operateDelete(){
		if(IS_POST){
			$userid = user_info('userid');
			$log_db = M('log');
			$date   = date('Y-m-d', strtotime('last month'));
			$where  = "`userid` = {$userid} AND left(`time`, 10) <= '{$date}'";
			$result = $log_db->where($where)->delete();
			$result ? $this->success(L("public_return_delete_success")) : $this->error('没有数据或已删除过了，请稍后再试');
		}
	}

    /**
     * 操作日志
     */
    public function operationRecord(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $type =  C('OPERATION_RECORD_TYPE');
        $data[1]= $type[1];
        $data[2]= $type[2];
        $data[3]= $type[3];
        $data[12]= $type[12];
        $data[8]= $type[8];
        $data[4]= $type[4];
        $data[6]= $type[6];
        $data[9]= $type[9];
        $data[10]= $type[10];
        $data[11]= $type[11];
        $data[13]= $type[13];
        $this->assign('type', $data);
        $this->display('operation_record');
    }

    /**
     * 操作日志列表
     */
    public function operationRecordPost(){
        if (IS_POST){
            $data = I('post.');
            $where =array();
            foreach ($data as $k=>$v){
                if(strlen($v['value']) < 1) continue;
                switch ($v['name']) {
                    case 'type':if ($v['value']!=0) $where['operationtype'] = $v['value'];break;
                    case 'username':$where['username'] = $v['value'];break;
                    case 'platform':$where['platform'] = $v['value']-10;break;
                    case 'rolecode':if ($v['value']!="#") $where['rolecode'] = $v['value'];break;
                    case 'start':
                        if(!check_datetime($v['value'])){
                            unset($data[$k]);
                            continue;
                        }
                        $where[] = "`time` >= '{$v['value']}'";
                        break;
                    case 'end':
                        if(!check_datetime($v['value'])){
                            unset($data[$k]);
                            continue;
                        }
                        $where[] = "`time` <= '{$v['value']}'";
                        break;
                }
            }

            $operation = M('config_operation',NULL);
            $list = $operation->where($where)->order('time desc')->page(I('post.page').','.I('post.rows'))->select();

            $total = $operation->where($where)->count();

            $type = C('OPERATION_RECORD_TYPE');

            $plat = C('PLAT_LIST');
            foreach ($list as $key=>$val){

                $list[$key]['operationtype'] = $type[$val['operationtype']];
                if ($val['platform']!=""){
                    $list[$key]['rolecode'] = $plat[$val['platform']];
                    if ($val['platform']==3){
                        $list[$key]['rolecode'] = L('public_use_platform_number');
                    }
                }
                $content = explode('&',$val['operationcontent']);

                $list[$key]['operationcontent'] = "";
                if (count($content)>1) {
                    foreach ($content as $v){
                        if (substr($v , 0 , 1)=='*'&&$v!=''){
                            $list[$key]['operationcontent'] .= L(substr($v , 1));
                        }else if ($v!=''){
                            $list[$key]['operationcontent'] .= $v;
                        }
                    }
                }else{
                    $list[$key]['operationcontent'] .= L($val['operationcontent']);
                }
            }

            $this->ajaxReturn(array('rows'=>$list,'total'=>$total));
        }
    }

    /**
     *获取操作记录条件
     * @Get (int)
     * 1:获取平台
     * 2:获取操作人员
     * */
    public function getSmallInfo(){
        if (I('get.type')==1){
            $type = C('PLAT_LIST');
            foreach ($type as $key=>$val){
                $data[]=array(
                    'label'=>$key+10,
                    'value'=>$val
                );
            }
           $this->ajaxReturn($data);
        }elseif (I('get.type')==2){
            $this->ajaxReturn(M('admin')->where()->select(array('username')));
        }

    }

}