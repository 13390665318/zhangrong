<?php
/**
 * User: chenyifan
 * Date: 2015/10/9
 * Time: 11:49
 */

/**
 * 作者：chenyifan
 * 功能：评分格式化
 * @param $grade
 */
function grade_format($grade) {

    return number_format(floatval($grade), 2, '.', '');
}

/**
 * 作者：chenyifan
 * 功能：更新评分
 * @param $receive_id
 * @param $type
 */
function update_grade($receive_id, $type) {

    $map = array(
        'receive_id' => $receive_id,
        'type'       => $type
    );

    $count = D('Appraise')->where($map)->count();
    $grade = D('Appraise')->where($map)->sum('grade');

    $average_grade = $count == 0 ? '5.00' : grade_format($grade / $count);

    $map = array(
        'user_id' => $receive_id
    );

    M('user')->where($map)->setField($type . '_grade', $average_grade);
}

/**
 * 作者：chenyifan
 * 功能：好评率
 * @param $grade
 */
function grade_to_percent($grade) {

}