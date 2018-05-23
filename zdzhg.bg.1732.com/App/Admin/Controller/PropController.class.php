<?php
/**
 * Created by PhpStorm.
 * User: xiangxin
 * Date: 2017/10/24
 * Time: 9:46
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;


/**
 * 道具管理
 * @author
 */
class PropController extends CommonController
{
    /**
     * 道具列表
     *
     * */
    public function propList()
    {
        $menuid = I('get.menuid');
        $menu_db = D('Menu');
        $currentpos = $menu_db->currentPos($menuid);//栏目位置
        $type = $this->getItemType();
        unset($type['5']);
        unset($type['4']);
        unset($type['0']);
        unset($type['1']);
        $this->assign('itemtype', $type);
        $this->assign('title', $currentpos);
        $this->display('prop_list');
    }

    /**
     * 新道具添加页面
     * */
    public function propAdd()
    {
        $type = $this->getItemType();
        unset($type['5']);
        unset($type['4']);
        unset($type['0']);
        unset($type['1']);
        $this->assign('itemtype', $type);
        $this->display('prop_add');
    }

    /**
     * 新道具修改页面
     *
     * */
    public function propEdit()
    {
        $id = I('get.id', '0', 'intval');
        if (is_int($id) && $id > 0) {
            $type = $this->getItemType();
            unset($type['5']);
            unset($type['4']);
            unset($type['0']);
            unset($type['1']);
            $props = M('config_item', null);
            $prop = $props->where(array('id' => $id))->find();

            $itemtype = array();
            foreach ($type as $key => $val) {
                $itemtype[$key]['name'] = $val;
                if ($key == $prop['itemtype']) {
                    $itemtype[$key]['type'] = "selected = 'selected'";
                }
            }
            $this->assign('itemtype', $itemtype);

            $this->assign('item', $prop);
            $this->display('prop_edit');
        } else {
            echo "网络错误";
        }

    }

    /**
     * 道具修改提交
     * @return 返回值为int
     * @(int)2 道具修改成功
     * @(int)3 道具修改失败
     * @(int)4 道具id重复
     * */
    public function propEditPost()
    {
        if (IS_POST) {
            $data = I('post.');
            $userInfo = user_info();
            $data['operator'] = $userInfo['username'];
            $prop = M('config_item', null);
            if ($prop->where(array('id' => array('NEQ', $data['id']), 'itemid' => $data['itemid']))->count() > 0) {
                $this->ajaxReturn(4);
            }
            $return = $prop->save($data);
            if ($return != false) {
                $this->ajaxReturn(2);
            }
            $this->ajaxReturn(3);
        }
    }

    /**
     * 获取道具列表
     * */
    public function propListPost()
    {
        if (IS_POST) {
            $prop = M('config_item', null);
            $where['itemid'] = array('neq', -1);
            if (I('post.type')) {
                $where['itemtype'] = I('post.type');
            }
            $list = $prop->where($where)->order('id desc')->page(I('post.page') . ',' . I('post.rows'))->select();
            $type = $this->getItemType();
            $type['-1'] = L('prop_info_type_name');
            $sum = $prop->where($where)->count();
            foreach ($list as $key => $val) {
                if ($list[$key]['itemid'] == "-1") {
                    $list[$key]['itemname'] = L($list[$key]['itemname']);
                }

                $list[$key]['itemtype'] = $type[$list[$key]['itemtype']];
                $list[$key]['sumnum'] = $sum - ((I('post.page') - 1) * I('post.rows') + $key);
                $list[$key]['itemname'] = $val['itemname'] . '  &nbsp(ID:' . $val['itemid'] . ')';
            }
            $this->ajaxReturn(array('rows' => $list, 'total' => $sum));
        }
    }

    /**
     * 新道具添加
     * @return 返回值为int
     * @(int)2 道具添加成功
     * @(int)3 道具添加失败
     * @(int)4 道具id重复
     * */
    public function propAddPost()
    {
        if (IS_POST) {
            $data = I('post.');
            $userInfo = user_info();
            $data['operator'] = $userInfo['username'];
            $prop = M('config_item', null);
            if ($prop->where(array('itemid' => $data['itemid'], 'itemtype' => $data['itemtype']))->count() > 0) {
                $this->ajaxReturn(4);
            }
            $return = $prop->add($data);
            if ($return != false) {
                $this->ajaxReturn(2);
            }
            $this->ajaxReturn(3);
        }
    }

    /**
     * 道具删除
     * @(int)2 道具删除成功
     * @(int)3 道具删除失败
     * */
    public function propDeltePost()
    {
        $id = I('post.id', '0', 'intval');
        if (is_int($id) && $id > 0) {
            $props = M('config_item', null);
            if ($props->where(array('id' => $id))->count() == 1) {
                $return = $props->where(array('id' => $id))->delete();
                if ($return != false) {
                    $this->ajaxReturn(2);
                }
            }
        }
        $this->ajaxReturn(3);
    }

    /**
     * 表格导入
     * @(int)2 表格删除成功
     * @(int)3 表格删除失败
     * */
    public function excelinto()
    {
        ini_set('memory_limit','1024M');
        if (!empty($_FILES)) {
            $config = array(
                'exts' => array('xlsx','xls'),
                'maxSize' => 3145728000,
                'rootPath' =>"./Public/",
                'savePath' => 'Uploads/',
                'subName' => "excel",
            );
            $upload = new \Think\Upload($config);
            if (!$info = $upload->upload()) {
                $this->error($upload->getError());
            }
            vendor("PHPExcel.PHPExcel");
            //设置上传的excel名
            $file_name=$upload->rootPath.$info['photo']['savepath'].$info['photo']['savename'];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//判断导入表格后缀格式

            if ($extension == 'xlsx') {
                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
            } else if ($extension == 'xls'){
                $objReader =\PHPExcel_IOFactory::createReader('Excel5');
            }

            ;
            $objPHPExcel =$objReader->load($file_name, $encode='utf-8');

            $sheet =$objPHPExcel->getSheet(0);

            $highestRow = $sheet->getHighestRow();//取得总行数


            $highestColumn =$sheet->getHighestColumn(); //取得总列数

           //  M('config_item', null)->execute('truncate table pro_info');
            for ($i = 2; $i <= $highestRow; $i++) {
//看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置
                //$data['id'] =$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
                $data['itemid'] =$objPHPExcel->getActiveSheet()->getCell("A" .$i)->getValue();
                $data['itemname'] =$objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue();
                $data['itemtype'] =$objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue();
                $data['maxnum'] = $objPHPExcel->getActiveSheet()->getCell("D". $i)->getValue();
                $data['operator'] = $objPHPExcel->getActiveSheet()->getCell("E". $i)->getValue();
//看这里看这里,这个位置写数据库中的表名

                M('config_item', null)->add($data);
            }
            $this->success('导入成功!');
        } else {
            $this->error("请选择上传的文件");
        }
    }

}