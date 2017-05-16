<?php

namespace Admin\Enum;

use Org\Util\Enum;

class PositionEnum extends Enum{

    const RIGHT = 0;
    const LEFT = 1;

    static $desc = array(
        'RIGHT'=>'右手',
        'LEFT'=>'左手'
    );
}