<?php
namespace Home\Controller;
use Common\Util\Xinge;
use Think\Controller;
use XingeApp;

class IndexController extends Controller {
    public function index(){
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $Xinge = new Xinge();



        $result = XingeApp::PushTokenIos(2200252500,'8154cb02e43dce0dc9052facbe19aa96',"dsfgfdgd",'33c4c5532e6233d14b51b94fbdb3f3c3b6590443f07d4bf5a65b47b9bca70fda', XingeApp::IOSENV_DEV);
    }
}