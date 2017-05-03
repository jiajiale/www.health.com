<?php

namespace Admin\Logic;

class UserLogic extends BaseLogic{

    /**
     * @var \Admin\Data\UserData
     */
    protected $userData;

    public function _initialize(){
        $this->userData = D('User', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->userData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->userData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveUser($data){

        $User = D('Userinformation');

        if($User->create($data)){
            return $User->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editUser($data){
        $User = D('Userinformation');

        if($User->create($data)){
            return $User->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delUser($id){
        $User = D('Userinformation');

        return $User->delete($id);
    }
}