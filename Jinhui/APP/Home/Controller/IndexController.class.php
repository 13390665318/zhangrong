<?php
namespace Home\Controller;

class IndexController extends BaseController {
    public function index(){
        $image=M('article')->where('type=0')->select();
        $this->assign('image',$image);
        $info=M('article')->where('type=1')->select();
        $this->assign('info',$info);
        $news=M('article')->where('type=2')->select();
        $this->assign('news',$news);
        $this->display();
    }
  
    
}