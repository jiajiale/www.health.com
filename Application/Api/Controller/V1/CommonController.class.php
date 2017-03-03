<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class CommonController extends BaseController{
    public function index(){
        $data = $this->getAvailableData();

        var_dump($data);
    }

    /**
     * 检测版本信息
     */
    public function checkVersion(){
        $AppInfo = D('Appinfo');

        $versionInfo = $AppInfo->field('version,content')->order('version DESC')->find();

        if($versionInfo){
            $this->apiSuccess($versionInfo);
        }else{
            $this->apiError('未找到版本信息');
        }
    }
}