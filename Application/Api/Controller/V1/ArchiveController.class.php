<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class ArchiveController extends BaseController{

    /**
     * @var \Api\Logic\ArchiveProgressLogic
     */
    protected $archiveProgressLogic;

    public function _initialize(){
        $this->archiveProgressLogic = D('ArchiveProgress', 'Logic');
    }

    /**
     * 获取成就等接口
     */
    public function getArchive(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->getAchievements($data);
                    break;
                case 2:
                    $this->getAchievementsProgress($data);
                    break;
                default:
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 获取成就
     * @param $data
     */
    public function getAchievements($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $SuccessAchive = D('Successachive');

        $arrayInfor = array();
        for($i=0; $i < 4; $i++) {

            $arraytwo = array();
            $indextwo = 0;
            for ($j=0; $j < 6; $j++) {

                $result = $SuccessAchive->where("userID = %d AND achiveFirst = %d AND achiveSecond = %d",array($data['userID'],$i,$j))->order('achiveID')->select();

                $arraytwo[$indextwo] = $result;
                $indextwo ++;

                if($i == 2 && $j == 4){
                    break;
                }
                if($i == 3 && $j == 1){
                    break;
                }
                if($i == 0 && $j == 0){
                    break;
                }

            }

            $arrayInfor[$i] = $arraytwo;
        }

        $this->apiSuccess(array('info' => $arrayInfor));
    }

    /**
     * 读取目前成就进度
     * @param $data
     */
    public function getAchievementsProgress($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $result = $this->archiveProgressLogic->getArchiveProgress($data);

        if($result){
            $info = array(
                array($result['appearanceNum']),
                array($result['accumulateFoot'],$result['userLV'],$result['spendMoney'],$result['accumulateSign'],$result['dayTaskNum'],$result['achivePoint']),
                array($result['friendNum'],$result['accumulateZanNum'],$result['dayZan'],$result['Release'],$result['comment'],$result['dayRelease'],$result['daycomment']),
                array($result['dayFoot'],$result['daySpendMoney'])
            );

            $this->apiSuccess(array("info" => $info));
        }else{
            $this->apiError('没有找到数据');
        }
    }

    /**
     * 领取奖励等接口
     */
    public function updateArchive(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->getAward($data);
                    break;
                case 2:

                    break;
                case 3:

                    break;
                case 4:
                    $this->getAwardFoot($data);
                    break;
                case 5:
                    $this->getSignAward($data);
                    break;
                case 6:
                    $this->getDailyTask($data);
                    break;
                default:
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 领取奖励
     * @param $data
     */
    public function getAward($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'achiveID');
        $this->validate('请输入achiveID');
        $this->validator->rule('required', 'first');
        $this->validate('请输入用户名');
        $this->validator->rule('required', 'second');
        $this->validate('请输入用户个性签名');

        $result = $this->archiveProgressLogic->saveSuccessArchive($data);

        if($result){
            $this->apiSuccess(null,'领取奖励成功');
        }else{
            $this->apiError('领取奖励失败');
        }
    }

    /**
     * 获取点赞和步数
     * @param $data
     */
    public function getAwardFoot($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $AchiveProgress = D('Achiveprogress');

        $data = $AchiveProgress->field('dayZan,dayFoot')->where("userID = %d",$data['userID'])->find();

        if($data){
            $this->apiSuccess(array("info" => array($data['dayZan'],$data['dayFoot'])));
        }else{
           $this->apiError('没有找到相关信息');
        }
    }

    /**
     * 领取签到奖励
     * @param $data
     */
    public function getSignAward($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'taskID');
        $this->validate('请输入taskID');

        $SuccessTask = D('Successtask');
        $AchiveProgress = D('Achiveprogress');

        $selectZan = $AchiveProgress->where("userID = %d",$data['userID'])->select();

        $flag1 = false;
        if($selectZan){
            if($data['taskID'] == 0){
                $flag1 = $AchiveProgress->where("userID = %d",$data['userID'])->setField(array(
                    "accumulateSign" => $selectZan['accumulateSign'] + 1,
                    "dayTaskNum" => $selectZan['dayTaskNum'] + 1
                ));
            }else{
                $flag1 = $AchiveProgress->where("userID = %d",$data['userID'])->setInc('dayTaskNum');
            }
        }

        $flag2 = $SuccessTask->where("userID = %d AND taskID = %d",array($data['userID'],$data['taskID']))->setField('getReward',1);

        if($flag1 !== false && $flag2 !== false){
            $this->apiSuccess(null,'领取签到奖励成功');
        }else{
            $this->apiError('领取签到奖励失败');
        }

    }

    /**
     * 读取每日任务
     * @param $data
     */
    public function getDailyTask($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $SuccessTask = D('Successtask');

        $successTask = $SuccessTask->where("userID = '%s'",$data['userID'])->select();

        if($successTask){
            $result = array();

            for($i = 0; $i < count($successTask); $i++){
                $idArray["isGet"] = $successTask[$i]["getReward"];
                $idArray["taskID"] = $successTask[$i]["taskID"];
                $result[$i] = $idArray;
            }

            $archiveDayTask = $this->archiveProgressLogic->getArchiveDayTask($data);

            if($archiveDayTask){
                $newFoot = $archiveDayTask["dayFoot"];
                $newRelease = $archiveDayTask["dayRelease"];
                $newcomment = $archiveDayTask["daycomment"];
            }else{
                $newFoot = $newRelease = $newcomment = 0;
            }

            $this->apiSuccess(array("info" => $result,"newFoot" => $newFoot,"newRelease" => $newRelease,"newcomment" => $newcomment));
        }else{
            $this->apiError('任务不存在');
        }
    }
}