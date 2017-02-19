<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:45
 */

namespace Admin\Model;


class RoleModel extends BaseModel{
    //定义表前缀
    protected $tablePrefix = 'st_common_';

    // 自动完成
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
    );
}