<?php
/**
 * User: 汪利东
 * Date: 2015/9/28
 * Time: 18:09
 */

namespace Api\Controller;


use Think\Controller;

class PublicController extends Controller {


    protected $define_file_type = array(
        'log',
        'supply',   //供应图片
        'request',  //需求图片
        'bid',
        'photo',    //头像
        'apply', //身份认证
    );

    /**
     * 作者：wanglidong
     * 功能：同步时间
     */
    public function getTimestamp() {
        $time = time();
        echo_success(array('timestamp' => "$time"));
    }

    /**
     * 作者：chenyifan
     * 功能：上传文件
     * 修改：wanglidong 2015-10-08 无上传文件的处理
     */
    public function upload($file_type = 'other', $user_id = '', $is_need = true) {
        //无上传文件
        if (!$is_need) {
            if (count($_FILES) == 0) {
                return array();
            }
        }

        if (!in_array($file_type, $this->define_file_type)) {
            $file_type = 'other';
        }

        $config = array(
            'maxSize'  => 314572800,
            'rootPath' => './Uploads/',
            'savePath' => $file_type . '/' . ($user_id ? $user_id . '/' : ''),
            'saveName' => array('uniqid', ''),
            //'exts'     => array('jpg', 'gif', 'png', 'jpeg', 'log','webp'),
            'subName'  => '',
        );

        $upload = new \Think\Upload($config);

        $info = $upload->upload();
        if (!$info) {
            //echo_error(9002, $upload->getError());
		$files=null;
		return $files;
        } else {

            $files = array();
            $image = new \Think\Image();
            foreach ($info as $file) {
                //清零
                $width  = 0;
                $height = 0;

                $path =  './Uploads/' . $file['savepath'] . $file['savename'];
         /**       if (in_array($file['ext'], array('jpg', 'gif', 'png', 'jpeg', 'webp'))) {
                    $image->open($path);
                    $width  = $image->width();
                    $height = $image->height();
                    if ($width > 3000 || $height > 3000) {
                        $name = md5_file($path);
                        $path = UPLOAD_PATH . $file['savepath'] . $name;
                        $image->thumb(3000, 3000, \Think\Image::IMAGE_THUMB_SCALE)->save($path);
                        $width  = $image->width();
                        $height = $image->height();
                    }
                }
**/
                $files[] = array(
                    'path'   => ltrim($path, './'),
                    'width'  => $width ? $width : 0,
                    'height' => $height ? $height : 0,
                    'size'   => $file['size'],
                    'name'   => $file['name']
                );
            }

            return $files;
        }
    }
}