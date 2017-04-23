<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
        //$Xinge = new Xinge();
        //$result = XingeApp::PushTokenIos(2200252500,'8154cb02e43dce0dc9052facbe19aa96',"dsfgfdgd",'33c4c5532e6233d14b51b94fbdb3f3c3b6590443f07d4bf5a65b47b9bca70fda', XingeApp::IOSENV_DEV);
        $this->display();
    }
}