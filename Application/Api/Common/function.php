<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:12
 */
use Common\Util\GeTui;

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
 * 获取日期时间
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

/**
 * 生成令牌
 */
function get_token()
{
    return strtoupper(md5(uniqid(rand(), true)));
}

/**
 * 根据redis中的键名获取键值
 * @param $key
 * @param string $prefix
 * @return bool|string
 */
function get_redis_value($key,$prefix = ''){
    $config = C('REDIS');

    $redis = new Redis();
    $redis->connect($config['HOST'],$config['PORT']);

    return $redis->get($prefix . $key);
}

/**
 * 获取redis中的list集合
 * @param $key
 * @param string $prefix
 * @return array
 */
function get_redis_lvalue($key,$prefix = ''){
    $config = C('REDIS');

    $redis = new Redis();
    $redis->connect($config['HOST'],$config['PORT']);

    return $redis->lRange($prefix . $key,0,-1);
}

/**
 * 根据设备的类型推送对应的信息
 * @param $title
 * @param $content
 * @param array $clientInfo
 */
function pushMessageToList($title,$content,$clientInfo = array()){
    $androidList = $iosList = array();
    $GeTui = new GeTui();

    foreach($clientInfo as $key => $val){
        $clientInfoArr = explode(',',$val);

        if(substr($clientInfoArr[0],0,1) == 'A'){
            $androidList[] = $clientInfoArr[1];
        }

        if(substr($clientInfoArr[0],0,1) == 'I'){
            $iosList[] = $clientInfoArr[1];
        }
    }

    if($androidList && count($androidList) > 0){
        $GeTui->pushMessageToList($title,$content,$androidList,'20000000');
    }

    if($iosList && count($iosList) > 0){
        $GeTui->pushMessageToList($title,$content,$iosList,'10000000');
    }
}
/**
 * 计算两个日期之间相差的分钟数
 * @param $time1
 * @param $time2
 * @return float
 */
function get_minute_diff($time1,$time2){
    $time1_num = preg_match('/[^0-9]/',$time1) > 0 ?  strtotime($time1) : intval($time1);
    $time2_num = preg_match('/[^0-9]/',$time2) > 0 ?  strtotime($time2) : intval($time2);

    return floor(abs(($time1_num - $time2_num)) / 60);
}

/**
 * 格式化推送消息的时间
 * @param $date
 * @return string
 */
function format_push_date($date){
    $date_arr = date_parse($date);
    $today_arr = date_parse(date('Y-m-d',time()));

    $date_str = $date_arr['month'] . '月' . $date_arr['day'] . '日';

    switch($date_arr['day'] - $today_arr['day']){
        case 0:
            $date_str .= '（今天）';
            break;
        case 1:
            $date_str .= '（明天）';
            break;
        case 2:
            $date_str .= '（后天）';
    }

    return $date_str;
}
/**
 *  格式化json
 * @param $json
 * @return string
 */
function json_format($json)
{
    // 缩进处理
    $ret = '';
    $pos = 0;
    $length = strlen($json);
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i = 0; $i <= $length; $i++) {

        $char = substr($json, $i, 1);

        if ($char == '"' && $prevchar != '\\') {
            $outofquotes = !$outofquotes;
        } elseif (($char == '}' || $char == ']') && $outofquotes) {
            $ret .= $newline;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
            $ret .= $newline;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}


/**
 * 递归删除数组中指定元素(并将键名转驼峰)
 * @param array $array
 * @param callable $callback
 * @return array
 */
function walk_recursive_remove (array $array, callable $callback) {
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = walk_recursive_remove($v, $callback);
        } else {
            if ($callback($v, $k)) {
                unset($array[$k]);
            }

            if(in_array($v,$array) && strpos($k,'_') !== false && !is_null($v)){
                $array[underline_to_hump($k)] = $v;
                unset($array[$k]);
            }
        }
    }

    return $array;
}

/**
 * 下划线名称转驼峰名称
 * @param $str
 * @return string
 */
