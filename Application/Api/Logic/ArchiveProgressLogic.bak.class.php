<?php

namespace Api\Logic;


class ArchiveProgressbakLogic extends BaseLogic{

    /**
     * @var \Api\Data\ArchiveProgressData
     */
    protected $userData;

    public function _initialize(){
        $this->userData = D('ArchiveProgress', 'Data');
    }

}