<?php

namespace Api\Logic;


class LikeLogic extends BaseLogic{

    /**
     * @var \Api\Data\LikeData
     */
    protected $likeData;

    public function _initialize(){
        $this->likeData = D('Like', 'Data');
    }

    /**
     * 用户点赞
     * @param $data
     * @return bool
     */
    public function saveUserLike($data){
        $UserZan = D('Userzan');
        $UserInformation = D('Userinformation');
        $ArchiveProgress = D('Achiveprogress');

        $UserZan->startTrans(); // 开启事务

        $flag1 = $UserZan->add(array(
            'userID' => $data['userID'],
            'friendID' => $data['friendID'],
            'zanDate' => date('Y-m-d',time())
        ));

        $flag2 = $UserInformation->where("userID = '%s'",$data['userID'])->setField('userZan',$data['zanNumber']);

        $flag3 = $ArchiveProgress->where("userID = '%s'",$data['userID'])->setInc('dayZan');

        if($flag1 && $flag2 !== false && $flag3 !== false){
            $UserZan->commit();  // 事务提交
            return true;
        }else{
            $UserZan->commit();  // 事务回滚
            return false;
        }
    }
}