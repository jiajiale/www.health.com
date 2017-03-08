<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class UserController extends BaseController{

    /**
     * @var \Api\Logic\UserLogic
     */
    protected $userLogic;
    /**
     * @var \Api\Logic\ArchiveProgressLogic
     */
    protected $archiveProgressLogic;

    public function _initialize(){
        $this->userLogic = D('User', 'Logic');
        $this->archiveProgressLogic = D('ArchiveProgress', 'Logic');
    }

    /**
     * 用户登录接口
     */
    public function login(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->loginValidate($data);
                    break;
                case 2:
                    $this->checkPhone($data);
                    break;
                default:
                    $this->loginValidate($data);
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 检查用户登录
     * @param $data
     */
    public function loginValidate($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户名');
        $this->validator->rule('required', 'userPassword');
        $this->validate('请输入密码');
        $this->validator->rule('required', 'uuid');
        $this->validate('请输入设备id号');

        $UserInformation = D('Userinformation');

        // 验证用户是否存在
        $user = $UserInformation->where("userID = '%s' OR userPhone = '%s'",array($data['userID'],$data['userID']))->find();

        if($user){
            $UserForbidden = D('Userforbidden');

            // 验证用户是否被禁用
            $userForbidden = $UserForbidden->where("userID = '%s'",$data['userID'])->find();

            if(!$userForbidden || $userForbidden['isOK'] == 0){
                // 验证用户密码是否正确
                if($user['userPassword'] == $data['userPassword']){
                    // 更新用户的设备信息
                    $result = $UserInformation->where("userId = '%s'",$data['userID'])->setField('UUID',$data['uuid']);

                    if($result !== false){
                        // 获取用户的信息
                        $userInfo = $this->userLogic->getUserInfo($data);

                        $data['startTime'] = date("Y-m-d",strtotime("-1 day"));
                        $achiveInfo = $this->archiveProgressLogic->getUserArchiveInfo($data);

                        $achiveArray = array();
                        if($achiveInfo){
                            $achiveArray[0] = $achiveInfo["dayFoot"];
                            $achiveArray[1] = $achiveInfo["foot"];
                        }else{
                            $achiveArray[0] = "0";
                            $achiveArray[1] = "20000";
                        }
                        $this->apiSuccess(array("info"=>$userInfo,"achive"=>$achiveArray));

                    }else{
                        $this->apiError('用户登录失败');
                    }
                }else{
                    $this->apiError('用户密码不正确');
                }
            }else{
                $this->apiError('该用户已经被禁用');
            }
        }else{
            $this->apiError('用户不存在');
        }
    }

    /**
     * 验证手机号是否注册接口
     * @param $data
     */
    public function checkPhone($data){
        $this->validator->rule('required', 'userPhone');
        $this->validate('请输入用户手机号');

        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userPhone = '%s'",$data['userPhone'])->find();

        if($user){
            $this->apiSuccess(null,'该手机号已存在');
        }else{
            $this->apiError('该手机号不存在');
        }
    }


    /**
     * 用户注册接口
     */
    public function register(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 0:
                    $this->checkUserId($data);
                    break;
                case 1:
                    $this->registerUserInfo($data);
                    break;
                default:
                    $this->checkUserId($data);
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 检查用户Id是否存在
     * @param $data
     */
    public function checkUserId($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userID = '%s'",$data['userID'])->find();

        if(!$user){
            $this->apiSuccess(null,'userID不存在');
        }else{
            $this->apiError('userID已经存在');
        }
    }

    /**
     * 完善用户信息
     * @param $data
     */
    public function registerUserInfo($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userPhone');
        $this->validate('请输入用户电话');
        $this->validator->rule('required', 'userPassword');
        $this->validate('请输入密码');
        $this->validator->rule('required', 'userName');
        $this->validate('请输入用户名');
        $this->validator->rule('required', 'userSex');
        $this->validate('请选择用户性别');
        $this->validator->rule('required', 'userHead');
        $this->validate('请选择用户头像');
        $this->validator->rule('required', 'userImage');
        $this->validate('请选择用户形象');
        $this->validator->rule('required', 'imageSet');
        $this->validate('请输入形象配置');
        $this->validator->rule('required', 'uuid');
        $this->validate('请输入uuid');
        $this->validator->rule('required', 'clothesID');
        $this->validate('请输入衣服ID');
        $this->validator->rule('required', 'userSign');
        $this->validate('请输入个性签名');

        // 转一下大小写
        $data['UUID'] = $data['uuid'];
        $data['userImageSet'] = $data['imageSet'];
        $data['registerTime'] = get_date();
        $data['lastTime'] = get_date();
        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userID = '%s'",$data['userID'])->find();

        if(!$user){
            $result = $this->userLogic->saveUserInfo($data);

            if($result){
                $this->apiSuccess(null,'完善用户信息成功');
            }else{
                $this->apiError('完善用户信息失败');
            }
        }else{
            $this->apiError('该用户已存在');
        }

    }

    /**
     * 更改用户信息接口
     */
    public function changeInfo(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->changeGlory($data);
                    break;
                case 2:
                    $this->changeUpgrade($data);
                    break;
                case 3:
                    $this->changeUserInfo($data);
                    break;
                default:

            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 更换用户称号
     * @param $data
     */
    public function changeGlory($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userGlory');
        $this->validate('请输入用户称号');

        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userID = '%s'",$data['userID'])->find();

        if($user){
            $result = $UserInformation->where("userID = '%s'",$data['userID'])->setField('userGlory',$data['userGlory']);

            if($result !== false){
                $this->apiSuccess(null,'更换用户称号成功');
            }else{
                $this->apiError('更换用户称号失败');
            }
        }else{
            $this->apiError('用户不存在');
        }
    }

    /**
     * 用户升级
     * @param $data
     */
    public function changeUpgrade($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userLV');
        $this->validate('请输入用户等级');
        $this->validator->rule('required', 'userEXP');
        $this->validate('请输入用户现有经验');

        $UserInformation = D('Userinformation');
        $Achiveprogress = D('Achiveprogress');

        $user = $UserInformation->where("userID = '%s'",$data['userID'])->find();

        if($user){
            $UserInformation->startTrans(); // 开启事务

            $flag1 = $UserInformation->where("userID = '%s'",$data['userID'])->save(array(
                'userLV' => $data['userLV'],
                'userExp' => $data['userEXP']
            ));

            $flag2 = $Achiveprogress->where("userID = '%s'",$data['userID'])->setField('userLV',$data['userLV']);

            if($flag1 !== false && $flag2 !== false){
                $UserInformation->commit(); // 事务提交
                $this->apiSuccess(null,'用户升级成功');
            }else{
                $UserInformation->rollback();   // 事务回滚
                $this->apiError('用户升级失败');
            }
        }else{
            $this->apiError('用户不存在');
        }
    }

    /**
     * 修改用户资料
     * @param $data
     */
    public function changeUserInfo($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userName');
        $this->validate('请输入用户名');
        $this->validator->rule('required', 'userSign');
        $this->validate('请输入用户个性签名');

        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userID = '%s'",$data['userID'])->find();

        if($user){
            $result = $UserInformation->where("userID = '%s'",$data['userID'])->save(array(
                'userName' => $data['userName'],
                'userSign' => $data['userSign']
            ));

            if($result !== false){
                $this->apiSuccess(null,'修改用户资料成功');
            }else{
                $this->apiError('修改用户资料失败');
            }
        }else{
            $this->apiError('用户不存在');
        }
    }

    /**
     * 查看用户资料
     */
    public function checkUserInfo(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');

        $data = $this->getAvailableData();

        $result = $this->userLogic->checkUserInfo($data);

        if($result){
            $this->apiSuccess($result);
        }else{
            $this->apiError('未找到相关用户');
        }
    }

    /**
     * 更新成就进度
     */
    public function updateAchieve(){
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

        $Achiveprogress = D('Achiveprogress');
        $UserInformation = D('Userinformation');

        $achieveInfo = $this->userLogic->getUserAchieveInfo($data);

        if($achieveInfo){
            $Achiveprogress->startTrans();  // 开启事务
            $flag1 = $Achiveprogress->where("userID = %d",$data['userID'])->setField("achivePoint",$data['achivePoint']);

            $flag2 = $UserInformation->where("userID = %d",$data['userID'])->save(array(
                "userMoney" => $achieveInfo['userMoney'] + $data['userMoney'],
                "userDiamond" => $achieveInfo['userDiamond'] + $data['userDiamond'],
                "userEXP" => $achieveInfo['userEXP'] + $data['userEXP'],
                "userAllMoney" => $achieveInfo['userAllMoney'] + $data['userMoney']
            ));

            $flag3 = $Achiveprogress->where("userID = %d",$data['userID'])->setField("achivePoint",$achieveInfo["achivePoint"] + $data["achivePoint"]);

            if($flag1 !== false && $flag2 !== false && $flag3 !== false){
                $Achiveprogress->commit();  // 提交事务
                $this->apiSuccess(null,'更新成就进度成功');
            }else{
                $Achiveprogress->rollback();    // 事务回滚
                $this->apiError('更新成就进度失败');
            }
        }else{
            $this->apiError('未找到相关用户信息');
        }
    }

    /**
     * 获取排行榜信息
     */
    public function getUserRanking(){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'condition');
        $this->validate('请输入userMoney');

        $data = $this->getAvailableData();

        $result = array();
        switch($data['condition']){
            case 199:
                // 好友排行榜
                $result = $this->userLogic->getFriendsRanking($data);
                break;
            case 299:
                // 步哟排行榜
                $data['yesterDay'] = date('Y-m-d',strtotime("-1 day"));
                $result = $this->userLogic->getAppRanking($data);
                break;
            default:
        }

        if(isset($result['meResult']) && count($result['meResult'])){
            if($result['achiveArray']){
                $this->apiSuccess(array("Me"=>$result['meResult'],"achivePoint"=>$result['achiveArray'],"clothesAll"=>$result['clothesArray'],"dayFoot"=>$result['footArray']));
            }else{
                $this->apiSuccess(array("Me"=>$result['meResult']));
            }
        }else{
            $this->apiError('没有找到信息');
        }
    }
}