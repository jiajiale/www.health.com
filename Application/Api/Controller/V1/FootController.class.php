<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class FootController extends BaseController{

    /**
     * 步数相关接口
     */
    public function step(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->getLastTime($data);
                    break;
                case 2:
                    $this->uploadStep($data);
                    break;
                case 3:
                    $this->recordStep($data);
                    break;
                default:
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 获取上次登录时间和当前时间
     * @param $data
     */
    public function getLastTime($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');

        $UserInformation = D('Userinformation');

        $result = $UserInformation->field('UUID,lastTime,day')->where("userID = '%s'",$data['userID'])->find();

        if($result){
            $result['nowTime'] = date("Y-m-d",time());

            $this->apiSuccess(array('info' => $result));
        }else{
            $this->apiError('用户不存在');
        }
    }

    /**
     * 上传步数
     * @param $data
     */
    public function uploadStep($data){

    }

    /**
     * 记步
     * @param $data
     */
    public function recordStep($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'day');
        $this->validate('请输入天数');

        $FootInformation = D('Footinformation');

        $startTime = '';
        $day = $data["day"];
        switch ($day) {
            case 7:     // 7天
                $startTime = date('Y-m-d',strtotime("-6 day"));
                break;
            case 30:    // 30天
                $startTime = date('Y-m-d',strtotime("-29 day"));
                break;
            case 3:     // 3个月
                $startTime = date('Y-m',strtotime("-2 month"));
                break;
            default:
                break;
        }

        $timeArray = $resultArray = array();

        if($day == 3){
            // 3个月的记录
            for($i = 0; $i < $day; $i++){
                $curTime = date('Y-m',strtotime("$startTime + $i month"));
                $timeArray[$i] = $curTime;
            }

            for($i = 0; $i < $day; $i++){
                $search = $timeArray[$i];
                $datetime = explode(" ",$search);
                $date = explode("-",$datetime[0]);
                $year = $date[0];
                $month = $date[1];

                // 统计步数
                $steps = $FootInformation->where("userID = '%s' AND year = %d AND month = %d",array($data['userID'],$year,$month))->sum('foot');

                $resultArray[$i] = $steps;
            }
        }else{
            for($i = 0; $i < $day; $i++){
                $curTime = date('Y-m-d',strtotime("$startTime + $i day"));
                $timeArray[$i] = $curTime;
            }

            for($i = 0; $i < $day; $i++){
                // 统计步数
                $steps = $FootInformation->where("time = '%s'",$timeArray[$i])->sum('foot');
                $resultArray[$i] = $steps;
            }
        }

        if(count($resultArray)){
            $this->apiSuccess(array("result"=>$resultArray,"dayTime"=>$timeArray));
        }else{
            $this->apiError('查询步数失败');
        }
    }
}