<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/28
 * Time: 14:20
 */

namespace Common\Util;

vendor('Xinge.XingeApp');

class Xinge {

    private $push;

    private $config;

    public function __construct(){
        $this->config = C('XINGE');
        $this->push = new XingeApp($this->config['ACCESSID'],$this->config['SECRETKEY']);
    }

    /**
     * 根据token推送消息
     * @param $content
     * @param $token
     * @return array|mixed
     */
    public function PushTokenIos($content,$token){
        $mess = new MessageIOS();
        $mess->setAlert($content);
        $ret = $this->push->PushSingleDevice($token, $mess, XingeApp::IOSENV_DEV);
        return $ret;
    }
}