<?php

namespace Admin\Enum;

use Org\Util\Enum;

class BoolEnum extends Enum{

    const NO = 0;
    const YES = 1;

    static $desc = array(
        'NO'=>'否',
        'YES'=>'是'
    );
}