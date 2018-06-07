<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\String;

class ApiController extends Controller
{
 //   private $prefix = 'baimifan-api_api_';  //cookie前缀

    public function _initialize()
    {
        $r = M('ApiSetting')->field('name,txt')->select();
        foreach ($r as $k => $v) {
            $name = $v['name'];
            $apiSetting[$name] = $v['txt'];
        }
        $this->apiSetting = $apiSetting;
    }

    /**
     * 接口说明
     */
    public function explain()
    {
        $m = M('ApiErrcode');
        $map['isshow'] = 1;
        $this->apiErrcode = $m
            ->where($map)
            ->order('sort,errcode')
            ->select();


        $this->display();
    }

    /**
     * 接口文档
     */
    public function index()
    {
        header('Content-Type:text/html;charset=utf-8');
        $map['isshow'] = 1;
        $where['isshow'] = 1;

        $apiType = M('ApiType')->alias('a')
            ->field('')
            ->where($map)
            ->order('sort')
            ->select();
        $mApi = M('Api');
        $mApiPost = M('ApiPost');
        $mApiResult = M('ApiResult');
        $i = 0;
        foreach ($apiType as $k => $v) {
            $map['type_id'] = $v['id'];
            $apiType[$k]['api'] = $mApi->where($map)->order('sort')->select();
            foreach ($apiType[$k]['api'] as $key => $value) {
                $where['api_id'] = $value['id'];
                $apiType[$k]['api'][$key]['post'] = $mApiPost->where($where)->order('sort')->select();
                $apiType[$k]['api'][$key]['result'] = $mApiResult->where($where)->order('sort')->select();
                //$apiType[$k]['api'][$key]['post'] = self::tree($apiType[$k]['api'][$key]['post']);

                $api[$i] = $value;
                $api[$i]['post'] = $apiType[$k]['api'][$key]['post'];

                //无限级分类
                $api[$i]['post'] = self::tree($api[$i]['post']);

                //html 标签处理
                foreach($api[$i]['post'] as &$po){
                    $po['remark']=htmlspecialchars_decode($po['remark']);
                    $po['remark1']=strip_tags($po['remark']);
                }
                $api[$i]['result'] = $apiType[$k]['api'][$key]['result'];
                foreach($api[$i]['result'] as &$re){
                    $re['txt']=htmlspecialchars_decode($re['txt']);
                }

                $name = $this->prefix . $value['id'];
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
        $this->url = 'https://' . $_SERVER['HTTP_HOST'] . __APP__ . '/';
        $this->apilist = $api;
        $this->vlist = $apiType;
        $this->display();
    }

    //无限级分类
    static public function tree($data,$pid = 0,$count = 0) {
        $arr = array();
        foreach ($data as $value){
            if($value['parent_id']==$pid){
                $value['v'] = $count;
                $arr[] = $value;
                $arr = array_merge($arr,self::tree($data,$value['id'],$count+1));
            }
        }
        return $arr;
    }

    /**
     * 接口文档测试处理
     * 模拟接口访问
     */
    public function ajaxTest()
    {
        $api_id = $_GET['api_id'];
        $api_name = $_GET['api_name'];
        $data = $_GET;
        unset($data['api_id']);
        unset($data['api_name']);
        $data = json_encode($data);


        cookie($api_id, $data, array('expire' => 7776000, 'prefix' => $this->prefix));
        $timestamp = time();
        $access_token = sha1($data . $timestamp . 'hollow2017averyone');

        $url = U('Api' . $api_name, array('access_token' => $access_token, 'timestamp' => $timestamp));


        $name = $this->prefix . $api_id;
        dump($name);
        $cookie
            = $_COOKIE[$name];
        $cookie = json_decode($cookie, true);
        dump($cookie);
        die;

        $id = I('id');
        $m = M('Api');
        $r = $m->alias('a')
            ->field('a.name,b.name as typename')
            ->join('LEFT JOIN __API_TYPE__ AS b ON a.type_id=b.id')
            ->where("a.id=$id")
            ->find();
        $msg['timestamp'] = time() . '';
        $msg['action'] = $r['typename'] . '/' . $r['name'];

        $rows = I('rows');
        foreach ($rows as $k => $v) {
            $name = $v['name'];
            $data[$name] = $v['example'];
            $insertData[$k]['post_id'] = $v['id'];
            $insertData[$k]['value'] = $v['example'];
        }
        $msg['data'] = $data;
        echo '上传内容：';
        dump($msg);

        //保存到数据库中
        $str = $this->getCookieName();
        $cookie = cookie($str);
        if (empty($cookie)) {
            $cookie = String::uuid();
            $time = 60 * 60 * 24 * 365 * 10;    //保存10年
            cookie($str, $cookie, $time);
        }
        $m = M('ApiCookie');
        foreach ($insertData as $k => $v) {
            $v['uuid'] = $cookie;
            $m->add($v, $options = array(), $replace = true);
        }

        $msg = msg_encrypt($msg);
        $url = C('BASE_URL') . U('Api/Index/index');
        $data = array();
        $data['msg'] = $msg;
        //todo post方式
        $msg = http($url, $data, 'GET', array("Content-type: text/html; charset=utf-8"));
        $r = base64_decode($msg);

        // 接口异常处理
        if (empty($r) || $msg == '<h1>Forbidden</h1>' || empty($msg)) {
            echo '接口报异常：<br>';
            var_dump($msg);
            die;
        }

        $msg = msg_decrypt($msg);
        echo '返回结果：';
        dump($msg);
    }
}