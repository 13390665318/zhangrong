<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/10/25
 * Time: 9:13
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;


/**
 *标识符查询
 * @author      xiangxing
 */
class IdentifierController extends CommonController
{

    /**
     * 标识符查询页面
     *
     * */
    public function identifierQuery(){
        $menuid     = I('get.menuid');
        $menu_db    = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $this->assign('title', $currentpos);
        $this->display('identifier_query');
    }


    /**
     *excel转成数据
     *
     * */
    public function excelTurnData(){

    }

}