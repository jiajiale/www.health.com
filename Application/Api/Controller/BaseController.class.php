<?php
namespace Api\Controller;

use Api\Enum\AppTypeEnum;
use Api\Enum\BoolEnum;
use Org\Util\Validator;
use Redis;
use Think\Hook;

/**
 * 基础API控制器
 *
 * 用来设置API控制器公用的方法
 *
 */
class BaseController
{


    /**
     * @var String
     */
    protected $fields;

    protected $user;

    protected $userAccount;

    protected $appId;

    protected $serialNum;

    protected $request;

    protected $clientId;

    /**
     * @var \Api\Logic\userLogic
     */
    protected $userLogic;
    /**
     * @var \Api\Logic\UserDeviceRelationLogic
     */
    protected $userDeviceRelationLogic;

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        Hook::listen('api_begin');

        $this->request = preg_replace_callback('/\\n\s+/',function(){
            return '';
        },removeBOM(@file_get_contents('php://input')));

        $this->request = json_clean_decode($this->request,true);

        $this->validator = new Validator($this->request);

        $this->serialNum = $this->request['serialNum'];
        // app检查
        //$this->check_app();

        // 检查重复请求
        //$this->check_duplicate();

        // 记录请求日志
        $this->write_log();

        //控制器初始化
        if(method_exists($this,'_initialize'))
            $this->_initialize();

