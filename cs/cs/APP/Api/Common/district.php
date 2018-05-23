<?php
/**
 * User: chenyifan
 * Date: 2015/9/29
 * Time: 15:07
 */

function district_children($pid) {

    $map = array(
        'pid'   => $pid,
        'level' => array('NEQ', 4)
    );

    $districts = M('district')->where($map)->field('district_id,name,pid')->select();
    foreach ($districts as $key => $child) {

        $map = array(
            'pid'   => $child['district_id'],
            'level' => array('NEQ', 4)
        );

        $children = M('district')->where($map)->field('district_id,name,pid')->select();
        if (!empty($children)) {
            foreach ($children as $k => $item) {
                $children[$k]['children'] = array();
            }
        }
        $districts[$key]['children'] = $children;
    }

    return $districts;
}