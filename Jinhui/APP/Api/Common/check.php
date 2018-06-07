<?php
/**
 * User: 汪利东
 * Date: 2015/10/23
 * Time: 18:02
 */
function check_params($key, $value) {
    $map[$key] = $value;
    switch ($key) {
        case 'user_id':
            $r = M('user')->where($map)->find();
            if (empty($r)) {
                echo_error(2003);
            }
            break;

        case 'request_id':
            $r = M('request')->where($map)->find();
            if (empty($r)) {
                echo_error(3001);
            }
            break;

        case 'order_id':
            $r = M('order_info')->where($map)->find();
            if (empty($r)) {
                echo_error(4005);
            }
            break;
    }
}