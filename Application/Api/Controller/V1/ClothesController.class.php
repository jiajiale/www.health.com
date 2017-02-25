<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class ClothesController extends BaseController{

    /**
     * 进入换装
     */
    public function getClothesSet(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userMoney');
        $this->validate('请输入userMoney');
        $this->validator->rule('required', 'userDiamond');
        $this->validate('请输入userDiamond');
        $this->validator->rule('required', 'userEXP');
        $this->validate('请输入userEXP');
        $this->validator->rule('required', 'achivePoint');
        $this->validate('请输入achivePoint');

        $data = $this->getAvailableData();

        $UserInformation = D('Userinformation');
        $ClothesInformation = D('Clothesinformation');
        $UserClothes = D('Userclothes');

        $imageSet = $UserInformation->field("userImageSet")->where("userID = %d",$data['userID'])->find();

        $resultArray = array();
        for($i=0; $i < 7; $i++){
            $clothesID = $UserClothes->field("clothesID")->where("userID = %d AND clothesParts = %d",array($data['userID'],$i));

            $result = $ClothesInformation->field("clothesName,clothesMarket,clothesAfter,clothesBefore,clothesCenter,clothesImportant,clothesPosition")
                    ->where(array("IN",$clothesID))->select();

            $resultArray[$i] = $result;

        }

        if(count($resultArray)){
            $this->apiSuccess(array("infor" => $resultArray,"imageSet" => $imageSet));
        }else{
            $this->apiError("未找到任何信息");
        }
    }
}