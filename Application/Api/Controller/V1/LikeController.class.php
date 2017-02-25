<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;

class LikeController extends BaseController{


    /**
     * @var \Api\Logic\LikeLogic
     */
    protected $likeLogic;
    /**
     * @var \Api\Logic\UserLogic
     */
    protected $userLogic;

    public function _initialize(){
        $this->userLogic = D('User', 'Logic');
        $this->likeLogic = D('Like', 'Logic');
    }

    public function index(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->checkLike($data);
                    break;
                case 2:
                    $this->doLike($data);
                    break;
                case 3:
                    $this->getLike($data);
                    break;
                default:
                    $this->checkLike($data);
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 检查是否可以点赞
     * @param $data
     */
    public function checkLike($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户名');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入密码');

        $Friends = D('Friends');

        $checkFans = $Friends->where("userID = %d AND friendID = %d",array($data['userID'],$data['friendID']))->find();
        $checkAttention = $Friends->where("userID = %d AND friendID = %d",array($data['friendID'],$data['userID']))->find();

        $isFans = count($checkFans) ? 1 : 0;
        $isAttention = count($checkAttention) ? 1 : 0;

        $this->apiSuccess(array("fans" => $isFans,"attention" => $isAttention));
    }

    /**
     * 点赞
     * @param $data
     */
    public function doLike($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');
        $this->validator->rule('required', 'zanNumber');
        $this->validate('请输入点赞数量');

        $result = $this->likeLogic->saveUserLike($data);

        if($result){
            $this->apiSuccess(null);
        }else{
            $this->apiError('点赞失败');
        }

    }

    /**
     * 获取点赞信息
     * @param $data
     */
    public function getLike($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $Friends = D('Friends');

        $userLikeInfo = $this->userLogic->getUserLikeInfo($data);

        if($userLikeInfo){
            foreach($userLikeInfo as $key => $val){
                $userLikeInfo[$key]['fansNum'] = $Friends->where("friendID = %d",$data['userId'])->count();
            }

            $this->apiSuccess(array("info" => $userLikeInfo,"time" => date("Y-m-d")));
        }else{
            $this->apiError('未找到任何信息');
        }
    }
}