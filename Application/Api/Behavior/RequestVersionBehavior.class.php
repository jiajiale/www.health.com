<?php

namespace Api\Behavior;

use Think\Behavior;

class RequestVersionBehavior extends Behavior {

    public function run(&$content){
        $params = array_merge(I('get.'),I('post.'));

        $controller = CONTROLLER_NAME;

        if(isset($params['ver']) && $params['ver'] == 1){
            R("V1/" . $controller,$params);
        }elseif(isset($params['ver']) && $params['ver'] == 2){
            R("V2/" . $controller,$params);
        }else{
            R("V1/" . $controller,$params);
        }
    }
}