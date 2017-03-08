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

        $data = $this->getAvailableData();

        $UserInformation = D('Userinformation');
        $ClothesInformation = D('Clothesinformation');
        $UserClothes = D('Userclothes');

        $imageSet = $UserInformation->field("userImageSet")->where("userID = %d",$data['userID'])->find();

        if(count($imageSet)){
            $resultArray = array();
            for($i=0; $i < 7; $i++){
                $clothesID = $UserClothes->where("userID = %d AND clothesParts = %d",array($data['userID'],$i))->getField("clothesID",true);

                if(count($clothesID)){
                    $map['clothesID'] = array("IN",$clothesID);
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
                $arrayTwo = $ClothesInformation->field($field)->where($map)->select();
            }else{
                $map['clothesGender'] = $data['Gender'];
                $map['clothesParts'] = $kindArray[$i];
                $arrayTwo = $ClothesInformation->field($field)->where($map)->select();
            }

            $arrayOne[$i] = $arrayTwo;
        }

        $arrayThree = $UserClothes->field('clothesID')->where("userID = %d",$data['userID'])->select();

        $this->apiSuccess(array("info" => $arrayOne,"Me" => $arrayThree));
    }
}