        $this->userLogic = D('User','Logic');
        $this->userDeviceRelationLogic = D('UserDeviceRelation', 'Logic');

    }

    /**
     * 验证数据
     * @param bool $message
     */
    public function validate($message = false)
    {
        if (!$this->validator->validate()) {

            if ($message) {
                $this->apiError($message, 9007);
            }

            $this->apiError($this->validator->errors(), 9007);
        }
        $this->validator->clearRule();
    }

    /**
     * 获取有效数据
     */
    public function getAvailableData()
    {
        $data = array();

        $request = $this->validator->data();
        foreach ($request as $key => $value) {
            if ($value != '') {
                $data[$key] = $value;
            }
        }
        return $data;
    }


    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (0 === strcasecmp($method, ACTION_NAME . C('ACTION_SUFFIX'))) {
            if (method_exists($this, '_empty')) {
                // 如果定义了_empty操作 则调用
                $this->_empty($method, $args);
            } else {
                E(L('_ERROR_ACTION_') . ':' . ACTION_NAME);
            }
        } else {
            E(__CLASS__ . ':' . $method . L('_METHOD_NOT_EXIST_'));
            return;
        }
    }

    //预留处理
    public function apiReturn($data = '',$class, $is_lost = 0, $msg = '', $code = '')
    {
        $apiData = array();
        $apiData['code'] = $code;

        if (is_array($msg)) {
            $msg = current(current($msg));
        }

        $apiData['msg'] = $msg;
        $apiData['is_lost'] = $is_lost;
        $apiData['serialNum'] = $this->serialNum;

        if(!is_null($data) || $data != ''){
            if (method_exists($data, 'getItems')) {
                $apiData[$class] = $data->getItems();
                $page = array();
                $page['pageIndex'] = $data->getPageIndex();
                $page['pageSize'] = $data->getPageSize();
                $page['itemsCount'] = $data->getItemsCount();
                $page['totalPages'] = $data->getTotalPages();
                $apiData['page'] = $page;
            } else {
                $apiData[$class] = $data;
            }

            //过滤字段
            $apiData[$class] = $this->filterFields($apiData[$class]);
            Hook::listen('api_return',$apiData);
        }

        $output = I('output', 'json');
        $result = '';
        switch (strtoupper($output)) {
            case 'JSON':
                header('Content-Type:application/json; charset=utf-8');
                $json = __json_encode($apiData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                //$json = json_format($json);
                $result = $json;
                break;
            case 'JSONP':
                header('Content-Type:application/json; charset=utf-8');
                $json = json_encode($apiData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $json = json_format($json);
                $callback = I('callback', 'callback');
                $result = ($callback . '(' . $json . ');'); //jsonp 返回
                break;
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                $result = (xml_encode($apiData, 'root'));
                break;
        }

        //Hook::listen('api_end');
        exit ($result);
    }

    //成功返回
    public function apiSuccess($data = null, $class = 'data' ,$msg = '')
    {
        if (!$msg) {
            $msg = "success";
        }
        $this->apiReturn($data, $class ,0, $msg, 8001);
    }

    //错误返回
    public function apiError($msg = '', $code = 0)
    {
        $this->apiReturn(null, 'data' ,9003, $msg, $code);
    }

    //默认找不到接口
    public function _empty()
    {
        $this->apiReturn("", "data",0, "找不到接口", 500);
    }

    /**
     *  data数据过滤不显示字段
     * @param $source
     * @return mixed
     */
    private function filterFields($source)
    {
        $fields = I('fields');

        if (APP_DEBUG && $fields == 'all') {
            return $source;
        }

        if ($fields && $this->fields) {
            $fields = explode(',', str_replace(' ', '', $fields));
            $this->fields = explode(',', str_replace(' ', '', $this->fields));
            $fields = array_intersect($fields, $this->fields); //返回交集
        } else if ($this->fields) {
            $this->fields = explode(',', str_replace(' ', '', $this->fields));
            $fields = $this->fields;
        }

        // 对字段进行过滤
        $source = walk_recursive_remove($source,function($value,$key) use($fields){

            if (!empty($fields) && is_string($key) && !in_array($key, $fields)) {
                return true;
            }

            if (is_null($value) || (is_array($value) && !count($value))) {
                return true;
            }

        });

        if (count($source)) {
            return $source;
        }
        return '';
    }

    /**
     * 检查用户是否登录
     */
    public function  need_auth()
    {
        $token = $this->request['token'];

        if (strlen($token) !== 32) {
            $this->apiError('请求参数错误', 9007);
        }

        // 从平台获取用户信息
        $config = C('REDIS');

        $redis = new Redis();
        $redis->connect($config['HOST'],$config['PORT']);

        $userInfo = json_decode(base64_decode($redis->get($token)),true);
        // 刷新redis中的token过期时间
        //$redis->setTimeout($token,$config['EXPIRE']);

        if($userInfo){
            $userInfo['app_id'] = $this->request['appId'];
            $userInfo['token'] = $token;

            $user = D('User')->alias('user')
                    ->field('user.*,user_account.username')
                    ->join('__USER_ACCOUNT__ AS user_account ON user_account.user_id = user.id')
                    ->where('platform_id = %d',$userInfo['id'])->find();

            if($user){
                // 数据库中有用户的信息
                if($user['is_black'] == BoolEnum::YES){
                    // 黑名单用户禁止登陆
                    $this->apiError('你已被加入黑名单', 9006);
                }else{
                    $this->userLogic->updateUserInfo($userInfo,$user);
                }
            }else{
                // 租户存在插入用户的信息
                $result = $this->userLogic->saveUserInfo($userInfo);

                if(!$result){
                    $this->apiError($this->userLogic->getError(),$this->userLogic->getCode());
                }

/*                $Tenant = D('Tenant');

                $tenant = $Tenant->where("name = '%s'",$userInfo['tenantName'])->find();

                if($tenant){
                    // 租户存在插入用户的信息
                    $result = $this->userLogic->saveUserInfo($userInfo,$tenant);

                    if(!$result){
                        $this->apiError($this->userLogic->getError(),$this->userLogic->getCode());
                    }

                }else{
                    // 租户不存在先插入租户的信息，再插入用户的信息
                    $result = $this->userLogic->saveTenantUserInfo($userInfo);

                    if(!$result){
                        $this->apiError($this->userLogic->getError(),$this->userLogic->getCode());
                    }
                }*/
            }

        }else{
            $this->apiError('token已过期', 9006);
        }

        $userInfoLocal['user'] = D('User')->field('id,name,sex AS gender,avatar,points,alipay,mobile,is_carer,tenant_ids')->where('platform_id = %d',$userInfo['id'])->find();
        $userInfoLocal['user_account'] = D('UserAccount')->where('user_id = %d',$userInfoLocal['user']['id'])->find();

        $this->userAccount = $userInfoLocal['user_account'];
        $this->user = $userInfoLocal['user'];
        $this->user['mobile'] = explode(',',$this->user['mobile']);
        $this->user['nickname'] = $userInfoLocal['user_account']['username'];

        //$this->bind_client();
    }

    /**
     * 检查应用来源
     */
    public function check_app()
    {
        $this->appId = $this->request['appId'];
        $this->clientId = $this->request['clientId'];

        $this->validator->rule('required', 'appId');
        $this->validate('appId不能为空');

        $this->validator->rule('required', 'clientId');
        $this->validate('clientId不能为空');

        if(strlen($this->appId) != 8 || (substr($this->appId,0,2) != '10' && substr($this->appId,0,2) != '20')){
            $this->apiError('appId不正确', -1);
        }

        if(strlen(trim($this->clientId)) != 32){
            $this->apiError('clientId不正确',-1);
        }
    }

    /**
     * 检查重复请求
     */
    public function check_duplicate(){
        if($this->serialNum == S('serialNum')){
            exit();
        }else{
            S('serialNum',$this->serialNum);
        }
    }

    /**
     * 记录请求日志
     */
    public function write_log(){
        $path = LOG_PATH . MODULE_NAME . '/' . date('Ymd');

        if(!is_dir($path)){
            mkdir($path,0777,true);
        }

        $file = $path . '/' . date('YmdH') . '.log';

        $content = "";
        $content .= '[' . $_SERVER['REQUEST_METHOD'] . ']' . ':';
        $content .= get_page_url() . ':';
        $content .= '[' . json_encode($this->request) . ']';
        $content .= "\r\n";

        file_put_contents($file,$content,FILE_APPEND);

    }

    /**
     * 绑定客户端
     */
    public function bind_client(){

        if($this->request['clientId']){
            $userDevice = D('UserDeviceRelation')
                ->where("user_id = %d AND client_id = '%s'",array($this->user['id'],$this->request['clientId']))->find();

            if($userDevice){
                $this->userDeviceRelationLogic->editUserDeviceRelation($userDevice);
            }else{
                $device = D('UserDeviceRelation')->where("client_id = '%s'",$this->request['clientId'])->find();

                if($device){
                    D('UserDeviceRelation')->where("client_id = '%s'",$this->request['clientId'])->delete();
                }

                $data['user_id'] = $this->user['id'];
                $data['client_id'] = $this->request['clientId'];

                if(substr($this->appId,0,2) != '10'){
                    $data['type'] = AppTypeEnum::ANDROID;
                }else{
                    $data['type'] = AppTypeEnum::IOS;
                }

                $this->userDeviceRelationLogic->saveUserDeviceRelation($data);
            }
        }
    }
    /**
     * 析构方法
     * @access public
     */
    public function __destruct()
    {
        // 执行后续操作
        Hook::listen('api_end');
    }

}
