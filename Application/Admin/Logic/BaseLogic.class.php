<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;

class BaseLogic {

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
}