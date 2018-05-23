<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31 0031
 * Time: 上午 11:47
 */

namespace Home\Controller;


class OiladdController extends BaseController
{
    public function index(){
       $oil=I('post.');

       if($oil['jine']==""){
           $oil['jine']=round($oil['danci']*$oil['danjia']);
       }elseif($oil['danci']==""){
           $oil['danci']=round($oil['jine']/$oil['danjia'],2);
       }


       $model=D('oil');
       $add=$model->data($oil)->add();

        if ($add !=false) {
            $this->success("添加成功", U("Oil/index"));
        } else {
            $this->error("添加失败", U("Oil/index"));
        }

       }
       public function add(){

           if(IS_POST){
               $id=I('post.id');


               $data['licheng']=I('post.licheng');
               $data['beizhu']=I('post.beizhu');

               $result=D('oil')->where("id='$id'")->save($data);
               if($result==1){
                   $this->success("添加成功", U("Index/index"));
               }else {
                   $this->error("添加失败", U("Oiladd/add"));
               }
           }else{
               $km=I('id');
               $model=D('oil');
               $kilo=$model->where("id='$km'")->find();
               $this->assign('kilo',$kilo);
               $this->display();
           }

       }

}