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

            // 2、插入用户衣服信息表serclothes
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
                    array('userID' => $data['userID'],'clothesID' => 64,'clothesParts' => 5),
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