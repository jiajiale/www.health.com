<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;

class BaseLogic {

    // 最近的错误信息
    protected $error = '操作失败';
    // 最近错误编号
    protected $code = 9014;

    public function __construct(){
        $this->_initialize();
    }

    /**
     * 返回模型的错误代码
     * @return int
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * 返回模型的错误信息
     * @return string
     */
    public function getError(){
        return $this->error;
    }
}