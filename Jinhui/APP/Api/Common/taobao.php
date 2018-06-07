<?php
/**
 * 作者：wanglidong
 * 功能：向阿里百川服务器添加聊天用户
 */
function taobao_users_add($user_id, $data = '') {
    include APP_PATH . "Common/Util/taobao-sdk/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');

    $appkey = 23260199;
    $secret = 'a7514b68aacd059766929d467e751696';

    $c            = new \TopClient;
    $c->appkey    = $appkey;
    $c->secretKey = $secret;
    $req          = new \OpenimUsersAddRequest;
    $userinfos    = new \Userinfos;
    empty($data['nickname']) ?: $userinfos->nick = $data['nickname'];
    empty($data['avatar']) ?: $userinfos->icon_url = convert_absolute($data['avatar']);
    //$userinfos->email="uid@taobao.com";
    empty($data['mobile']) ?: $userinfos->mobile = $data['mobile'];
    //$userinfos->taobaoid="tbnick123";
    $userinfos->userid   = $user_id;
    $userinfos->password = "1234";
    //$userinfos->remark="demo";
    //$userinfos->extra="demo";
    //$userinfos->career="demo";
    //$userinfos->vip="demo";
    //$userinfos->address="demo";
    empty($data['nickname']) ?: $userinfos->name = $data['nickname'];
    //$userinfos->age="123";
    //$userinfos->gender="demo";
    //$userinfos->wechat="demo";
    //$userinfos->qq="demo";
    //$userinfos->weibo="demo";
    $req->setUserinfos(json_encode($userinfos));
    $resp = $c->execute($req);
    //dump($resp);
    //die;
}

/**
 * 作者：wanglidong
 * 功能：向阿里百川服务器修改聊天用户
 */
function taobao_users_update($user_id, $data = array()) {
    include APP_PATH . "Common/Util/taobao-sdk/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');

    $appkey = 23260199;
    $secret = 'a7514b68aacd059766929d467e751696';

    $c            = new \TopClient;
    $c->appkey    = $appkey;
    $c->secretKey = $secret;
    $req          = new \OpenimUsersUpdateRequest;
    $userinfos    = new \Userinfos;
    empty($data['nickname']) ?: $userinfos->nick = $data['nickname'];
    empty($data['avatar']) ?: $userinfos->icon_url = convert_absolute($data['avatar']);
    //$userinfos->email    = "uid@taobao.com";
    empty($data['mobile']) ?: $userinfos->mobile = $data['mobile'];
    //$userinfos->taobaoid = "tbnick123";
    $userinfos->userid   = "$user_id";
    $userinfos->password = "1234";
    //$userinfos->remark   = "demo";
    //$userinfos->extra    = "demo";
    empty($data['job']) ?: $userinfos->career = $data['job'];
    //$userinfos->vip      = "demo";
    //$userinfos->address  = "demo";
    empty($data['nickname']) ?: $userinfos->name = $data['nickname'];
    //$userinfos->age      = "123";
    //$userinfos->gender   = "demo";
    //$userinfos->wechat   = "demo";
    //$userinfos->qq       = "demo";
    //$userinfos->weibo    = "demo";
    $req->setUserinfos(json_encode($userinfos));
    $resp = $c->execute($req);
}

/**
 * 作者：wanglidong
 * 功能：向阿里百川服务器查询聊天用户
 */
function taobao_users_get($user_id) {
    include APP_PATH . "Common/Util/taobao-sdk/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');

    $appkey = 23260199;
    $secret = 'a7514b68aacd059766929d467e751696';

    $c            = new \TopClient;
    $c->appkey    = $appkey;
    $c->secretKey = $secret;
    $req          = new OpenimUsersGetRequest;
    $req->setUserids("$user_id");
    $resp = $c->execute($req);
    dump($resp);
}

/**
 * 作者：wanglidong
 * 功能：推送
 */
function taobao_cloudpush_push() {
    include_once APP_PATH . "Common/Util/taobao-sdk/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');

    $appkey = 23260199;
    $secret = 'a7514b68aacd059766929d467e751696';

    $c            = new \TopClient;
    $c->appkey    = $appkey;
    $c->secretKey = $secret;
    $req          = new \CloudpushPushRequest;

    $req->setTarget("account");
    $req->setTargetValue("99");
    $req->setBody("111");
    $req->setDeviceType(3);
    $req->setRemind("false");
    $req->setStoreOffline("true");
    $req->setTitle("use");
    $req->type("0");
    $resp = $c->execute($req);

    dump($resp);
}

function taobao_open_sms_sendmsg($mobile, $code) {
    include APP_PATH . "Common/Util/taobao-sdk/TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');

    $appkey = 23260199;
    $secret = 'a7514b68aacd059766929d467e751696';

    $c                                 = new \TopClient;
    $c->appkey                         = $appkey;
    $c->secretKey                      = $secret;
    $req                               = new \OpenSmsSendmsgRequest;
    $send_message_request              = new \SendMessageRequest;
    $send_message_request->template_id = "234";
    //$send_message_request->signature_id="123";
    $send_message_request->context = json_decode("{\"code\":\"" . $code . "\"}");
    //$send_message_request->external_id="demo";
    $send_message_request->mobile = "$mobile";
    //$send_message_request->device_limit="123";
    //$send_message_request->session_limit="123";
    //$send_message_request->device_limit_in_time="123";
    //$send_message_request->mobile_limit="123";
    //$send_message_request->session_limit_in_time="123";
    //$send_message_request->mobile_limit_in_time="123";
    //$send_message_request->session_id="demo";
    //$send_message_request->domain="demo";
    //$send_message_request->device_id="demo";
    $req->setSendMessageRequest(json_encode($send_message_request));
    $resp = $c->execute($req);

    return $resp;
    //dump($resp);die;
}