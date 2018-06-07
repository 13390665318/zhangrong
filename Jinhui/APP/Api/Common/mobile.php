<?php

/**
 * 作者：wanglidong
 * 功能：手机号检查
 */
function check_mobile($mobile) {
    $search = '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
    if (preg_match($search, $mobile)) {
    } else {
        //echo_error(2004);
    }
    if (strlen($mobile) != 11) {
        echo_error(2004);
    }
}

/**
 * 作者：chenyifan
 * 功能：发送短信验证码
 * @param $phone
 * @param $code
 * @param $time
 * @return mixed
 */
function sendSMS($phone, $code, $time) {

    $softwareSerialNo = "3SDK-EMY-0130-JCRQO";// 软件序列号,请通过亿美销售人员获取
    $key              = "8c582235cfba7fbfd477eb59ac9ae1b6";// 序列号首次激活时自己设定
    $password         = "793777";// 密码,请通过亿美销售人员获取
    $ws               = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService?wsdl';

    $client = new SoapClient($ws);

    $result = $client->sendSMS(array(
        'arg0' => $softwareSerialNo,
        'arg1' => $key,
        'arg2' => '', // 定时短信的定时时间，格式为:年年年年月月日日时时分分秒秒 sendTime值为空时，为即时发送短信
        'arg3' => array($phone),
        'arg4' => "【全球花木网】尊敬的用户：您的验证码是" . $code . "，" . $time . "分钟内有效",
        'arg5' => '',
        'arg6' => 'GBK',
        'arg7' => '5',
        'arg8' => time(),
    ));

    return $result;
}