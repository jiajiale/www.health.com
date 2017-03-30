<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;
use Common\Util\Xinge;
use Exception;
use Think\Log;

class ArticleController extends BaseController{

    /**
     * @var \Api\Logic\ArticleLogic
     */
    protected $articleLogic;

    public function _initialize(){
        $this->articleLogic = D('Article', 'Logic');
    }

    public function index(){
        $data = $this->getAvailableData();

        if(isset($data['condition'])){
            switch($data['condition']){
                case 1:
                    $this->addArticles($data);
                    break;
                case 2:
                    $this->getArticles($data);
                    break;
                case 3:
                    $this->praiseArticles($data);
                    break;
                case 4:
                    $this->commentArticles($data);
                    break;
                case 5:
                    $this->getArticleDetail($data);
                    break;
                case 6:
                    $this->delArticles($data);
                    break;
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 发布动态
     * @param $data
     */
    public function addArticles($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'sayText');
        $this->validate('请输入sayText');

        $Saytable = D('Saytable');
        $Daytask = D('Daytask');
        $Achiveprogress = D('Achiveprogress');

        $base64Data = $data['NBC'];

        $config = array(
            'maxSize' => 1048576, //图片最大为1M
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'rootPath' => './Public/',
            'savePath' => 'Api/sayImage/',
            'hash' => false,
            'subName' => ''
        );

        $path = $config['rootPath'] . $config['savePath'] . $config['subName'];

        if(!is_dir($path)){
            mkdir($path,0777,true);
        }

        try {
            $imageArr = explode(',', $base64Data);
            $content = str_replace(' ', '+', $imageArr[1]);

            $a = explode(';', $imageArr[0]);
            $b = explode('/', $a[0]);
            $ext = $b[1]; //获取后缀
            $config['saveName'] = $data['userID'] . date('YmdHis',time()) . '.' . $ext;

            $filename = $path . '/' . $config['saveName'];

            // 检查文件的后缀
            if(!in_array($ext,$config['exts'])){
                $this->apiError('上传文件后缀不允许',409);
            }

            $image = base64_decode($content);

            if(file_put_contents($filename, $image) === false){
                $this->apiError('图片上传失败',408);
            }

            // 检查文件的大小
            if(filesize($filename) > $config['maxSize']){
                unlink($filename);
                $this->apiError('上传文件太大',407);
            }

            $insertId = $Saytable->add(array(
                "userID" => $data['userID'],
                "sayImage" => $config['saveName'],
                "sayText" => $data['sayText'],
                "sayTime" => date('Y-m-d H:i:s',time())
            ));

            if($insertId){
                $Daytask->where('userID = %d',$data['userID'])->setInc('dayRelease');
                $achiveInfo = $Achiveprogress->where('userID = %d',$data['userID'])->find();
                $achiveInfo['Release'] = $achiveInfo['Release'] + 1;
                $Achiveprogress->save($achiveInfo);

                //$Achiveprogress->where('userID = %d',$data['userID'])->setInc('Release');

                $this->apiSuccess(array('q' => $insertId));
            }else{
                $this->apiError('发布动态失败');
            }

        } catch (Exception $e) {
            Log::write($e->getMessage());
            $this->apiError('上传失败',406);
        }
    }

    /**
     * 获取空间动态
     * @param $data
     */
    public function getArticles($data)
    {
        $this->validator->rule('required', 'userID');
        $this->validate('请输入用户ID');
        $this->validator->rule('required', 'kind');
        $this->validate('请输入kind');
        $this->validator->rule('required', 'run');
        $this->validate('请输入run');

        $Saytexttable = D('Saytexttable');

        $sayInfo = $this->articleLogic->getSayInfo($data);

        $resultArray = array();
        $idArray = array();
        foreach ($sayInfo as $key => $val) {
            $val['sayNumber'] = $Saytexttable->where('sayID = %d AND sayKind = 0', $val['sayID'])->count();
            $val['zanNumber'] = $Saytexttable->where('sayID = %d AND sayKind = 2', $val['sayID'])->count();

            $idArray[] = $val["sayID"];
            $resultArray[] = $val;
        }

        $zanArray = $sayArray = array();
        for($i = 0; $i < count($idArray); $i++){
            $zanArray[$i] = $Saytexttable->where("userID = %d AND sayID = %d AND sayKind = 2",array($data['friendID'],$idArray[$i]))->count();

            $data['idArr'] = $idArray[$i];
            $sayArray[$i] = $this->articleLogic->getUserSayInfo($data);
        }

        if(count($resultArray) > 0){
            $this->apiSuccess(array('info' => $resultArray,'time' =>date('Y-m-d H:i:s',time()),'zan' => $zanArray,'say' => $sayArray));
        }else{
            $this->apiError('未找到任何信息');
        }
    }

    /**
     * 说说点赞
     * @param $data
     */
    public function praiseArticles($data){
        $this->validator->rule('required', 'sayID');
        $this->validate('请输入sayID');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'userName');
        $this->validate('请输入userName');

        $Saytexttable = D('Saytexttable');
        $Achiveprogress = D('Achiveprogress');
        $UserInformation = D('Userinformation');

        $zanInfo = $Saytexttable->where("userID = %d AND sayID = %d AND sayKind = 2",array($data['userID'],$data['sayID']))->select();
        $index = 0;

        if($zanInfo){
            // 有点赞的信息
            $index = 0;
            $Saytexttable->startTrans();

            $flag1 = $Saytexttable->where("userID = %d AND sayID = %d AND sayKind = 2",array($data['userID'],$data['sayID']))->delete();

            $index = $flag1 !== false ? 0 : 1;

            $userZan = $Achiveprogress->where('userID = %d',$data['userID'])->find();
            $userZan['accumulateZanNum'] = $userZan['accumulateZanNum'] - 1;
            $userZan['dayZan'] = $userZan['dayZan'] - 1;

            $flag2 = $Achiveprogress->save($userZan);

            if($flag1 !== false && $flag2 !== false){
                $Saytexttable->commit();
                $this->apiSuccess(array('info' => $index),'点赞成功');
            }else{
                $Saytexttable->rollback();
                $this->apiError('点赞失败');
            }
        }else{
            // 没有点赞的信息
            $Saytexttable->startTrans();

            $flag1 = $Saytexttable->add(array(
                "userID" => $data['userID'],
                "sayID" => $data['sayID'],
                "sayMessage" => 'zan',
                "textTime" => date('Y-m-d H:i:s',time()),
                "sayKind" => 2
            ));

            $index = $flag1 !== false ? 1 : 0;

            if($data['sendPut'] == 1){
                $Xinge = new Xinge();
                $token = $UserInformation->where('userID = %d',$data['friendID'])->getField('token');
                $Xinge->PushTokenIos($data['userName'].'点赞了你的照片',$token);
            }

            $userZan = $Achiveprogress->where('userID = %d',$data['userID'])->find();
            $userZan['accumulateZanNum'] = $userZan['accumulateZanNum'] + 1;
            $userZan['dayZan'] = $userZan['dayZan'] + 1;

            $flag2 = $Achiveprogress->save($userZan);

            if($flag1 !== false && $flag2 !== false){
                $Saytexttable->commit();
                $this->apiSuccess(array('info' => $index),'点赞成功');
            }else{
                $Saytexttable->rollback();
                $this->apiError('点赞失败');
            }
        }
    }

    /**
     * 发表评论
     * @param $data
     */
    public function commentArticles($data){
        $this->validator->rule('required', 'sayID');
        $this->validate('请输入sayID');
        $this->validator->rule('required', 'sayKind');
        $this->validate('请输入sayKind');
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'sayMessage');
        $this->validate('请输入sayMessage');
        $this->validator->rule('required', 'friendID');
        $this->validate('请输入friendID');
        $this->validator->rule('required', 'userName');
        $this->validate('请输入userName');
        $this->validator->rule('required', 'sendPut');
        $this->validate('请输入sendPut');

        $result = $this->articleLogic->commentArticles($data);

        if($result){
            $this->apiSuccess(array('time' => $result['time'],'ret' => $result['ret']),'发表评论成功');
        }else{
            $this->apiError('发表评论失败');
        }
    }

