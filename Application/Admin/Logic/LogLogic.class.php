<?php

namespace Admin\Logic;

class LogLogic extends BaseLogic{

    /**
     * @var \Admin\Data\LogData
     */
    protected $logData;

    public function _initialize(){
        $this->logData = D('Log', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->logData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->logData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveLog($data){

        $Log = D('Log');

        if($Log->create($data)){
            return $Log->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editLog($data){
        $Log = D('Log');

        if($Log->create($data)){
            return $Log->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delLog($id){
        $Log = D('Log');

        return $Log->delete($id);
    }
}