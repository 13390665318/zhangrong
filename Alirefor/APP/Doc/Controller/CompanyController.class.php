<?php
namespace Doc\Controller;

use Think\Controller;
class CompanyController extends  Controller
{
    public function detial(){
        if(isset($_GET["company_id"])){
            $id=I("get.company_id");
            $list=D("company")->where("company_id=$id")->find();
            $this->assign("list",$list);
		$data=D("gudong")->where("company_id=$id")->select();
            $this->assign("data",$data);
            $this->display();
        }
    }
}

?>