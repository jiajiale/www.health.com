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

    protected $fields;

    protected $request;


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

        //控制器初始化
        if(method_exists($this,'_initialize'))
            $this->_initialize();

    }

    /**
     * 验证数据
     * @param bool $message
     * @param string $code
     */
    public function validate($message = false, $code = '401')
    {
        if (!$this->validator->validate()) {

            if ($message) {
                $this->apiError($message, $code);
            }

            $this->apiError($this->validator->errors(), $code);
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
    public function apiReturn($data = '',$class, $msg = '', $code = '')
    {
        $apiData = array();
        $apiData['code'] = $code;

        if (is_array($msg)) {
            $msg = current(current($msg));
        }

        $apiData['msg'] = $msg;

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
                $json = json_format($json);
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
    public function apiSuccess($data = null, $class = 'obj' ,$msg = '')
    {
        if (!$msg) {
            $msg = "success";
        }
        $this->apiReturn($data, $class , $msg, 200);
    }

    //错误返回
    public function apiError($msg = '', $code = 401)
    {
        $this->apiReturn(null, 'data', $msg, $code);
    }

    //默认找不到接口
    public function _empty()
    {
        $this->apiReturn("", "data","找不到接口", 500);
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
     * 析构方法
     * @access public
     */
    public function __destruct()
    {
        // 执行后续操作
        Hook::listen('api_end');
    }

}
