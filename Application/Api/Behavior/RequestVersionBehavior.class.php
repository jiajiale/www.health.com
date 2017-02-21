<?php

namespace Api\Behavior;

use Think\Behavior;
use Think\Exception;

class RequestVersionBehavior extends Behavior {

    public function run(&$content){
        $params = array_merge(I('get.'),I('post.'));

        $controller = CONTROLLER_NAME;

        try{
            if(isset($params['ver']) && $params['ver'] == 1){
                R("V1/" . $controller,$params);
            }elseif(isset($params['ver']) && $params['ver'] == 2){
                R("V2/" . $controller,$params);
            }else{
                R("V1/" . $controller,$params);
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}