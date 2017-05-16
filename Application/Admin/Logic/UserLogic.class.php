<?php

namespace Admin\Logic;

use Admin\Enum\BaseEnum;
use Think\Exception;

class UserLogic extends BaseLogic{

    /**
     * @var \Admin\Data\UserData
     */
    protected $userData;

    public function _initialize(){
        $this->userData = D('User', 'Data');
    }

    /**
     * 获取单条数据
     * @param $userID
     * @return mixed
     * @internal param $id
     */
    public function getById($userID){
        return $this->userData->getById($userID);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->userData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveUser($data){

        $User = D('Userinformation');

        if($User->create($data)){
            return $User->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editUser($data,$userInfo){
        $User = D('Userinformation');

        $userInfo['userID'] = $data['userID'];
        $userInfo['userPassword'] = $data['userPassword'];
        $userInfo['userName'] = $data['userName'];
        $userInfo['userPhone'] = $data['userPhone'];
        $userInfo['userMoney'] = $data['userMoney'];
        $userInfo['userDiamond'] = $data['userDiamond'];

        $result = $User->where("userID = '%s'",$data['id'])->save($userInfo);

        return $result;
    }

    /**
     * 删除数据
     * @param $userID
     * @return mixed
     */
    public function delUser($userID){
        $User = D('Userinformation');

        return $User->where("userID = '%s'",$userID)->setField('status',BaseEnum::DELETE);
        //$UserClothes = D('Userclothes');
        //$Achiveprogress = D('Achiveprogress');
        //$Daytask = D('Daytask');
        //$Friends = D('Friends');
        //$Footinformation = D('Footinformation');
        //$Successtask = D('Successtask');
        //$Successachive = D('Successachive');
        //$Saytable = D('Saytable');
        //$Saytexttable = D('Saytexttable');
        //$Userzan = D('Userzan');
        //$Userforbidden = D('Userforbidden');
        //
        //
        //$User->startTrans(); // 开启事物
        //
        //try{
        //    $flag1 = $UserClothes->where("userID = '%s'",$userID)->delete();
        //    $flag2 = $Achiveprogress->where("userID = '%s'",$userID)->delete();
        //    $flag3 = $Successtask->where("userID = '%s'",$userID)->delete();
        //    $flag4 = $Daytask->where("userID = '%s'",$userID)->delete();
        //    $flag5 = $Friends->where("userID = '%s' AND friendID = '%s'",array($userID,$userID))->delete();
        //    $flag6 = $Footinformation->where("userID = '%s'",$userID)->delete();
        //    $flag7 = $Successachive->where("userID = '%s'",$userID)->delete();
        //    $flag8 = $Saytable->where("userID = '%s'",$userID)->delete();
        //    $flag9 = $Saytexttable->where("userID = '%s'",$userID)->delete();
        //    $flag10 = $Userzan->where("userID = '%s'",$userID)->delete();
        //    $flag11 = $Userforbidden->where("userID = '%s'",$userID)->delete();
        //    $flag12 = $User->where("userID = '%s'",$userID)->delete();
        //
        //    if($flag1 !== false &&
        //       $flag2 !== false &&
        //       $flag3 !== false &&
        //       $flag4 !== false &&
        //       $flag5 !== false &&
        //       $flag6 !== false &&
        //       $flag7 !== false &&
        //       $flag8 !== false &&
        //       $flag9 !== false &&
        //       $flag10 !== false &&
        //       $flag11 !== false &&
        //       $flag12 !== false){
        //        $User->commit();
        //        return true;
        //    }else{
        //        $User->rollback();
        //        return false;
        //    }
        //}catch (Exception $e){
        //    $User->rollback();
        //    return false;
        //}

    }
}