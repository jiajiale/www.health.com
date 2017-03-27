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
     * 单个设备下发通知消息
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function PushSingleDeviceNotification($title,$content)
    {
        $mess = new Message();
        $mess->setType(Message::TYPE_NOTIFICATION);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setExpireTime(86400);
        //$style = new Style(0);
        #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
        $style = new Style(0,1,1,0,0);
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_URL);
        $action->setUrl("http://xg.qq.com");
        #打开url需要用户确认
        $action->setComfirmOnUrl(1);
        $custom = array('key1'=>'value1', 'key2'=>'value2');
        $mess->setStyle($style);
        $mess->setAction($action);
        $mess->setCustom($custom);
        $acceptTime1 = new TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime1);
        $ret = $this->push->PushSingleDevice('token', $mess);
        return($ret);
    }

    /**
     * 单个设备下发透传消息   注：透传消息默认不展示
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function PushSingleDeviceMessage($title,$content)
    {
        $mess = new Message();
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setType(Message::TYPE_MESSAGE);
        $ret = $this->push->PushSingleDevice('token', $mess);
        return $ret;
    }

    /**
     * 下发IOS设备消息
     * @return array|mixed
     */
    function PushSingleDeviceIOS()
    {
        $mess = new MessageIOS();
        $mess->setExpireTime(86400);
        //$mess->setSendTime("2014-03-13 16:00:00");
        $mess->setAlert("ios test");
        //$mess->setAlert(array('key1'=>'value1'));
        $mess->setBadge(1);
        $mess->setSound("beep.wav");
        $custom = array('key1'=>'value1', 'key2'=>'value2');
        $mess->setCustom($custom);
        $acceptTime = new TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime);
        $raw = '{"xg_max_payload":1,"accept_time":[{"start":{"hour":"20","min":"0"},"end":{"hour":"23","min":"59"}}],"aps":{"alert":"="}}';
        $mess->setRaw($raw);
        $ret = $this->push->PushSingleDevice('token', $mess, XingeApp::IOSENV_DEV);
        return $ret;
    }

    //setIntent()的内容需要使用intent.toUri(Intent.URI_INTENT_SCHEME)方法来得到序列化后的Intent(自定义参数也包含在Intent内）
    //终端收到后通过intent.parseUri()来反序列化得到Intent
    /**
     * 单个设备下发通知Intent
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function PushSingleDeviceNotificationIntent($title,$content)
    {
        $mess = new Message();
        $mess->setExpireTime(86400);
        $mess->setType(Message::TYPE_NOTIFICATION);
        $mess->setTitle($title);
        $mess->setContent($content);
        $style = new Style(0);
        $style = new Style(0,1,1,0);
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_INTENT);
        $action->setIntent('intent:10086#Intent;scheme=tel;action=android.intent.action.DIAL;S.key=value;end');
        $mess->setStyle($style);
        $mess->setAction($action);
        $ret = $this->push->PushSingleDevice('token', $mess);
        return($ret);
    }


    /**
     * 下发单个账号
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function PushSingleAccount($title,$content)
    {
        $mess = new Message();
        $mess->setExpireTime(86400);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setType(Message::TYPE_MESSAGE);
        $ret = $this->push->PushSingleAccount(0, 'joelliu', $mess);
        return ($ret);
    }

    /**
     * 下发多个账号， IOS下发多个账号参考DemoPushSingleAccountIOS进行相应修改
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function DemoPushAccountList($title,$content)
    {
        $mess = new Message();
        $mess->setExpireTime(86400);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setType(Message::TYPE_MESSAGE);
        $accountList = array('joelliu');
        $ret = $this->push->PushAccountList(0, $accountList, $mess);
        return ($ret);
    }

    /**
     * 下发IOS账号消息
     * @return array|mixed
     */
    function PushSingleAccountIOS()
    {
        $mess = new MessageIOS();
        $mess->setExpireTime(86400);
        $mess->setAlert("ios test");
        //$mess->setAlert(array('key1'=>'value1'));
        $mess->setBadge(1);
        $mess->setSound("beep.wav");
        $custom = array('key1'=>'value1', 'key2'=>'value2');
        $mess->setCustom($custom);
        $acceptTime1 = new TimeInterval(0, 0, 23, 59);
        $mess->addAcceptTime($acceptTime1);
        $ret = $this->push->PushSingleAccount(0, 'joelliu', $mess, XingeApp::IOSENV_DEV);
        return $ret;
    }

    /**
     * 下发所有设备
     * @param $title
     * @param $content
     * @return array|mixed
     */
    function PushAllDevices($title,$content)
    {
        $mess = new Message();
        $mess->setType(Message::TYPE_NOTIFICATION);
        $mess->setTitle($title);
        $mess->setContent($content);
        $mess->setExpireTime(86400);
        $style = new Style(0);
        #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
        $style = new Style(0,1,1,0,0);
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_URL);
        $action->setUrl("http://xg.qq.com");
        #打开url需要用户确认
        $action->setComfirmOnUrl(1);
        $mess->setStyle($style);
        $mess->setAction($action);

        $ret = $this->push->PushAllDevices(0, $mess);
        return ($ret);
    }
}