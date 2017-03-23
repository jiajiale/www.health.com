<?php

namespace Api\Logic;


use Think\Exception;

class UserLogic extends BaseLogic{

    /**
     * @var \Api\Data\UserData
     */
    protected $userData;

    public function _initialize(){
        $this->userData = D('User', 'Data');
    }

    /**
     * 获取用户的信息
     * @param $conditions
     * @return mixed
     */
    public function getUserInfo($conditions){
        return $this->userData->getUserInfo($conditions);
    }

    /**
     * 获取用户之间的点赞信息
     * @param $conditions
     * @return mixed
     */
    public function getUserLikeInfo($conditions){
        return $this->userData->getUserLikeInfo($conditions);
    }

    /**
     * 获取用户的成就进度信息
     * @param $conditions
     * @return mixed
     */
    public function getUserAchieveInfo($conditions){
        return $this->userData->getUserAchieveInfo($conditions);
    }

    /**
     * 获取用户好友之间的排行
     * @param $conditions
     * @return mixed
     */
    public function getFriendsRanking($conditions){
        return $this->userData->getFriendsRanking($conditions);
    }

    /**
     * 获取用户在app中的排行
     * @param $conditions
     * @return mixed
     */
    public function getAppRanking($conditions){
        return $this->userData->getAppRanking($conditions);
    }

    /**
     * 获取完整的用户资料
     * @param $conditions
     * @return array
     */
    public function checkUserInfo($conditions){
        $UserInformation = D('Userinformation');
        $Achiveprogress = D('Achiveprogress');
        $Friends = D('Friends');

        // 获取用户的基本信息
        $userInfo = $UserInformation->field('userLV,userSign,userName,userHead,userAllMoney,userGlory,userZan,backImageView')
                            ->where("userID = %d",$conditions['friendID'])->find();

        // 获取用户的archive信息
        $archiveUserInfo = $Achiveprogress->field('achivePoint,accumulateSign,accumulateFoot,appearanceNum')
            ->where("userID = %d",$conditions['friendID'])->find();

        if($archiveUserInfo){
            $userInfo["userFoot"] = $archiveUserInfo["accumulateFoot"];
            $userInfo["achivePoint"] = $archiveUserInfo["achivePoint"];
            $userInfo["daySign"] = $archiveUserInfo["accumulateSign"];
            $userInfo["allClothes"] = $archiveUserInfo["appearanceNum"];
        }

        // 获取用户的关注信息(是否关注)
        $checkFans = $Friends->where("userID = %d AND friendID = %d",array($conditions['userID'],$conditions['friendID']))->find();
        $checkAttention = $Friends->where("userID = %d AND friendID = %d",array($conditions['friendID'],$conditions['userID']))->find();

        $isFans = count($checkFans) ? 1 : 0;
        $isAttention = count($checkAttention) ? 1 : 0;

        // 获取用户的关注信息(关注数量)
        $fans = $Friends->where("userID = %d AND friendID = %d",array($conditions['userID'],$conditions['friendID']))->count();
        $attentions = $Friends->where("userID = %d AND friendID = %d",array($conditions['friendID'],$conditions['userID']))->count();

        $userInfo['attention'] = $attentions;
        $userInfo['fans'] = $fans;

        return array(
            "info" => $userInfo,
            "fans" => $isFans,
            "attention" => $attentions,
            "number" => array("attention" => $attentions,"fans" => $fans)
        );
    }

    /**
     * 完善用户信息
     * @param $data
     * @return bool
     */
    public function saveUserInfo($data){
        $UserInformation = D('Userinformation');
        $UserClothes = D('Userclothes');
        $Achiveprogress = D('Achiveprogress');
        $Successtask = D('Successtask');
        $Daytask = D('Daytask');
        $Friends = D('Friends');

        $UserInformation->startTrans();

        try{
            // 1、插入用户信息表userinformation
            $flag1 = $UserInformation->add($data);

            // 2、插入用户衣服信息表userclothes
            if($data['userSex'] == 0){
                $clothes = array(
                    array('userID' => $data['userID'],'clothesID' => $data['clothesID'],'clothesParts' => 0),
                    array('userID' => $data['userID'],'clothesID' => 73,'clothesParts' => 1),
                    array('userID' => $data['userID'],'clothesID' => 15,'clothesParts' => 5),
                    array('userID' => $data['userID'],'clothesID' => 64,'clothesParts' => 6),
                );

                $flag2 = $UserClothes->addAll($clothes);
            }else{
                $clothes = array(
                    array('userID' => $data['userID'],'clothesID' => $data['clothesID'],'clothesParts' => 0),
                    array('userID' => $data['userID'],'clothesID' => 59,'clothesParts' => 1),
                    array('userID' => $data['userID'],'clothesID' => 61,'clothesParts' => 5),
                    array('userID' => $data['userID'],'clothesID' => 63,'clothesParts' => 6),
                );

                $flag2 = $UserClothes->addAll($clothes);
            }

            // 3、插入achiveprogress表信息
            $flag3 = $Achiveprogress->add(array('userID' => $data['userID']));

            // 4、插入successtask表信息
            $flag4 = $Successtask->addAll(array(
                array('userID' => $data['userID'],'taskID' => 0),
                array('userID' => $data['userID'],'taskID' => 1),
                array('userID' => $data['userID'],'taskID' => 2),
                array('userID' => $data['userID'],'taskID' => 3),
                array('userID' => $data['userID'],'taskID' => 4),
                array('userID' => $data['userID'],'taskID' => 5),
            ));

            // 5、插入daytask表信息
            $flag5 = $Daytask->add(array('userID' => $data['userID'],'dayRelease' => 0,'daycomment' => 0));

            // 6、插入Friends表信息
            $flag6 = $Friends->add(array('userID' => $data['userID'],'friendID' => $data['userID']));

            if($flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6){
                $UserInformation->commit();
                return true;
            }else{
                $UserInformation->rollback();
                return false;
            }
            
        }catch(Exception $e){
            $UserInformation->rollback();
            return false;
        }
    }
}