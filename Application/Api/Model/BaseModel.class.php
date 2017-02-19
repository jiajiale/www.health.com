<?php

namespace Api\Model;
use Think\Model;


/**
 * 基础模型
 */
class BaseModel extends Model
{
    /* 默认数据库 */
    protected $connection = 'DB_MAIN';
}
