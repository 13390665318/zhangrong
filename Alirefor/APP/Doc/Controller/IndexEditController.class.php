<?php
namespace  Doc\Controller;

//use Think\Controller;



class IndexEditController extends BaseController{
    /**
     * 接口文档
     */
    public function index(){
        header('Content-Type:text/html;charset=utf-8');
        $m = M('api_setting');
        
        $arr= $m->find();
       
        $apiSetting['sitename']=$arr["txt"];
       
       $this->apiSetting = $apiSetting;
        $this->assign('apitype',self::gettype());
        $this->display();
    }

    public function lst(){
        //title
        $m = M('api_setting');
        $arr= $m->find();
        $apiSetting['sitename']=$arr["txt"];
        $this->apiSetting = $apiSetting;
        //左边数据
        $this->assign('apitype',self::gettype());
        //main
        $api_id = I('get.api_id');
        $this->api_id = I('api_id');
        $m = M('Api');
        $api = $m->where('id='.$api_id)->find();
        //上传参数
        $m = M('ApiPost');
        $post = $m->where('api_id='.$api_id)->select();
        foreach($post as &$v){
            $v['remark']=htmlspecialchars_decode($v['remark']);
            if($v['isshow']==1){
                $v['isshow']='Y';
            }else{
                $v['isshow']='N';
            }
        }
        $post = self::tree($post);
        $api['post'] = $post;
        //返回说明
        $m = M('ApiResult');
        $result = $m->where('api_id='.$api_id)->select();
        foreach($result as &$v){
            $v['txt']=htmlspecialchars_decode($v['txt']);
            if($v['isshow']==1){
                $v['isshow']='Y';
            }else{
                $v['isshow']='N';

            }
        }
        $api['result'] = $result;
        $this->api = $api;
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

    //获取接口类型+接口
    static private function gettype(){
        $m = M('ApiType');
        $apitype = $m->select();
        foreach($apitype as &$v){
            $m = M('api');
            $api = $m->where('type_id='.$v['id'])->select();
            $v['api'] = $api;
        }
        return $apitype;
    }

    //修改 & 添加
    public function edit(){
        //title
        $m = M('ApiSetting');
        $arr= $m->find();
        $apiSetting['sitename']=$arr["txt"];
        $this->apiSetting = $apiSetting;
        //左边列表
        $this->assign('apitype',self::gettype());
        //判断是否是添加
        $isadd = I('get.isadd');
        $this->assign('isadd',$isadd);
        //$id 为2个数据库的id
        $id = I('get.id');
        $this->assign('id',$id);
        $edit = I('get.edit');
        $this->assign('edit',$edit);
        $api_id = I('get.api_id');
        $this->assign('api_id',$api_id);
        //可以做父ID的字段
        $m = M('ApiPost');
        $this->pname = $m->field('id,name,parent_id')->where("type!='string' AND api_id='$api_id'")->select();
        //判断使用哪个修改页面
        if($edit==1) {
            $m = M('ApiPost');
            $post = $m->find($id);
            //把isshow的1,0替换成Y,N
            self::isshow($post);
            $this->post = $post;
            $this->display('edit1');
        }else if($edit==2){
            $m = M('ApiResult');
            $data = $m->find($id);
            //把isshow的1,0替换成Y,N
            self::isshow($data);
            $this->data = $data;
            $this->display('edit2');
        }
        //处理提交数据
        if(IS_POST){
            $id = I('post.id');
            $edit = I('post.edit');
            $api_id = I('post.api_id');
            $isadd = I('post.isadd');
            //$remark = I('post.remark');
            //$remark = str_replace("\r\n","<br/>",$remark);
            //var_dump($str);exit;
            //判断修改哪个数据库
            switch($edit){
                case 1:
                    $d = D('ApiPost');
                    //$data = $_POST;
                    //$data['remark'] = $remark;
                    break;
                case 2:
                    $d = D('ApiResult');
                    //$data = $_POST;
                    //$data['txt'] = $remark;
                    break;
            }
            if($d->create()){
                //有此参数为添加，否则为修改
                if($isadd){
                    if($d->add()){
                        $this->success('添加成功！',U('lst?api_id='.$api_id));exit;
                    }else{
                        $this->error('添加失败！');
                    }
                }
                if($d->where('id='.$id)->save()){
                    $this->success('修改成功！',U('lst?api_id='.$api_id));exit;
                }else{
                    $this->error('修改失败！');
                }
            }else{
                $this->error($d->geterror());
            }
        }
    }

    //把isshow的1,0替换成Y,N
    static private function isshow($data){
        if($data){
            if($data['isshow']==1){
                $data['isshow']='Y';
            }else{
                $data['isshow']='N';
            }
        }
    }

    //异步修改notise
    public function ajax(){
        $where['id'] = I('post.api_id');
        $str = I('post.notice');
        $data['notice'] = str_replace("\r","<br/>",$str);
        $m = M('api');
        if($m->where($where)->save($data)){
            echo '修改成功';
        }else{
            echo '修改失败';
        }
    }

    public function del(){
        $edit = I('get.edit');
        $api_id = I('get.api_id');
        $id = I('get.id');
        switch($edit){
            case 1:
                $d = M('ApiPost');
                break;
            case 2:
                $d = M('ApiResult');
                break;
        }
        if($d->delete($id)){
            $this->success('删除成功！',U('lst?api_id='.$api_id));
        }else{
            $this->error('删除成功！');
        }
    }

}