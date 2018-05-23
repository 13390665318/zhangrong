<?php

/**
 * 作者：wanglidong
 * 功能：errcode不为0时输出
 */
function echo_error($errcode, $errmsg = '')
{
    if ($errmsg == '') {
        exit(json_encode(array('errcode' => "$errcode")));
    } else {
        if (version_compare(PHP_VERSION, '5.4.0', '>')) {
            exit(json_encode(array('errcode' => "$errcode", 'errmsg' => "$errmsg"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            exit(json_encode(array('errcode' => "$errcode", 'errmsg' => "$errmsg")));
        }
    }
}

/**
 * 作者：wanglidong
 * 功能：errcode为0输出
 */
function echo_success($data = array(), $errcode = 0)
{
    $errcode = array('errcode' => "$errcode");
    if (!is_array($data)) {
        $data['data'] = $data;
    }
    $data = array_merge($errcode, $data);

    if (version_compare(PHP_VERSION, '5.4.0', '>')) {
        exit(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    } else {
        exit(json_encode($data));
    }
}

/**
 * 作者：wanglidong
 * 功能：获取上传参数并检查有没传必传参数
 */
function get_param($need_params, $optional_params)
{
    $access_token = I('get.access_token');
    $timestamp = I('get.timestamp');
    $platform = I('get.platform');
    $data = $_POST['data'];

    if ($platform == 'enterprise') {
        C('taobao_appkey', 23290646);
        C('taobao_secret', 'bcfd825e4679e891f20c8156c5d2cd72');
        echo_error(-1, '此版本已不再支持，请更新');
    }
    $data=str_replace(" ","+",$data);
 //var_dump($data);exit;

    //无参数时
    if (!empty($data)) {
     //   $data="eyJtb2JpbGVfbW9kZWwiOiLljY7kuLoiLCJtb2JpbGVfdHlwZSI6IkFuZHJvaWQiLCJnYW1lX25hbWUiOiLmtYvor5UiLCJnYW1lX3VzZXJfbmFtZSI6InpoYW5nc2FuIiwiY2xvdGhlcyI6IjMiLCJnYW1lX3VzZXJfaWQiOiIxOCJ9";
      $data = base64_decode($data);
   //   var_dump($data);exit;
        if (!$data) {
            echo_error(1006);
        }

        if (!empty($data)) {
            $data = json_decode($data,true);
      //var_dump($data);exit;
            if (!$data) {
            echo_error(1004);
            }
        }
    }

    foreach ($need_params as $k => $v) {
        if (!isset($data[$v]) || $data[$v] == '') {
            echo_error(1005, '缺少参数：' . $v);
        } else {
            $params[$v] = $data[$v];
           // check_params($v, $data[$v]);
        }
    }

    foreach ($optional_params as $k => $v) {
        if (isset($data[$v])) {
            $params[$v] = $data[$v];
           // check_params($v, $data[$v]);
        }
    }

    if (in_array('user_id', $need_params) && in_array('to_user_id', $optional_params)) {
        $identity_auth = M('user')->where(array('user_id' => $data['to_user_id']))->getField('identity_auth');
        if (!($identity_auth == '1')) {
            $user = $data['user_id'];
            $data['user_id'] = $data['to_user_id'];
            $data['to_user_id'] = $user;
        }
    }



    $add['action'] = CONTROLLER_NAME . '/' . ACTION_NAME;
    $add['content'] = json_encode($params);
    $add['user_id'] = $params['user_id'];
    $add['ip'] = get_client_ip();
    $add['remark'] =1213;
    M('params_log')->add($add);
    return $params;
}


/**
 * 获取app配置
 * @param string $name
 * @return mixed|string
 */
function app_config($name = '')
{
    if (!$name) {
        return '';
    }

    $map['name'] = $name;

    return M('app_config')->where($map)->getField('value');
}

function check_address($data)
{
    $result = $data;
    if (!empty($data['city'])) {
        $map['level'] = 1;
        $map['name'] = array('like', $data['province'] . '%');
        $r = M('district')->where($map)->find();
        $result['province'] = $r['name'];
        if ($r['id'] == 1 || $r['id'] == 2 || $r['id'] == 9 || $r['id'] == 22) {
            $result['city'] = $r['name'];
            if (empty($result['district'])) {
                $map['level'] = 3;
                $map['name'] = array('like', $data['city'] . '%');
                $r = M('district')->where($map)->find();
                $result['district'] = $r['name'];
            }
        } else {
            $map['level'] = 2;
            $map['name'] = array('like', $data['city'] . '%');
            $map['pid'] = $r['id'];
            $r = M('district')->where($map)->find();
            $result['city'] = $r['name'];
        }
    }

    $result['province'] = empty($result['province']) ? '' : $result['province'];
    $result['city'] = empty($result['city']) ? '' : $result['city'];
    $result['district'] = empty($result['district']) ? '' : $result['district'];
    return $result;
}