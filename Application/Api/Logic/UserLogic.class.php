<?php

namespace Api\Logic;


class UserLogic extends BaseLogic{

    /**
     * @var \Api\Data\UserData
     */
    protected $userData;

    public function _initialize(){
        $this->userData = D('User', 'Data');
    }

    /**
     * 获取用户的信息
     * @param $conditions
     * @return mixed
     */
    public function getUserInfo($conditions){
        return $this->userData->getUserInfo($conditions);
    }

}