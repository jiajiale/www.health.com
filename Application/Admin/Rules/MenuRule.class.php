<?php

namespace Admin\Rules;

class MenuRule{

    public static $_validate = array(

        //默认规则(使用样例)
/*        'default'=>array(
            'name'  => array(
                'required'  =>  '菜单名称必须填写',
                'regex(/^[\x{4e00}-\x{9fa5}]+$/u)' =>  '必须为数字'
            ),
            'url'   => array(
                'url'       =>  '必须为url格式'
            ),
            'sort'  => array(
                'required'  =>  '排序必须填写',
                'between(1,20)' =>  '排序在1-20之间'
            )
        ),

        //对应方法规则
        'do_add'=>array(
            'code'  => array(
                'required'  =>  '编码必须填写',
                'between(1,20)' =>  '编码在1-20之间'
            )
        )*/
    );
}
