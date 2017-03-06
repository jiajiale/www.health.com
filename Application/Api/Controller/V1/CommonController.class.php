<?php
namespace Api\Controller\V1;
use Api\Controller\BaseController;
use Exception;

class CommonController extends BaseController{
    public function index(){
        $data = $this->getAvailableData();

        var_dump($data);
    }

    /**
     * 检测版本信息
     */
    public function checkVersion(){
        $AppInfo = D('Appinfo');

        $versionInfo = $AppInfo->field('version,content')->order('version DESC')->find();

        if($versionInfo){
            $this->apiSuccess($versionInfo);
        }else{
            $this->apiError('未找到版本信息');
        }
    }

    /**
     * 上传图片
     */
    public function uploadImage(){
        $data = $this->getAvailableData();

        if(isset($data['condition']) && !empty($data['condition'])){
            switch($data['condition']){
                case 1:
                    $data['folder'] = 'Clothes';
                    $this->uploadClothes($data);
                    break;
                case 2:
                    // 换装图片上传
                    $data['folder'] = 'Clothes';
                    $this->uploadClothes($data);
                    break;
                default:
                    $data['folder'] = 'Clothes';
                    $this->uploadClothes($data);
            }
        }else{
            $this->apiError('请求参数不正确',401);
        }
    }

    /**
     * 换装图片上传
     * @param $data
     */
    public function uploadClothes($data){
        $this->validator->rule('required', 'userID');
        $this->validate('请输入userID');
        $this->validator->rule('required', 'saveKind');
        $this->validate('请输入saveKind');

        $UserInformation = D('Userinformation');

        // 保存图片
        $this->saveImages($data);

        if(isset($data['filename']) && !empty($data['filename'])){
            if($data['saveKind'] == 1){
                $this->validator->rule('required', 'imageSet');
                $this->validate('请输入imageSet');

                $result = $UserInformation->where("userID = %d",$data['userID'])->save(array(
                    "userImage" => $data['filename'],
                    "userImageSet" => $data['imageSet']
                ));
            }else{
                $this->validator->rule('required', 'backImage');
                $this->validate('请输入backImage');

                $result = $UserInformation->where("userID = %d",$data['userID'])->save(array(
                    "userHead" => $data['filename'],
                    "backImageView" => $data['backImage']
                ));
            }

            if($result !== false){
                $this->apiSuccess('换装成功');
            }else{
                $this->apiError('换装失败');
            }
        }else{
            $this->apiError('图片上传失败');
        }
    }

    /**
     * 保存图片
     * @param $data
     */
    public function saveImages(&$data){
        $this->validator->rule('required', 'images');
        $this->validate('请上传图片');

        $base64Data = $data['images'];

        $config = array(
            'maxSize' => 1048576, //图片最大为1M
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'rootPath' => './Public/',
            'savePath' => 'Uploads/Api/' . $data['folder'] . '/',
            'hash' => false,
            'subName' => date('Ymd',time()),
            'saveName' => date('YmdHis')
        );

        $path = $config['rootPath'] . $config['savePath'] . $config['subName'];

        if(!is_dir($path)){
            mkdir($path,0777,true);
        }

        try {
            $data = explode(',', $base64Data);
            $content = str_replace(' ', '+', $data[1]);

            $a = explode(';', $data[0]);
            $b = explode('/', $a[0]);
            $ext = $b[1]; //获取后缀
            $filename = $path . '/' . $config['saveName'] . '.' . $ext;

            // 检查文件的后缀
            if(!in_array($ext,$config['exts'])){
                $this->apiError('上传文件后缀不允许',409);
            }

            $image = base64_decode($content);

            if(file_put_contents($filename, $image) === false){
                $this->apiError('图片上传失败',409);
            }

            // 检查文件的大小
            if(filesize($filename) > $config['maxSize']){
                unlink($filename);
                $this->apiError('上传文件太大',409);
            }

            $data['filename'] = $config['subName'] . '/' . $config['saveName'] . '.' . $ext;

        } catch (Exception $e) {
            $this->apiError('上传失败',409);
        }
    }
}