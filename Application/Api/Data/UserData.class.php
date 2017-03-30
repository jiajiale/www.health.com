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
            //$where['user.userID'] = array('EQ', $conditions['userID']);
            $where['_string'] = '(user.userID = '. $conditions['userID'] .' OR user.userPhone = '. $conditions['userID'] .')';
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
            ->field('user.userID,user.userName,user.userHead,user.userImage,user.userImageSet,user.userGlory,user.userLV,user.userEXP,
            user.userMoney,user.userDiamond,user.userSex,user.backImageView AS backImage,progress.achivePoint')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID',"LEFT")
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

        if (isset($conditions['userID']) && !empty($conditions['userID']) && $conditions['condition'] == 3){
            $where['userzan.friendID'] = array('EQ', $conditions['userID']);
        }
        if (isset($conditions['userID']) && !empty($conditions['userID']) && $conditions['condition'] == 4){
            $where['userzan.userID'] = array('EQ', $conditions['userID']);
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
            ->field('user.userMoney, user.userAllMoney, user.userDiamond, user.userExp,progress.achivePoint')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where($where)
            ->find();

        return $data;
    }

    /**
     * 获取用户好友之间的排行
     * @param $conditions
     * @return array
     */
    public function getFriendsRanking($conditions){
        $meResult = $this->table('__USERINFORMATION__ AS user')
            ->field('progress.dayFoot,progress.userLV,progress.achivePoint,progress.appearanceNum,
            user.userName,user.userGlory,user.userHead,user.userImage,user.userImageSet,user.backImageView')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where("user.userID = %d",$conditions['userID'])
            ->find();

        $achiveArray = $this->table('__FRIENDS__ as friends')
            ->field('progress.achivePoint,user.userLV,user.userName,user.userGlory,user.userHead,user.userImage,user.userImageSet,user.backImageView')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = friends.friendID')
            ->join('__USERINFORMATION__ AS user ON friends.friendID = user.userID')
            ->where("friends.userID = %d AND friends.userID != friends.friendID",$conditions['userID'])
            ->order('progress.achivePoint DESC')
            ->limit(20)
            ->select();

        $clothesArray = $this->table('__FRIENDS__ as friends')
            ->field('progress.appearanceNum,user.userLV,user.userName,user.userGlory,user.userHead,user.userImage,user.userImageSet,user.backImageView')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = friends.friendID')
            ->join('__USERINFORMATION__ AS user ON friends.friendID = user.userID')
            ->where("friends.userID = %d AND friends.userID != friends.friendID",$conditions['userID'])
            ->order('progress.appearanceNum DESC')
            ->limit(20)
            ->select();

        $footArray = $this->table('__FRIENDS__ as friends')
            ->field('progress.dayFoot,user.userLV,user.userName,user.userGlory,user.userHead,user.userImage,user.userImageSet,user.backImageView')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = friends.friendID')
            ->join('__USERINFORMATION__ AS user ON friends.friendID = user.userID')
            ->where("friends.userID = %d AND friends.userID != friends.friendID",$conditions['userID'])
            ->order('progress.dayFoot DESC')
            ->limit(20)
            ->select();

        return array("meResult"=>$meResult,"achiveArray"=>$achiveArray,"clothesArray"=>$clothesArray,"footArray"=>$footArray);
    }

    /**
     * 获取用户在app中的排行
     * @param $conditions
     * @return mixed
     */
    public function getAppRanking($conditions){
        $Footinformation = D('Footinformation');

        $meResult["dayFoot"]= $Footinformation->where("userID = %d AND time = '%s'",array($conditions['userID'],$conditions['yesterDay']))->getField('foot');

        $meResult = $this->table('__USERINFORMATION__ AS user')
            ->field('progress.userLV,progress.achivePoint,progress.userFans,progress.appearanceNum,user.userName,user.userGlory,user.userHead,user.userImage,user.userImageSet,user.backImageView')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where("user.userID = %d",$conditions['userID'])
            ->find();

        $achiveArray = $this->table('__ACHIVEPOINRANKING__ AS rank')
            ->field('userName, userGlory, userLV, userHead, userImage, backImageView, achivePoinNum')
            ->order('rank.achivePoinNum DESC')
            ->select();

        $clothesArray = $this->table('__CLOTHSRANKING__ AS rank')
            ->field('userName, userGlory, userLV, userHead, backImageView, userImage, FansNum')
            ->order('rank.FansNum DESC')
            ->select();

        $footArray = $this->table('__FOOLTRANKING__ AS rank')
            ->field('userName, userGlory, userLV, userHead, userImage, backImageView, foolt')
            ->order('rank.foolt DESC')
            ->select();

        return array("meResult"=>$meResult,"achiveArray"=>$achiveArray,"clothesArray"=>$clothesArray,"footArray"=>$footArray);
    }

    /**
     * 获取用户的粉丝关注信息
     * @param $conditions
     * @return mixed
     */
    public function getUserFans($conditions){
        if($conditions['condition'] == 1){
            $data = $this->table('__USERINFORMATION__ AS user')
                ->field('user.userID,user.userName,user.userLV,user.userSign,user.userGlory,user.userHead')
                ->join('__FRIENDS__ as friends ON friends.friendID = user.userID')
                ->where("friends.userID = %d AND friends.userID != user.userID",$conditions['userID'])
                ->select();
        }else{
            $data = $this->table('__USERINFORMATION__ AS user')
                ->field('user.userID,user.userName,user.userLV,user.userSign,user.userGlory,user.userHead')
                ->join('__FRIENDS__ as friends ON friends.userID = user.userID')
                ->where("friends.friendID = %d AND friends.friendID != user.userID",$conditions['userID'])
                ->select();
        }


        return $data;
    }

    /**
     * 获取用户的金钱信息
     * @param $conditions
     * @return mixed
     */
    public function getUserMoney($conditions){
        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.userMoney, progress.spendMoney, progress.daySpendMoney')
            ->join('__ACHIVEPROGRESS__ as progress ON progress.userID = user.userID')
            ->where("user.userID = %d",$conditions['userID'])
            ->find();

        return $data;
    }

    /**
     * 获取用户的推送消息
     * @param $conditions
     * @return array
     */
    public function getUserMessage($conditions){
        $data = array();

        if($conditions['condition'] == 1){
            $data = $this->table('__USERINFORMATION__ AS user')
                ->field('user.userID,user.userName,user.userGlory,user.userHead,texttable.sayMessage,texttable.textTime,texttable.sayID,texttable.sayKind,texttable.number,saytable.sayImage')
                ->join('__SAYTEXTTABLE__ as texttable ON texttable.userID = user.userID')
                ->join('__SAYTABLE__ as saytable ON saytable.sayID = texttable.sayID')
                ->where("saytable.userID = %d AND user.userID != saytable.userID",$conditions['userID'])
                ->order("texttable.textTime DESC")
                ->limit(10)
                ->select();
        }elseif($conditions['condition'] == 2){
            $data = $this->table('__USERINFORMATION__ AS user')
                ->field('user.userID,user.userName,user.userGlory,user.userHead,texttable.sayMessage,texttable.textTime,texttable.sayID,texttable.sayKind,texttable.number,saytable.sayImage')
                ->join('__SAYTEXTTABLE__ as texttable ON texttable.userID = user.userID')
                ->join('__SAYTABLE__ as saytable ON saytable.sayID = texttable.sayID')
                ->where("saytable.userID = %d AND user.userID != saytable.userID AND texttable.number < %d",array($conditions['userID'],$conditions['lastNumber']))
                ->order("texttable.textTime DESC")
                ->limit(5)
                ->select();
        }
        return $data;
    }
}