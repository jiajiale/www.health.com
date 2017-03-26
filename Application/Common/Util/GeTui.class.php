<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/28
 * Time: 14:20
 */

namespace Common\Util;

use DictionaryAlertMsg;
use IGeTui;
use IGtAPNPayload;
use IGtAppMessage;
use IGtLinkTemplate;
use IGtListMessage;
use IGtNotificationTemplate;
use IGtNotyPopLoadTemplate;
use IGtSingleMessage;
use IGtTarget;
use IGtTransmissionTemplate;
use RequestException;

vendor('GeTui.GeTui');

class GeTui {

    private $igt;

    private $config;

    public function __construct(){
        $this->config = C('GETUI');
        $this->igt = new IGeTui($this->config['HOST'],$this->config['APPKEY'],$this->config['MASTERSECRET']);
    }

    /**
     * 单个用户推送接口
     */
    public function pushMessageToSingle(){
        $template = $this->IGtNotificationTemplate('测试标题','测试内容');

/*        $template = $this->IGtNotyPopLoadTemplate(array(
            'title' => '测试标题',
            'content' => '测试内容',
            'icon' => ''
        ),array(
            'title' => '测试标题',
            'content' => '测试内容',
            'image' => '',
            'ok' => '确认',
            'cancel' => '取消'
        ),array(
            'icon' => '',
            'title' => '测试标题',
            'url' => 'http://www.baidu.com',
        ));*/

        //个推信息体
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600*12*1000);
        $message->set_data($template);
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        //接收方
        $target = new IGtTarget();
        $target->set_appId($this->config['APPID']);
        $target->set_clientId($this->config['CID']);


        try {
            $this->igt->pushMessageToSingle($message, $target);
        }catch(RequestException $e){
            $requstId = $e.getRequestId();
            $this->igt->pushMessageToSingle($message, $target,$requstId);
        }

    }

    /**
     * 多用户推送接口
     * @param $title
     * @param $content
     * @param $clients
     * @throws \Exception
     */
    public function pushMessageToList($title,$content,$clients,$appId){
        putenv("gexin_pushList_needDetails=true");
        putenv("gexin_pushList_needAsync=true");

        //$content = '拼车吧-' . $content;
        if(substr($appId,0,2) == '10'){
            // IOS设备采用透传模板
            $template = $this->IGtTransmissionTemplate($title,$content);
        }else{
            // Android设备采用通知模板
            $template = $this->IGtNotificationTemplate($title,$content);
        }

        //个推信息体
        $message = new IGtListMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600 * 12 * 1000);
        $message->set_data($template);

        $contentId = $this->igt->getContentId($message,"toList任务别名功能");

        $targetList = array();
        //接收方
        foreach($clients as $key => $val){
            $target = new IGtTarget();
            $target->set_appId($this->config['APPID']);
            $target->set_clientId($val);
            $targetList[] = $target;
        }

        $this->igt->pushMessageToList($contentId, $targetList);

    }

    /**
     * 应用群推接口
     */
    public function pushMessageToApp(){

        $template = $this->IGtNotificationTemplate('测试群推','测试群推内容');

        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();

        $message->set_isOffline(true);
        $message->set_offlineExpireTime(3600*12*1000);
        $message->set_data($template);

        $message->set_appIdList(array($this->config['APPID']));

        $this->igt->pushMessageToApp($message,'toApp任务别名');

    }


    /**
     * 透传消息模板
     * @param $title
     * @param $content
     * @return IGtTransmissionTemplate
     * @throws \Exception
     * @internal param $title
     */
    public function IGtTransmissionTemplate($title,$content){
        $template =  new IGtTransmissionTemplate();
        $template->set_appId($this->config['APPID']);
        $template->set_appkey($this->config['APPKEY']);
        $template->set_transmissionType(1);
        $template->set_transmissionContent($content);

        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body=$content;
        //IOS8.2 支持
        $alertmsg->title=$title;

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        //$apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        //$apn->add_customMsg('key','senthink-shareCar');
        $template->set_apnInfo($apn);

        return $template;
    }

    /**
     * 点击通知打开应用模板(IOS不推荐)
     * @param $title
     * @param $text
     * @param string $content
     * @param string $logo
     * @return IGtNotificationTemplate
     */
    public function IGtNotificationTemplate($title,$text,$content='',$logo=''){
        $template =  new IGtNotificationTemplate();
        $template->set_appId($this->config['APPID']);
        $template->set_appkey($this->config['APPKEY']);
        $template->set_transmissionType(2);
        $template->set_transmissionContent($content);
        $template->set_title($title);
        $template->set_text($text);
        $template->set_logo($logo);
        $template->set_isRing(true);
        $template->set_isVibrate(true);
        $template->set_isClearable(true);

        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body=$content;
        //IOS8.2 支持
        $alertmsg->title=$title;

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->sound="";
        //$apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;
    }

    /**
     * 点击通知打开网页模板(IOS不推荐)
     * @param $title
     * @param $text
     * @param string $logo
     * @param string $link
     * @return IGtLinkTemplate
     */
    public function IGtLinkTemplate($title,$text,$logo='',$link=''){
        $template =  new IGtLinkTemplate();
        $template ->set_appId($this->config['APPID']);
        $template ->set_appkey($this->config['APPKEY']);
        $template ->set_title($title);
        $template ->set_text($text);
        $template ->set_logo($logo);
        $template ->set_isRing(true);
        $template ->set_isVibrate(true);
        $template ->set_isClearable(true);
        $template ->set_url($link);
        return $template;
    }

    /**
     * 点击通知栏弹框下载模版(IOS不支持)
     * @param $noty
     * @param $pop
     * @param $load
     * @return IGtNotyPopLoadTemplate
     */
    function IGtNotyPopLoadTemplate($noty,$pop,$load){
        $template =  new IGtNotyPopLoadTemplate();

        $template ->set_appId($this->config['APPID']);
        $template ->set_appkey($this->config['APPKEY']);
        //通知栏
        $template ->set_notyTitle($noty['title']);
        $template ->set_notyContent($noty['content']);
        $template ->set_notyIcon($noty['icon']);
        $template ->set_isBelled(true);
        $template ->set_isVibrationed(true);
        $template ->set_isCleared(true);
        //弹框
        $template ->set_popTitle($pop['title']);
        $template ->set_popContent($pop['content']);
        $template ->set_popImage($pop['image']);
        $template ->set_popButton1($pop['ok']);
        $template ->set_popButton2($pop['cancel']);
        //下载
        $template ->set_loadIcon($load['icon']);
        $template ->set_loadTitle($load['title']);
        $template ->set_loadUrl($load['url']);
        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);

        return $template;
    }

    /**
     * 用户状态查询
     * @param $cid
     * @return mixed|null
     */
    public function getUserStatus($cid) {
        return $this->igt->getClientIdStatus($this->config['APPID'],$cid);
    }

    /**
     * 推送任务停止
     * @param $taskId
     */
    public function stoptask($taskId){
        $this->igt->stop($taskId);
    }

    /**
     * 通过服务端设置CID
     * @param $cid
     * @param array $tagList
     * @return mixed|null
     */
    public function setTag($cid,$tagList = array()){
        return $this->igt->setClientTag($this->config['APPID'],$cid,$tagList);

    }
}