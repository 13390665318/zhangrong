<?php

namespace Home\Controller;

class IndexController extends BaseController
{
    /**
     *by ZHANG
     **/
    public function index()
    {
        if (isset($_GET["start_time"]) && isset($_GET["end_time"])) {
            $stime = I("get.start_time");
            $etime = I("get.end_time");
        } else {
            $stime = date("Y-m-01 00:00:00", time());
            $etime = date("Y-m-d H:i:s", time());
        }
        $this->assign("Stime",$stime);
        $this->assign("Etime",$etime);

        $oil = D('oil')->order('start_time desc')->select();

        foreach ($oil as $key =>$value){
            if(($oil[$key]['licheng'])!=""){
                $oil[$key]['baiyou']=round(($oil[$key]['danci']/$oil[$key]['licheng'])*100,2);
                D('oil')->where("id='$value[id]'")->setField('baiyou',$oil[$key]['baiyou']);
                $oil[$key]['danxiao']=round(($oil[$key]['danci']/$oil[$key]['licheng'])*$oil[$key]['danjia'],2);
                D('oil')->where("id='$value[id]'")->setField('danxiao',$oil[$key]['danxiao']);
            }
        }

        $this->assign('oil', $oil);
        $this->display();
    }

    function sendSms($phone, $code)
    {

        // 基于TP3.2开发

        //引进阿里的配置文件

        Vendor('api_sdk.vendor.autoload');


        // 加载区域结点配置
        \Aliyun\Core\Config::load();
        // 初始化用户Profile实例
        $profile = \Aliyun\Core\Profile\DefaultProfile::getProfile(C('ALI_SMS.REGION'), C('cfg_smssid'), C('cfg_smstoken'));


        // 增加服务结点
        \Aliyun\Core\Profile\DefaultProfile::addEndpoint(C('ALI_SMS.END_POINT_NAME'), C('ALI_SMS.REGION'), C('ALI_SMS.PRODUCT'), C('ALI_SMS.DOMAIN'));
        // 初始化AcsClient用于发起请求
        $acsClient = new \Aliyun\Core\DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new \Aliyun\Api\Sms\Request\V20170525\SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称
        $request->setSignName(C('cfg_smsname'));
        // 必填，设置模板CODE
        $request->setTemplateCode('SMS_122281847');
        $params = array(
            'code' => $code
        );
        // 可选，设置模板参数
        $request->setTemplateParam(json_encode($params));
        // 可选，设置流水号
        //if($outId) {
        //    $request->setOutId($outId);
        //}
        // 发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        // 打印请求结果
        // var_dump($acsResponse);
        return $acsResponse;
    }


    //config配置文件中要写上参数


}