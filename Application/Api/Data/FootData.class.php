<?php

namespace Api\Data;

class FootData extends BaseData{

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
            $where['user.userID'] = array('EQ', $conditions['userID']);
        }

        return $where;
    }

    /**
     * 获取用户的步数信息
     * @param $conditions
     * @return mixed
     */
    public function getUserFootInfo($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userMoney,user.userAllMoney,user.userEXP,progress.accumulateFoot')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID',"LEFT")
            ->where($where)
            ->find();

        return $data;
    }
}