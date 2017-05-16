<?php

namespace Api\Data;

class ArticleData extends BaseData{

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
     * 获取空间动态
     * @param $conditions
     * @return mixed
     */
    public function getSayInfo($conditions){
        if($conditions['kind'] == 0){
            if($conditions['run'] == 0) {
                $data = $this->table('__SAYTABLE__ AS say')
                    ->field('say.sayID,say.userID,say.sayText,say.sayImage,say.sayTime,user.userName,user.userHead,user.userLV,user.userGlory')
                    ->join('__FRIENDS__ AS friends ON friends.friendID = say.userID',"LEFT")
                    ->join('__USERINFORMATION__ AS user ON user.userID = friends.friendID',"LEFT")
                    ->where("friends.userID = %d",$conditions['userID'])
                    ->order('say.sayTime DESC')
                    ->limit(5)
                    ->select();
            }else{
                $data = $this->table('__SAYTABLE__ AS say')
                    ->field('say.sayID,say.userID,say.sayText,say.sayImage,say.sayTime,user.userName,user.userHead,user.userLV,user.userGlory')
                    ->join('__FRIENDS__ AS friends ON friends.friendID = say.userID',"LEFT")
                    ->join('__USERINFORMATION__ AS user ON user.userID = friends.friendID',"LEFT")
                    ->where("friends.userID = %d AND say.sayID < %d",array($conditions['userID'],$conditions['sayID']))
                    ->order('say.sayTime DESC')
                    ->limit(5)
                    ->select();
            }

        }else{
            if($conditions['run'] == 0) {
                $data = $this->table('__SAYTABLE__ AS say')
                    ->field('say.sayID,say.userID,say.sayText,say.sayImage,say.sayTime,user.userName,user.userHead,user.userLV,user.userGlory')
                    ->join('__USERINFORMATION__ AS user ON user.userID = say.userID',"LEFT")
                    ->where("say.userID = %d",$conditions['userID'])
                    ->order('say.sayTime DESC')
                    ->limit(5)
                    ->select();

            }else{
                $data = $this->table('__SAYTABLE__ AS say')
                    ->field('say.sayID,say.userID,say.sayText,say.sayImage,say.sayTime,user.userName,user.userHead,user.userLV,user.userGlory')
                    ->join('__USERINFORMATION__ AS user ON user.userID = say.userID',"LEFT")
                    ->where("say.userID = %d AND say.sayID < %d",array($conditions['userID'],$conditions['sayID']))
                    ->order('say.sayTime DESC')
                    ->limit(5)
                    ->select();
            }

        }

        return $data;
    }

    /**
     * 获取用户动态
     * @param $conditions
     * @return mixed
     */
    public function getUserSayInfo($conditions){
        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userID,user.userName,sayText.sayMessage,sayText.textTime')
            ->join('__SAYTEXTTABLE__ AS sayText ON user.userID = sayText.userID',"LEFT")
            ->where("sayText.sayID = %d AND sayText.friendID = 0 AND sayKind = 0",array($conditions['idArr']))
            ->order('sayText.textTime DESC')
            ->limit(3)
            ->select();

        return $data;
    }

    /**
     * 获取动态用户信息
     * @param $conditions
     * @return mixed
     */
    public function getSayUser($conditions){
        $data = $this->table('__SAYTABLE__ AS sayTable')
            ->field('sayTable.sayID,sayTable.userID,sayTable.sayText,sayTable.sayImage,sayTable.sayTime,user.userName,user.userHead,user.userLV,user.userGlory,user.userID')
            ->join('__USERINFORMATION__ AS user ON user.userID = sayTable.userID',"LEFT")
            ->where("sayTable.sayID = %d",array($conditions['sayID']))
            ->find();

        return $data;
    }

    /**
     * 获取用户动态信息
     * @param $conditions
     * @return mixed
     */
    public function getUserSay($conditions){
        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userID,user.userName,user.userGlory,user.userLV,user.userHead,sayTable.sayMessage,sayTable.textTime,sayTable.friendID,sayTable.sayKind')
            ->join('__SAYTEXTTABLE__ AS sayTable ON user.userID = sayTable.userID',"LEFT")
            ->where("sayTable.sayID = %d AND sayTable.sayKind < 2",array($conditions['sayID']))
            ->order('sayTable.textTime DESC')
            ->select();

        return $data;
    }

    /**
     * 获取动态用户头像
     * @param $conditions
     * @return mixed
     */
    public function getSayUserImage($conditions){
        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userID,user.userHead')
            ->join('__SAYTEXTTABLE__ AS sayTable ON user.userID = sayTable.userID',"LEFT")
            ->where("sayTable.sayID = %d AND sayTable.sayKind = 2",array($conditions['sayID']))
            ->order('sayTable.textTime DESC')
            ->select();

        return $data;
    }

    /**
     * @param $conditions
     * @return mixed
     */
    public function getSayOk($conditions){
        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userHead')
            ->join('__SAYTEXTTABLE__ AS say')
            ->where("say.sayID = %d AND user.userID = %d AND say.sayKind = 2",array(array($conditions['sayID'],$conditions['userID'])))
            ->select();

        return $data;
    }
}