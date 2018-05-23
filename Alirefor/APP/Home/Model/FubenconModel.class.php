<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7 0007
 * Time: 上午 10:47
 */

namespace Home\Model;

use Think\Model;

class FubenconModel extends  Model{

    public  function fubenadd($data){

        $result=D('fubencon')->add($data);
        if($result==1){
            return true;
        }else{
            return false;
        }
    }

}
