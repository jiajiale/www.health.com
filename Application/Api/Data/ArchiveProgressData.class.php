<?php

namespace Api\Data;

class ArchiveProgressData extends BaseData{

    //定义表前缀
    protected $tablePrefix = '';
    
    /**
     * 获取查询条件
     * @param $conditions
     * @return array
     */
    public function getCondition($conditions){
        $where = array();

        if (isset($conditions['userID']) && !empty($conditions['userID'])) {
            $where['progress.userID'] = array('EQ', $conditions['userID']);
        }

        if (isset($conditions['startTime']) && !empty($conditions['startTime'])) {
            $where['foot.time'] = array('EQ', $conditions['startTime']);
        }

        return $where;
    }


    /**
     * 获取用户的信息
     * @param $conditions
     * @return mixed
     */
    public function getUserArchiveInfo($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__ACHIVEPROGRESS__ as progress')
            ->field('progress.dayFoot,foot.foot')
            ->join('__FOOTINFORMATION__ as foot ON foot.userID = progress.userID')
            ->where($where)
            ->find();

        return $data;
    }

    /**
     * 读取每日任务
     * @param $conditions
     * @return mixed
     */
    public function getArchiveDayTask($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__ACHIVEPROGRESS__ as progress')
            ->field('progress.dayFoot,dataTask.dayRelease,dataTask.daycomment')
            ->join('__DAYTASK__ as dataTask ON dataTask.userID = progress.userID')
            ->where($where)
            ->find();

        return $data;
    }

    /**
     * 读取目前成就进度
     * @param $conditions
     * @return mixed
     */
    public function getArchiveProgress($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__ACHIVEPROGRESS__ as progress')
            ->field('progress.*,dataTask.dayRelease,dataTask.daycomment')
            ->join('__DAYTASK__ as dataTask ON dataTask.userID = progress.userID')
            ->where($where)
            ->find();

        return $data;
    }
}