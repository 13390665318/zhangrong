<?php

/**
 * 作者：wanglidong
 * 功能：
 */
function  convert_absolute($relative_url = '') {
    if (!empty($relative_url)) {
        $relative_url = 'http://.' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/' . $relative_url;
    }

    return $relative_url;
}

function  convert_absolute_noroot($relative_url = '') {
    if (!empty($relative_url)) {
        $relative_url = 'http://' . $_SERVER['HTTP_HOST']   . $relative_url;
    }

    return $relative_url;
}