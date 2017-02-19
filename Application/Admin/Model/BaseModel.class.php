<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:45
 */

namespace Admin\Model;

use Think\Model;

class BaseModel extends Model{

    /* 默认数据库 */
    protected $connection = 'DB_MAIN';
}