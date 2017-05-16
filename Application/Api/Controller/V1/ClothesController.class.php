<?php
namespace Api\Controller\V1;
use Admin\Enum\BaseEnum;
use Api\Controller\BaseController;

class ClothesController extends BaseController{

    /**
     * @var \Api\Logic\UserLogic
     */
    protected $userLogic;

    public function _initialize(){
        $this->userLogic = D('User', 'Logic');
    }

    /**
     * 进入换装
     */
    public function getClothesSet(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $data = $this->getAvailableData();

        $UserInformation = D('Userinformation');
        $ClothesInformation = D('Clothesinformation');
        $UserClothes = D('Userclothes');

        $imageSet = $UserInformation->where("userID = %d",$data['userID'])->getField('userImageSet');

        if(count($imageSet)){
            $resultArray = array();
            for($i=0; $i < 7; $i++){
                $clothesID = $UserClothes->where("userID = %d AND clothesParts = %d",array($data['userID'],$i))->getField("clothesID",true);

                if(count($clothesID)){
                    $map['clothesID'] = array("IN",$clothesID);
                    $map['status'] = array("EQ",BaseEnum::ACTIVE);
                    $result = $ClothesInformation->field("clothesName,clothesMarket,clothesAfter,clothesBefore,clothesCenter,clothesImportant,clothesPosition")
                        ->where($map)->select();
                }else{
                    $result = array();
                }

                $resultArray[$i] = $result;

            }

            if(count($resultArray)){
                $this->apiSuccess(array("infor" => $resultArray,"imageSet" => $imageSet));
            }else{
                $this->apiError("未找到任何信息");
            }
        }else{
            $this->apiError("用户信息不存在");
        }

    }

    /**
     * 商城接口
     */
    public function getUserClothes(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'Gender');
        $this->validate('请输入Gender');

        $data = $this->getAvailableData();
        $kindArray = array("0","1","3","5","6");

        $ClothesInformation = D('Clothesinformation');
        $UserClothes = D('Userclothes');

        $field = "clothesID,clothesName,clothesNormal,clothesMarket,clothesImportant,clothesDescripe,clothesMoney,clothesDiamond,achivePoint,clothesParts";

        $arrayOne = array();
        for($i=0; $i < count($kindArray); $i++) {
            if($i == 2){
                $map['clothesGender'] = $data['Gender'];
                $map['clothesParts'] = array('between',array('2','4'));
                $map['status'] = array("EQ",BaseEnum::ACTIVE);
                $arrayTwo = $ClothesInformation->field($field)->where($map)->select();
            }else{
                $map['clothesGender'] = $data['Gender'];
                $map['clothesParts'] = $kindArray[$i];
                $map['status'] = array("EQ",BaseEnum::ACTIVE);
                $arrayTwo = $ClothesInformation->field($field)->where($map)->select();
            }

            $arrayOne[$i] = $arrayTwo;
        }

        $arrayThree = $UserClothes->where("userID = %d",$data['userID'])->getField('clothesID',true);

        $this->apiSuccess(array("info" => $arrayOne,"Me" => $arrayThree));
    }

    /**
     * 购买衣服
     */
    public function buyClothes(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'achivePoint');
        $this->validate('请输入achivePoint');
        $this->validator->rule('required', 'clothesAll');
        $this->validate('请输入clothesAll');
        $this->validator->rule('required', 'clothesID');
        $this->validate('请输入clothesID');
        $this->validator->rule('required', 'clothesParts');
        $this->validate('请输入clothesParts');
        $this->validator->rule('required', 'userMoney');
        $this->validate('请输入userMoney');
        $this->validator->rule('required', 'userDiamond');
        $this->validate('请输入userDiamond');

        $data = $this->getAvailableData();

        $UserClothes = D('Userclothes');
        $AchiveProgress = D('Achiveprogress');
        $UserInformation = D('Userinformation');

        $UserClothes->startTrans();  // 开启事务

        $result1 = $AchiveProgress->where('userID = %d',$data['userID'])->save(array(
            'achivePoint' => $data['achivePoint'],
            'appearanceNum' => $data['clothesAll']
        ));

        $result2 = $UserClothes->add(array(
            'userID' => $data['userID'],
            'clothesID' => $data['clothesID'],
            'clothesParts' => $data['clothesParts']
        ));

        $userMoney = $this->userLogic->getUserMoney($data);

        if($userMoney){
            $money = $userMoney['userMoney'] - $data['userMoney'];

            $result3 = $AchiveProgress->where('userID = %d',$data['userID'])->save(array(
                'spendMoney' => $userMoney['spendMoney'] + $money,
                'daySpendMoney' => $userMoney['daySpendMoney'] + $money,
            ));

            $result4 = $UserInformation->where('userID = %d',$data['userID'])->save(array(
                'userMoney' => $data['userMoney'],
                'userDiamond' => $data['userDiamond']
            ));

            if($result1 !== false && $result2 !== false && $result3 !== false && $result4 !== false){
                $UserClothes->commit();
                $this->apiSuccess(null,'操作成功');
            }else{
                $UserClothes->rollback();
                $this->apiError('操作失败');
            }
        }else{
            $UserClothes->rollback();
            $this->apiError('未找到用户信息');
        }
    }
}