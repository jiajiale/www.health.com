<?php

namespace Api\Logic;


class ArchiveProgressLogic extends BaseLogic{

    /**
     * @var \Api\Data\ArchiveProgressData
     */
    protected $archiveProgressData;

    public function _initialize(){
        $this->archiveProgressData = D('ArchiveProgress', 'Data');
    }

    /**
     * 获取用户的档案信息
     * @param $conditions
     * @return mixed
     */
    public function getUserArchiveInfo($conditions){
        return $this->archiveProgressData->getUserArchiveInfo($conditions);
    }
}