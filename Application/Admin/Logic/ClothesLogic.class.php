<?php

namespace Admin\Logic;

use Admin\Enum\BaseEnum;

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
     * @param $clothesID
     * @return mixed
     */
    public function getById($clothesID){
        return $this->clothesData->getById($clothesID);
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

        $result = $Clothes->query("SELECT max(cast(clothesID AS UNSIGNED)) AS clothesID FROM clothesinformation;");
        $data['clothesID'] = $result[0]['clothesID'] + 1;

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
            return $Clothes->where("clothesID = %d",$data['clothesID'])->save($data);
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $clothesID
     * @return mixed
     */
    public function delClothes($clothesID){
        $Clothes = D('Clothesinformation');

        return $Clothes->where("clothesID = '%s'",$clothesID)->setField('status',BaseEnum::DELETE);
    }
}