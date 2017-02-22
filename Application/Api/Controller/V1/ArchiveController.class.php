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

                    break;
                case 5:

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
            $this->apiSuccess(null,'领取奖励失败');
        }else{
            $this->apiError('领取奖励成功');
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

        $successTask = $SuccessTask->where("userID = '%s'")->select();

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