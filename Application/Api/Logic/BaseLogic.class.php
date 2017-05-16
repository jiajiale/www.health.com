<?php
namespace Api\Logic;

/**
 * 基础逻辑层
 * 所有逻辑层模型都需要继承此模型
 */
class BaseLogic
{
    
    // 最近错误信息
    protected $error = '操作失败';
    // 最近错误编号
    protected $code = 401;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    public function __construct()
    {

        $this->_initialize();
    }

    // 回调方法 初始化
    protected function _initialize()
    {
    }

    /**
     * 返回模型的错误信息
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


}
