<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/21
 * Time: 17:08
 */

namespace Common\Util;


use Admin\Enum\BaseEnum;

class GlobalUtil {

    /**
     * 根据文件的id获取文件的路径
     * @param $id
     * @return mixed
     */
    public function getFilePath($id){
        $File = D('File');

        return $File->where('id = %d AND status = %d',array($id,BaseEnum::ACTIVE))->getField('path');
    }
}