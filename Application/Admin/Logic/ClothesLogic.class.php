<?php

namespace Admin\Logic;

class ClothesLogic extends BaseLogic{

    /**
     * @var \Admin\Data\ClothesData
     */
    protected $clothesData;

    public function _initialize(){
        $this->clothesData = D('Clothes', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->clothesData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->clothesData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveClothes($data){

        $Clothes = D('Clothesinformation');

        if($Clothes->create($data)){
            return $Clothes->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editClothes($data){
        $Clothes = D('Clothesinformation');

        if($Clothes->create($data)){
            return $Clothes->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delClothes($id){
        $Clothes = D('Clothesinformation');

        return $Clothes->delete($id);
    }
}