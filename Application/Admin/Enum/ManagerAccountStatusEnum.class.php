<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/21
 * Time: 14:26
 */

namespace Admin\Enum;


use Org\Util\Enum;

class ManagerAccountStatusEnum extends Enum{
    const ACTIVE = 1;
    const DELETE = 99;

    static $desc = array(
        'ACTIVE'=>'正常',
        'DELETE'=>'删除',
    );

}