function underline_to_hump($str){
    if(strpos($str,'_') === false){
        return $str;
    }else{
        return lcfirst(join('',array_map(function($v){
           return ucfirst($v);
        },explode('_',$str))));
    }
}

/**
 * 保存base64图片
 * @param $imageData
 * @return bool|string
 */
function base64_to_image($imageData)
{
    try {
        $data = explode(',', $imageData);
        $content = str_replace(' ', '+', $data[1]);

        $a = explode(';', $data[0]);
        $b = explode('/', $a[0]);
        $ext = $b[1]; //获取后缀
        $path = TEMP_PATH . uniqid() . '.' . $ext;
        $image = base64_decode($content);
        file_put_contents($path, $image);

        $file = array();
        $file['name'] = uniqid() . '.' . $ext;
        $file['type'] = 'image/' . $ext;
        $file['tmp_name'] = $path;
        $file['error'] = 0;
        $file['size'] = filesize($path);
        return $file;
    } catch (\Think\Exception $e) {
        return false;
    }
}

/**
 * 移除字符串的BOM头
 * @param string $str
 * @return string
 */
function removeBOM($str = '')
{
    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf))
        $str = substr($str, 3);

    return $str;
}

/**
 * 兼容性json解码
 * @param $json
 * @param bool $assoc
 * @param int $depth
 * @param int $options
 * @return mixed
 */
function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
    // search and remove comments like /* */ and //
    $json = preg_replace("#(/\\*([^*]|[\r\n]|(\\*+([^*/]|[\r\n])))*\\*+/)|([\\s\t]//.*)|(^//.*)#", '', $json);

    if(version_compare(phpversion(), '5.4.0', '>=')) {
        $json = json_decode($json, $assoc, $depth, $options);
    }
    elseif(version_compare(phpversion(), '5.3.0', '>=')) {
        $json = json_decode($json, $assoc, $depth);
    }
    else {
        $json = json_decode($json, $assoc);
    }

    return $json;
}

/**
 * 兼容性json编码
 * @param $data
 * @return string
 */
function __json_encode( $data ) {
    if( is_array($data) || is_object($data) ) {
        $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );

        if( $islist ) {
            $json = '[' . implode(',', array_map('__json_encode', $data) ) . ']';
        } else {
            $items = Array();
            foreach( $data as $key => $value ) {
                $items[] = __json_encode("$key") . ':' . __json_encode($value);
            }
            $json = '{' . implode(',', $items) . '}';
        }
    } elseif( is_string($data) ) {
        # Escape non-printable or Non-ASCII characters.
        # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
        $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
        $json    = '';
        $len    = strlen($string);
        # Convert UTF-8 to Hexadecimal Codepoints.
        for( $i = 0; $i < $len; $i++ ) {

            $char = $string[$i];
            $c1 = ord($char);

            # Single byte;
            if( $c1 <128 ) {
                $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                continue;
            }

            # Double byte
            $c2 = ord($string[++$i]);
            if ( ($c1 & 32) === 0 ) {
                $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                continue;
            }

            # Triple
            $c3 = ord($string[++$i]);
            if( ($c1 & 16) === 0 ) {
                $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                continue;
            }

            # Quadruple
            $c4 = ord($string[++$i]);
            if( ($c1 & 8 ) === 0 ) {
                $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;

                $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
                $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
                $json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
            }
        }
    } else {
        # int, floats, bools, null
        $json = strtolower(var_export( $data, true ));
    }
    return $json;
}

/**
 * 分词
 * @param $str
 * @return array
 */
function segment($str)
{

//    $result = string_to_array($str);
//    $ext = string_to_array('ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789 ');
//    foreach ($result as $key => $value) {
//        if (in_array($value, $ext)) {
//            unset($result[$key]);
//        }
//    }
    $result=array();
    $pa = new \Org\Util\PhpAnalysis();
    $pa->LoadDict();
    $pa->SetSource($str);
    $pa->StartAnalysis();
    $result = array_unique(array_merge($result, explode(' ', trim($pa->GetFinallyResult(' ')))));
    return $result;
}

/**
 * 获取请求地址
 * @return string
 */
function get_page_url(){
    $url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']);
    return $url;
}