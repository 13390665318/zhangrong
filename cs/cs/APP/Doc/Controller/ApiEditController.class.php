<?php
/**
 * Created by PhpStorm.
 * User: 周文龙
 * Date: 2015/8/28
 * Time: 15:42
 */

namespace Doc\Controller;
use Think\Controller;
class ApiEditController extends Controller{
    public function lst(){
        $m = M('ApiType');
        $this->apitype = self::gettype();
        $this->display();
    }
    static private function gettype(){
        //接口类型+接口
        $m = M('ApiType');
        $apitype = $m->select();
        foreach($apitype as &$v){
            $m = M('api');
            $api = $m->where('type_id='.$v['id'])->select();
            $v['api'] = $api;
        }
        return $apitype;
    }
    public function edit(){
        $this->apitype = self::gettype();

        $type_id = I('get.type_id');
        $this->type_id = $type_id;
        $id = I('get.id');
        $this->id = $id;
        $m = M('Api');
        $this->api = $m->find($id);
        //判断是否是添加
        $isadd = I('get.isadd');
        $this->assign('isadd',$isadd);
        if(IS_POST) {
            $type_id = I('post.type_id');
            $id = I('post.id');
            $d = D('Api');
            $isadd = I('post.isadd');
            if($d->create()) {
                //有此参数为添加
                if($isadd){
                    if ($d->add()) {
                        $this->success('添加成功！',U('lst'));exit;
                    } else {
                        $this->error('添加失败！');
                    }
                }
                if ($d->where('id='.$id)->save()) {
                    $this->success('修改成功！',U('lst?type_id=' . $type_id));exit;
                } else {
                    $this->error('修改失败！');
                }
            }else{
                $this->error($d->getError());
            }
        }
        $this->display();
    }
    public function del(){
        $id = I('get.id');
        $type_id = I('get.type_id');
        $m = D('Api');
        if($m->delete($id)){
            $this->success('删除成功！',U('lst?type_id=' . $type_id));exit;
        } else {
            $this->error('删除成功！');
        }
    }
    public function typeedit(){
        $this->apitype = self::gettype();

        $this->isadd = I('get.isadd');
        $type_id = I('get.type_id');
        $this->type_id = $type_id;
        $m = M('ApiType');
        $this->api = $m->find($type_id);
        if(IS_POST){
            $isadd         = I('post.isadd');
            $type_id       = I('post.type_id');
            $data['name']  = I('post.name');
            $data['sort']  = I('post.sort');
            $d = D('ApiType');

            if($d->create($data)){
                if($isadd){
                    if ($d->add($data)) {
                        $this->success('添加成功！',U('lst'));exit;
                    } else {
                        $this->error('添加失败！');
                    }
                }else{
                    if ($d->where("id='$type_id'")->save($data)) {
                        $this->success('修改成功！',U('lst'));exit;
                    } else {
                        $this->error('修改失败！');
                    }
                }

            }else{
                $this->error($d->getError());
            }
        }
        $this->display();
    }
    public function typedel(){
        $type_id = I('get.type_id');
        $m = M("ApiType");
        if($m->delete($type_id)){
            $this->success('删除成功！',U('lst'));exit;
        } else {
            $this->error('删除成功！');
        }
    }
    public function ajax($id=0){
        //id 为type id
        $type_id = $id?$id:I('get.id');
        $m = M('Api');
        $api = $m->where("type_id='$type_id'")->select();
        echo json_encode($api);
    }
}