<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class FriendsController extends BaseController{

    /**
     * @var \Api\Logic\UserLogic
     */
    protected $userLogic;

    public function _initialize(){
        $this->userLogic = D('User', 'Logic');
    }


    /**
     * 获取粉丝或关注列表
     */
    public function getFans(){
        $this->validator->rule('required', 'condition');
        $this->validate('请输入condition');
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $data = $this->getAvailableData();
        $Friends = D('Friends');

        $fansInfo = $this->userLogic->getUserFans($data);

        $resultArray = array();
        foreach($fansInfo as $key => $val){
            $val['number'] = $Friends->where("friendID = %d AND userID != friendID",$val['userID'])->count();

            if($data['condition'] == 1){
                $val['fans'] = $Friends->where("userID = %d AND friendID = %d",array($val['userID'],$data['userID']))->count();
            }else{
                $val['fans'] = $Friends->where("userID = %d AND friendID = %d",array($data['userID'],$val['userID']))->count();
            }

            $resultArray[] = $val;
        }

        if(count($resultArray) > 0){
            $this->apiSuccess($resultArray,'获取粉丝列表信息成功');
        }else{
            $this->apiError('未找到相关信息');
        }
    }

    /**
     * 用户好友相关接口
     */
    public function index(){
        $data = $this->getAvailableData();

        if(isset($data['condition'])){
            switch($data['condition']){
                case 0:
                    $this->addFriends($data);
                    break;
                case 1:
                    $this->cancelFriends($data);
                    break;
                case 2:
                    $this->searchFriends($data);
                    break;
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 关注好友
     * @param $data
     */
    public function addFriends($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');

        $Friends = D('Friends');
        $Achiveprogress = D('Achiveprogress');

        $friendsInfo = $Friends->where("userID = %d AND friendID = %d",array($data['userID'],$data['friendID']))->find();

        if(!$friendsInfo){
            $friendId = $Friends->add(array('userID' => $data['userID'],'friendID' => $data['friendID']));

            if($friendId){
                $achiveInfo = $Achiveprogress->where('userID = %d',$data['userID'])->find();

                if($achiveInfo){
                    $achiveInfo['friendNum'] = $achiveInfo['friendNum'] + 1;
                    $achiveInfo['userFans'] = $achiveInfo['userFans'] + 1;

                    $Achiveprogress->where('userID = %d',$data['userID'])->save($achiveInfo);
                }
                // todo 推送关注消息

                $this->apiSuccess('关注好友成功');
            }else{
                $this->apiError('关注好友失败');
            }
        }else{
            $this->apiError('你已关注该好友');
        }
    }

    /**
     * 取消关注
     * @param $data
     */
    public function cancelFriends($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');

        $Friends = D('Friends');
        $Achiveprogress = D('Achiveprogress');
        $Saytexttable = D('Saytexttable');

        $friendsInfo = $Friends->where("userID = %d AND friendID = %d",array($data['userID'],$data['friendID']))->find();

        if($friendsInfo){
            $Friends->startTrans(); // 开启事务

            // 删除关注信息
            $flag1 = $Friends->where("userID = %d AND friendID = %d",array($data['userID'],$data['friendID']))->delete();
            $flag2 = $Saytexttable->where("userID = %d AND friendID = %d AND sayKind = 3",array($data['userID'],$data['friendID']))->delete();

            $achiveInfo = $Achiveprogress->where('userID = %d',$data['userID'])->find();

            $flag3 = true;
            if($achiveInfo){
                $achiveInfo['friendNum'] = $achiveInfo['friendNum'] - 1;
                $achiveInfo['userFans'] = $achiveInfo['userFans'] - 1;

                $flag3 = $Achiveprogress->where('userID = %d',$data['userID'])->save($achiveInfo);
            }
            // todo 推送关注消息
            if($flag1 !== false && $flag2 !== false && $flag3 !== false){
                $Friends->commit();
                $this->apiSuccess('取消关注成功');
            }else{
                $Friends->rollback();
                $this->apiSuccess('取消关注失败');
            }
        }else{
            $this->apiError('你未关注该好友');
        }
    }

    /**
     * 搜索好友
     * @param $data
     */
    public function searchFriends($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');

        $Friends = D('Friends');
        $UserInformation = D('Userinformation');

        $where['_string'] = "(userID like '%". $data['friendID'] ."%' OR userName like '%". $data['friendID'] ."%' AND userID != ". $data['userID'] .")";
        $userInfo = $UserInformation->field('userID,userName,userHead,userLV,userGlory,userSign')
            ->where($where)->order('userLV')->select();

        $resultArray = array();
        if(count($userInfo) > 0){
            foreach($userInfo as $key => $val){
                $val['number'] = $Friends->where("friendID = %d AND userID != friendID",$data['friendID'])->count();
                $val['fans'] = $Friends->where("userID = %d AND friendID= %d",array($data['userID'],$data['friendID']))->count();
                $val['attention'] = $Friends->where("userID = %d AND friendID= %d",array($data['friendID'],$data['userID']))->count();

                $resultArray[] = $val;
            }
            $this->apiSuccess($resultArray);
        }else{
            $this->apiError('未搜到任何结果');
        }
    }
}