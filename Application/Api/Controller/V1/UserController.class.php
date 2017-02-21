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
                    $this->loginValidate($data);
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
        $data['registerTime'] = get_date();
        $data['lastTime'] = get_date();
        $result = $this->userLogic->saveUserInfo($data);

        if($result){
            $this->apiSuccess(null,'完善用户信息成功');
        }else{
            $this->apiError('完善用户信息失败');
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
                'userEXP' => $data['userEXP']
            ));

            $flag2 = $Achiveprogress->where("userID = '%s'",$data['userID'])->setField('userLV',$data['userLV']);

            if($flag1 !== false && $flag2 !== false){
                $UserInformation->commit(); // 事务提交
                $this->apiSuccess(null,'更换用户称号成功');
            }else{
                $UserInformation->rollback();   // 事务回滚
                $this->apiError('更换用户称号失败');
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
                $this->apiSuccess(null,'更换用户称号成功');
            }else{
                $this->apiError('更换用户称号失败');
            }
        }else{
            $this->apiError('用户不存在');
        }
    }
}