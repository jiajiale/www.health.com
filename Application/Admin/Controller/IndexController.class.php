<?php
namespace Admin\Controller;


class IndexController extends BaseController {

    /**
     * 主页视图
     */
    public function index(){
        $this->display();
    }

    /**
     * 菜单视图
     */
    public function left(){
        $this->display();
    }

    /**
     * 顶部视图
     */
    public function top(){
        $this->display();
    }

    /**
     * 内容区视图
     */
    public function main(){
        $this->display();
    }

}