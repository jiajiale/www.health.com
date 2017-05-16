<?php

namespace Admin\Rules;

class ClothesRule{

    public static $_validate = array(

        //默认规则(使用样例)
        'default'=>array(
            'clothesName'  => array(
                'required'  =>  '衣服名称必须填写'
            ),
            'clothesMarket'  => array(
                'required'  =>  '右侧列表衣服图片必须上传'
            ),
            'clothesNormal'  => array(
                'required'  =>  '商品列表衣服图片必须上传'
            ),
            'clothesDescripe'  => array(
                'required'  =>  '衣服描述必须填写'
            ),
        ),

        //对应方法规则
        'do_add'=>array(
        ),

        'do_edit'=>array(
        )
    );
}
