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
     * 验证手机号是否注册
     * @param $data
     */
    public function checkPhone($data){
        $this->validator->rule('required', 'userPhone');
        $this->validate('请输入用户手机号');

        $UserInformation = D('Userinformation');

        $user = $UserInformation->where("userPhone = '%s'",$data['userPhone'])->find();

        if($user){

        }else{

        }
    }


    /**
     * 用户注册接口
     */
    public function register(){
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
}