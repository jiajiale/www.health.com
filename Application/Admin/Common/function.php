<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:12
 */

/**
 * 密码二次加密
 * @param $passwordMd5
 * @param $key
 * @return string
 */
function hash_password($passwordMd5, $key = '')
{
    $salt = C('PASSWORD_SALT_KEY');
    return md5(md5(strtolower($passwordMd5) . $salt) . $key);
}

/**
 * 获取分页参数
 * @param null $pageIndex
 * @param null $pageSize
 * @return stdClass
 */
function get_page_para($pageIndex = null, $pageSize = null)
{

    if (!$pageIndex) {
        $pageIndex = I('page_index', 1);
    }

    if (!$pageSize) {
        $pageSize = I('page_size', C('PAGE_SIZE', null, 10));
    }

    if ($pageSize < 0 && $pageSize > 50) {
        $pageSize = C('PAGE_SIZE', null, 10);
    }

    $pagePara = new stdClass();
    $pagePara->pageIndex = $pageIndex;
    $pagePara->pageSize = $pageSize;
    return $pagePara;
}

/**
 * 数据签名
 * @param $data
 * @return string
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
 * 判断管理员是否登录
 * @return int
 */
function is_login(){
    $manager = session('manager_auth');

    if (empty($manager)) {
        return 0;
    } else {
        return session('manager_auth_sign') == data_auth_sign($manager) ? $manager['id'] : 0;
    }
}

function chk_ie_browser() {
    $userbrowser = $_SERVER['HTTP_USER_AGENT'];
    if ( preg_match( '/MSIE/i', $userbrowser ) ) {
        $usingie = true;
    } else {
        $usingie = false;
    }
    return $usingie;
}

/**
 * 获取日期
 * @return bool|string
 */
function get_date()
{
    return date('Y-m-d H:i:s');
}

/**
 * 获取某个二维数组某一列组成的数组
 * @param $arr
 * @param $filed
 * @return array
 */
function get_array_column($arr,$filed){
    $result = array();

    if(function_exists('array_column')){
        $result = array_column($arr,$filed);
    }else{
        foreach($arr as $key=>$val){
            $result[] = $val[$filed];
        }
    }
    return $result;
}
