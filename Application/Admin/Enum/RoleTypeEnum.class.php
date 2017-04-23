<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/21
 * Time: 14:26
 */

namespace Admin\Enum;


use Org\Util\Enum;

class RoleTypeEnum extends Enum{
    const ADMIN = 1;
    const COMPANY = 2;
    const PERSONAL = 3;

    static $desc = array(
        'ADMIN'=>'超级管理员',
        'COMPANY'=>'企业商家',
        'PERSONAL'=>'个人商家',
    );

}