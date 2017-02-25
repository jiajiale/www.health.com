<?php

namespace Api\Data;

class UserData extends BaseData{

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
     * 获取用户的信息
     * @param $conditions
     * @return mixed
     */
    public function getUserInfo($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userID,user.userName,user.userHead,user.userImage,user.userGlory,user.userLV,user.userEXP,
            user.userMoney,user.userDiamond,user.userSex,user.backImageView AS backImage,progress.achivePoint')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where($where)
            ->find();

        return $data;
    }

    /**
     * 获取用户直接的点赞信息
     * @param $conditions
     * @return mixed
     */
    public function getUserLikeInfo($conditions){
        $where = array();

        if (isset($conditions['userID']) && !empty($conditions['userID'])) {
            $where['userzan.friendID'] = array('EQ', $conditions['userID']);
        }

        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userID,user.userName,user.userLV,user.userSign,user.userGlory,user.userHead,userzan.zanDate')
            ->join('__USERZAN__ as userzan ON userzan.userID = user.userID')
            ->where($where)
            ->order('userzan.zanDate DESC')
            ->select();

        return $data;
    }

    /**
     * 获取用户的成就进度信息
     * @param $conditions
     * @return mixed
     */
    public function getUserAchieveInfo($conditions){
        $where = $this->getCondition($conditions);

        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('a.userMoney, a.userAllMoney, a.userDiamond, a.userExp,b.achivePoint')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where($where)
            ->find();

        return $data;
    }
}