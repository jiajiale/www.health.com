<?php
namespace Admin\Controller;


use Admin\Enum\BaseEnum;
use Admin\Enum\FileTypeEnum;
use Common\Util\CacheLock;
use Think\Image;
use Think\Upload;

class ImageController extends BaseController {

    /**
     * 上传图片
     */
    public function uploadPicture(){

        $upload = new Upload();         // 实例化上传类
        $upload->maxSize   =     3145728 ;      // 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');    // 设置附件上传类型
        $upload->rootPath  =     './Public/Api/';   // 设置附件上传根目录
        $upload->savePath  =     'marketImage/';     // 设置附件上传（子）目录
        $upload->subName   =     '';  // 设置上传保存子目录
        $upload->saveName  =     'uniqid';

        // 缩略图保存路径

        // 上传文件(加锁)
        if(isset($_FILES['file1'])){
            unset($_FILES['file1']);
        }
        $info   =   $upload->upload();
        if(!$info) {
            // 上传错误提示错误信息
            $this->ajaxError($upload->getError());
        }else{
            // 上传成功

            $result = array();
            foreach($info as $file){

                // 原文件保存路径

                if($upload->subName){
                    array_push($result,date('Ymd') . '/' . $file['savename']);
                }else {
                    array_push($result,$file['savename']);
                }

            }

            $this->ajaxSuccess(implode(',',$result),'上传成功！');
        }
    }


}