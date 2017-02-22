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

    /**
     * 读取每日任务
     * @param $conditions
     * @return mixed
     */
    public function getArchiveDayTask($conditions){
        return $this->archiveProgressData->getArchiveDayTask($conditions);
    }

    /**
     * 读取目前成就进度
     * @param $conditions
     * @return mixed
     */
    public function getArchiveProgress($conditions){
        return $this->archiveProgressData->getArchiveProgress($conditions);
    }

    /**
     * 领取奖励
     * @param $data
     * @return mixed
     */
    public function saveSuccessArchive($data){
        $SuccessaAchive = D('Successachive');

        return $SuccessaAchive->add(array(
            'achiveID' => $data['achiveID'],
            'achiveFirst' => $data['first'],
            'achiveSecond' => $data['second'],
            'userID' => $data['userID'],
            'isGet' => 0,
        ));
    }
}