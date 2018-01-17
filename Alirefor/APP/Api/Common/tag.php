<?php
/**
 * User: chenyifan
 * Date: 2015/9/30
 * Time: 16:49
 */

/**
 * 作者：chenyifan
 * 功能：功能：检测tag是否已经存在
 * @param $value
 * @return bool
 */
function tag_exists($value) {

    $map = array(
        'value' => $value
    );

    return M('tag')->where($map)->getField('tag_id');
}

/**
 * 作者：chenyifan
 * 功能：检测用户是否已经添加tag
 * @param $user_id
 * @param $value
 * @return bool
 */
function user_tag_exists($user_id, $tag_id) {

    $map = array(
        'user_id' => intval($user_id),
        'tag_id'  => intval($tag_id)
    );

    return D('UserTag')->where($map)->count();
}