<?php
/**
 * 上传目录列表
 * @param string $path 目录名
 * @param string $exts 获取后缀名
 * @return array
 */
function file_list_upload($path, $exts = '', &$res = array()){
	$list = file_dir($path);
	foreach($list as $info){
		if($info['type'] == 'dir') continue;

		if ($exts && !preg_match("/({$exts})$/i", $info['ext'])) continue;

		$info['url'] = str_replace(UPLOAD_PATH, '', $path) . $info['path'];
		array_push($res, $info);
	}
	return $res;
}

/**
 * 用户信息管理
 * @param string $name
 * @param string $value
 * @return mix
 */
function user_info($name = '', $value = ''){
	$time = 3600 * 24;
	$key  = cookie('identity');
	if(!$key){
		$key = uuid();
		cookie('identity', $key, array('httponly'=>true));
	}

	$info = S($key);
	//清除数据
	if($name === null){
		S($key, null);
		return null;
	}

	if(empty($value)){
		if(empty($name)) return $info;
		if(isset($info[$name])) return $info[$name];
	}else{
		if(empty($name)){
			$info = $value;

			if(isset($info['password'])) unset($info['password']);
			if(isset($info['encrypt'])) unset($info['encrypt']);
		}else{
			$info[$name] = $value;
		}
		S($key, $info, $time);
	}
	return null;
}
/**
 * 数据库选择
 * @param string $name
 * @param string $value
 * @return mix
 */
function db(){
    $connection = array(
        'db_type'  => "mysql",
        'db_user'  => "root",
        'db_pwd'   => "root",
        'db_host'  =>"192.168.7.85",
        'db_port'  => "3306",
        'db_name'  =>"newstatisticsdb",
        'db_charset' => 'utf8',
        'db_prefix'  => '',
    );
    return $connection;
}
function count_days($a,$b){
    $d1 = strtotime($a);
    $d2 = strtotime($b);
    $a_dt = getdate($d1);
    $b_dt = getdate($d2);
    $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
    $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
    return round(abs($a_new-$b_new)/86400);
}

/**
 * 生成HTML中的id
 * @param string|array $subfix 后缀
 * @param string       $prefix 前缀
 * @return string
 */
function html_id($subfix = '', $prefix = 'ID'){
	$items = array(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME, http_build_query($_REQUEST));
	if($subfix){
		if(is_string($subfix)) array_push($items, $subfix);
		if(is_array($subfix)) $items = array_merge($items, $subfix);
	}

	$str  = substr(md5(implode('-', $items)), 8, 16);
	$code = array($prefix);  //id必须已字母开头
	$code = array_merge($code, str_split($str, 4));

	return strtoupper(implode('-', $code));
}

/**
 * 验证是否为datetime格式
 * @param string $str
 * @return bool
 */
function check_datetime($str = ''){
	if(!preg_match("/^\d{4}(-\d{2}){2} \d{2}(\:\d{2}){2}$/", $str)){
		return false;
	}
	return true;
}

/**
 * 验证是否为date格式
 * @param string $str
 * @return bool
 */
function check_date($str = ''){
	if(!preg_match("/^\d{4}(-\d{2}){2}$/", $str)){
		return false;
	}
	return true;
}

/**
 * 菜单图标
 * @param int $level
 * @param string|null $icon
 * @return string;
 */
function menu_icon($level, $icon = null){
	if($icon) return $icon;

	switch($level){
		case 1:
			$icon = 'fa fa-home';
			break;

		case 2:
			$icon = 'fa fa-inbox';
			break;

		case 3:
			$icon = 'fa fa-puzzle-piece';
			break;

		case 4:
			$icon = 'fa fa-file-o';
			break;

		default:
			$icon = 'fa fa-puzzle-piece';
	}

	return $icon;
}


/**
 * 文本id
 */
function textTypeList($type,$state = 0){
    $langSet = strtolower(cookie('think_language'));
    $field = "tid,";
    $language = "chinese";
    switch ($langSet){
        case "zh-cn":$language ='chinese';break;
        case "ko-kr":$language ='korean';break;
        case "en-us":$language ='english';break;
    }
    $field .= $language;
    $text = M('language_text',null)->where(array('type'=>$type))->field($field)->select();

    foreach ($text as $val){
        if ($state){
            $info[] = array('label'=>$val['tid'],'value'=> $val[$language]);
        }else{
            $info[$val['tid']] = $val[$language];
        }
    }
    return $info;
}