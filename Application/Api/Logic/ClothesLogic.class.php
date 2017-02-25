<?php

namespace Api\Logic;


class ClothesLogic extends BaseLogic{

    /**
     * @var \Api\Data\ClothesData
     */
    protected $clothesData;

    public function _initialize(){
        $this->clothesData = D('Clothes', 'Data');
    }

}