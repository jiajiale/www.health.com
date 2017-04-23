<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:45
 */

namespace Admin\Model;


class ManagerAccountModel extends BaseModel{
    //定义表前缀
    protected $tablePrefix = '';

    // 自动完成
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('gmt_create', 'get_date', self::MODEL_INSERT, 'function'),
        array('gmt_last_login', 'get_date', self::MODEL_INSERT, 'function'),
        array('last_ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
        array('password', 'encryptPassword', self::MODEL_INSERT, 'callback'),
    );

    /**
     * 密码加密
     * @param $fields
     * @return string
     */
    protected function encryptPassword($fields){
        return hash_password($fields,C('PASSWORD_SALT_KEY'));
    }
}