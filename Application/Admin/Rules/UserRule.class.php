<?php

namespace Admin\Rules;

class UserRule{

    public static $_validate = array(

        //默认规则(使用样例)
        'default'=>array(

        ),

        //对应方法规则
        'do_add'=>array(
        ),

        'do_edit'=>array(
            'userID'  => array(
                'required'  =>  '用户ID必须填写'
            ),
            'userName'  => array(
                'required'  =>  '用户昵称必须填写'
            ),
            'userPhone'  => array(
                'required'  =>  '用户手机号必须填写'
            ),
            'userMoney'  => array(
                'required'  =>  '用户金币必须填写'
            ),
            'userDiamond'  => array(
                'required'  =>  '用户钻石必须填写'
            ),
        )
    );
}
