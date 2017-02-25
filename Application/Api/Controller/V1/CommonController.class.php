<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class CommonController extends BaseController{
    public function index(){
        $data = $this->getAvailableData();

        var_dump($data);
    }
}