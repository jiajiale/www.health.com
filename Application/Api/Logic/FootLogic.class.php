<?php

namespace Api\Logic;


class FootLogic extends BaseLogic{

    /**
     * @var \Api\Data\FootData
     */
    protected $footData;

    public function _initialize(){
        $this->footData = D('Foot', 'Data');
    }

    /**
     * 上传步数
     * @param $data
     * @return array|bool
     */
    public function uploadStep($data){
        $dateArr = $data['date'];
        $footArr = $data['foot'];
        $redundantFoot = $nowFootSum = $money = $allMoney = $exp = $accumulateFoot = 0;
        $resultArray = array();

        $FootInformation = D('Footinformation');
        $UserInformation = D('Userinformation');
        $AchiveProgress = D('Achiveprogress');

        $FootInformation->startTrans(); // 开启事务

        $result1 = false;
        for($i=0; $i < count($dateArr); $i++) {

            // 查看当前日期步数是否存在
            $footInfo = $FootInformation->field('foot')->where("time = '%s' AND userID = %d",array($dateArr[$i],$data['userID']))->find();

            $arr = explode("-",$dateArr[$i]);

            if($footInfo){
                // 当前日期存在
                $redundantFoot += $footInfo['foot'];

                // 更新当前日期的步数
                $result1 = $FootInformation->where("time = '%s' AND userID = %d",array($dateArr[$i],$data['userID']))->setField('foot',$footArr[$i]);
            }else{
                // 当前日期不存在
                $result1 = $FootInformation->add(array(
                    'userID' => $data['userID'],
                    'foot' => $footArr[$i],
                    'year' => $arr[0],
                    'month' => $arr[1],
                    'day' => $arr[2],
                    'time' => $dateArr[$i]
                ));
            }

            // 计算总步数
            $nowFootSum += $footArr[$i];
        }

        // 计算需要增加的步数
        $addNumber = $nowFootSum - $redundantFoot;
        if ($addNumber < 0) {
            $addNumber = 0;
        }

        // 获取用户的步数信息
        $userFootInfo = $this->footData->getUserFootInfo($data);

        if($userFootInfo){
            $money = $userFootInfo["userMoney"] + $addNumber;
            $allMoney = $userFootInfo["userAllMoney"] + $addNumber;
            $exp = $userFootInfo["userEXP"] + $addNumber;
            $accumulateFoot = $userFootInfo["accumulateFoot"] + $addNumber;

            $resultArray["userMoney"] = $money;
            $resultArray["userEXP"] = $exp;
            $resultArray["accumulateFoot"] = $accumulateFoot;
        }

        // 更新用户信息
        $result2 = $UserInformation->where("userID = %d",$data['userID'])->save(array(
            'lastTime' => date("Y-m-d",time()),
            'userMoney' => $money,
            'userAllMoney' => $allMoney,
            'userEXP' => $exp
        ));

        $dayFootQ = $footArr[count($footArr) - 1];
        $result3 = $AchiveProgress->where("userID = %d",$data['userID'])->save(array(
            'accumulateFoot' => $accumulateFoot,
            'dayFoot' => $dayFootQ
        ));

        if($result1 !== false && $result2 !== false && $result3 !== false){
            $FootInformation->commit();     // 事务提交

            $yesterDay = date('Y-m-d',strtotime("-1 day"));

            $resultArray["YesterdayFoot"] = $FootInformation->where("userID = %d AND time = '%s'",array($data['userID'],$yesterDay))->getField('foot');

            return array("info" => $resultArray, "poor" => $addNumber);
        }else{
            $FootInformation->rollback();   // 事务回滚
            return false;
        }
    }
}