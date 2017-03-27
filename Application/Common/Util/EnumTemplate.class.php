<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/21
 * Time: 17:08
 */

namespace Common\Util;


class EnumTemplate {
    /**
     * 枚举类多选模板
     * @param $select
     * @param $enumName
     * @param null $default
     * @param null $name
     * @param null $class
     */
    public static function enumSelect($select,$enumName,$default=null,$name=null,$class=null){
        $template = '<select name="'.$name.'" class="'.$class.'">';

        //设置多选默认项
        if($default){
            $template .= '<option value="">'.$default.'</option>';
        }

        $enumClass = '\\Admin\\Enum\\'.$enumName.'Enum';

        if(class_exists($enumClass)){
            $enumItems = $enumClass::getConstants();

            if($enumItems){
                foreach($enumItems as $key=>$val){
                    if($select == $val && $select != null){
                        $template .= '<option selected="selected" value="'.$val.'">'.$enumClass::getDesc($key).'</option>';
                    }else{
                        $template .= '<option value="'.$val.'">'.$enumClass::getDesc($key).'</option>';
                    }
                }
            }
        }

        $template .= '</select>';

        echo $template;
    }

    /**
     * 模板中获取枚举描述
     * @param $value
     * @param $enumName
     * @param string $space 命名空间
     * @param bool $flag    返回|输出
     */
    public static function getEnumDesc($value,$enumName,$space='Admin',$flag=true){
        $enumClass = '\\'. $space .'\\Enum\\'.$enumName.'Enum';

        if(class_exists($enumClass)){
            $enumItems = $enumClass::getConstants();

            if($enumItems){
                foreach($enumItems as $key=>$val){
                    if($value == $val){
                        if($flag){
                            echo $enumClass::getDesc($key);
                            break;
                        }else{
                            return $enumClass::getDesc($key);
                            //break;
                        }
                    }
                }
            }
        }
    }
}