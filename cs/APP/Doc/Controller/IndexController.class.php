<?php
namespace Doc\Controller;

use Think\Controller;

class IndexController extends Controller {
    private $prefix = 'baimifan-api_api_';  //cookie前缀

    public function _initialize() {
        header('Content-Type:text/html;charset=utf-8');
        $r = M('ApiSetting')->field('name,txt')->select();
        foreach ($r as $k => $v) {
            $name              = $v['name'];
            $apiSetting[$name] = $v['txt'];
        }
        $this->apiSetting = $apiSetting;
    }

    /**
     * 作者：wanglidong
     * 功能：接口说明
     */
    public function explain() {
        $map['isshow'] = 1;

        $m = M('ApiErrcode');

        $this->apiErrcode = $m
            ->where($map)
            ->order('sort,errcode')
            ->select();
        $this->display();
    }

    /**
     * 作者：wanglidong
     * 功能：接口文档
     */
    public function index() {
        $map['isshow']   = 1;
        $where['isshow'] = 1;

        $apiType    = M('ApiType')->alias('a')
            ->field('')
            ->where($map)
            ->order('sort')
            ->select();
        $mApi       = M('Api');
        $mApiPost   = M('ApiPost');
        $mApiResult = M('ApiResult');
        $i          = 0;
        foreach ($apiType as $k => $v) {
            $map['type_id']     = $v['id'];
            $apiType[$k]['api'] = $mApi->where($map)->order('sort')->select();
            foreach ($apiType[$k]['api'] as $key => $value) {
                $where['api_id']                    = $value['id'];
                $apiType[$k]['api'][$key]['post']   = $mApiPost->where($where)->order('sort,parent_id,id')->select();
                $apiType[$k]['api'][$key]['result'] = $mApiResult->where($where)->order('sort')->select();
                //$apiType[$k]['api'][$key]['post'] = self::tree($apiType[$k]['api'][$key]['post']);

                $api[$i]         = $value;
                $api[$i]['post'] = $apiType[$k]['api'][$key]['post'];

                //无限级分类
                $api[$i]['post'] = self::tree($api[$i]['post']);

                //html 标签处理
                foreach ($api[$i]['post'] as &$po) {
                    $po['remark']  = htmlspecialchars_decode($po['remark']);
                    $po['remark1'] = strip_tags($po['remark']);
                }
                $api[$i]['result'] = $apiType[$k]['api'][$key]['result'];
                foreach ($api[$i]['result'] as $kk => $vv) {
                    if ($vv['updatetime'] > $api[$i]['updatetime']) {
                        $api[$i]['updatetime'] = $vv['updatetime'];
                    }
                    $api[$i]['result'][$kk]['txt'] = htmlspecialchars_decode($vv['txt']);
                }

                $name   = $this->prefix . $value['id'];
                $cookie = cookie($name);
                $cookie = json_decode($cookie, true);
                foreach ($api[$i]['post'] as $kk => $vv) {
                    $name = $vv['name'];

                    $api[$i]['post'][$kk]['example'] = $cookie[$name];

                    if ($vv['updatetime'] > $api[$i]['updatetime']) {
                        $api[$i]['updatetime'] = $vv['updatetime'];
                    }
                }

                $i++;
            }
        }
	var_dump();
	for($i=0;$i<count($api);$i++){
		$str=$api[$i]["name"];
		$arr = explode("/",$str);
		
		$api[$i]["name"]="c=".$arr[0]."&a=".$arr[1];
		
	}	
        $this->url     = 'https://' . $_SERVER['HTTP_HOST'] . __APP__ . '/';
        $this->apilist = $api;
        $this->vlist   = $apiType;
        $this->display();
    }

    //无限级分类
    static public function tree($data, $pid = 0, $count = 0) {
        $arr = array();
        foreach ($data as $value) {
            if ($value['parent_id'] == $pid) {
                $value['v'] = $count;
                $arr[]      = $value;
                $arr        = array_merge($arr, self::tree($data, $value['id'], $count + 1));
            }
        }

        return $arr;
    }

    /**
     * 作者：wanglidong
     * 功能：接口文档模拟app访问
     */
    public function webDebug() {
        $api_id         = $_POST['api_id'];
        $api_post_name  = $_POST['api_post_name'];
        $api_post_value = $_POST['api_post_value'];


        //保存到cookie
        foreach ($api_post_name as $k => $v) {
            $cookie[$v] = $api_post_value[$k];
        }
        //dump($cookie);die;
        $data = json_encode($cookie);
        cookie($api_id, $data, array('expire' => 7776000, 'prefix' => $this->prefix));
        //访问
        $timestamp = time();

        $base64       = base64_encode($data);
        $access_token = sha1($base64 . $timestamp . 'baimifan.net');
        $api_name     = M('Api')->where(array('id' => $api_id))->getField('name');
        $url          = 'http://' . $_SERVER['HTTP_HOST'] . U('Api/' . $api_name, array('access_token' => $access_token, 'timestamp' => $timestamp));

        $msg = curl_post($url, array('data' => $base64));
        $msg = json_decode($msg);
        // 接口异常处理
        if (empty($msg)) {
            echo '接口报异常：<br>';
            var_dump($msg);
            die;
        }
        dump($msg);
    }

    /**
     * 作者：chenyifan
     * 功能：接口文档模拟访问数据生成
     */
    public function debug() {

        $api_id         = $_POST['api_id'];
        $api_post_name  = $_POST['api_post_name'];
        $api_post_value = $_POST['api_post_value'];


        //保存到cookie
        foreach ($api_post_name as $k => $v) {
            $cookie[$v] = $api_post_value[$k];
        }

       $data = json_encode($cookie, JSON_UNESCAPED_UNICODE);
       //"{"game_user_id":"1","game_name":"\u6d4b\u8bd5","clothes":"1","mobile_type":"IOS"}"

        cookie($api_id, $data, array('expire' => 7776000, 'prefix' => $this->prefix));
      //  cookie($data);
        //访问
        $timestamp = time();

        $base64       = base64_encode($data);
        $access_token = sha1($base64 . 1425860757 . 'hollow2017averyone');
        $api_name     = M('Api')->where(array('id' => $api_id))->getField('name');
        $url          = 'http://' . $_SERVER['HTTP_HOST'] . U('Api/' . $api_name, array('access_token' => $access_token, 'timestamp' => $timestamp));

        $data = array(
            'url'  => $url,
            'data' => $base64,
            'a'=>$data,
            'access_token'=>$access_token,
            'post'=>$cookie


        );

        echo json_encode($data);
    }
}