    /**
     * 获取动态详情
     * @param $data
     */
    public function getArticleDetail($data){
        $this->validator->rule('required', 'sayID');
        $this->validate('请输入sayID');
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');

        $UserInformation = D('Userinformation');

        $sayUserInfo = $this->articleLogic->getSayUser($data);

        $resultA = array();
        $userSayInfo = $this->articleLogic->getUserSay($data);
        foreach($userSayInfo as $key => $val){
            if($val["friendID"]){
                $val['friendName'] = $UserInformation->where('userID = %d',$val["friendID"])->getField('userName');
            }

            $resultA[] = $val;
        }

        $sayUserImage = $this->articleLogic->getSayUserImage($data);

        $sayOkArr = $this->articleLogic->getSayOk($data);

        if(count($sayOkArr) > 0){
            $sayOK = 1;
        }else{
            $sayOK = 0;
        }

        if(count($sayUserInfo) > 0){
            $this->apiSuccess(array("info" => $sayUserInfo,"say" => $resultA,"zan" => $sayUserImage,"time" => date('Y-m-d H:i:s',time()),"isOK"=>$sayOK));
        }else{
            $this->apiError('未找到相关信息');
        }
    }

    /**
     * 删除空间动态
     * @param $data
     */
    public function delArticles($data){
        $this->validator->rule('required', 'sayID');
        $this->validate('请输入sayID');

        $Saytexttable = D('Saytexttable');
        $Sayable = D('Saytable');

        $Saytexttable->startTrans();
        $flag1 = $Saytexttable->where('sayID = %d',$data['sayID'])->delete();
        $flag2 = $Sayable->where('sayID = %d',$data['sayID'])->delete();

        if($flag1 !== false && $flag2 !== false){
            $Saytexttable->commit();
            $this->apiSuccess('删除成功');
        }else{
            $Saytexttable->rollback();
            $this->apiError('删除失败');
        }

    }
}