<?php

namespace Admin\Behavior;

use Common\Util\Validate;
use Think\Behavior;

class RequestValidateBehavior extends Behavior {

    public function run(&$content){
        $params = I('post.');

        if($params != null && count($params) > 0){
            $classNameArr = array_map(function($item){
                 return ucfirst($item);
            },explode('_',strtolower(CONTROLLER_NAME)));

            //读取验证规则
            $ruleClass =  '\\Admin\\Rules\\'.implode('',$classNameArr).'Rule';

            if(class_exists($ruleClass)){

                if(property_exists($ruleClass,'_validate')){
                    $rules = $ruleClass::$_validate;

                    //判断验证规则中是否有对应方法的规则
                    if(in_array(strtolower(ACTION_NAME),array_keys($rules))){
                        $methodRule = array_merge($rules['default'],$rules[strtolower(ACTION_NAME)]);
                    }

                    if(isset($methodRule)){
                        //根据验证规则验证请求参数
                        $test = validate::test($params,$methodRule);

                        //验证失败json格式返回验证不通过的结果
                        if(is_array($test)){
                            $data['state'] = 'fail';
                            $data['message'] = current($test);
                            header('Content-Type:application/json; charset=utf-8');
                            exit(json_encode($data,0));
                        }
                    }
                }

            }

        }

    }
}