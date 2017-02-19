<?php

namespace Admin\Controller;

class LogController extends BaseController{


    /**
     * @var \Admin\Logic\LogLogic
     */
    protected $logLogic;

    public function _initialize(){
        $this->logLogic = D('Log', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $logList = $this->logLogic->getList($conditions,$pagePara);

        $this->assign("list",$logList['items']);
        $this->assign("pager",$logList['pager']);
        $this->assign("params",$conditions);
        $this->display();
    }

    /**
     * 添加视图
     */
    public function add(){
        $this->display();
    }

    /**
     * 编辑视图
     */
    public function edit($id){
        $log = $this->logLogic->getById($id);

        $this->assign("log",$log);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($id){
        $log = $this->logLogic->getById($id);

        $this->assign("log",$log);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->logLogic->saveLog($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->logLogic->editLog($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->logLogic->delLog($id);

        $this->ajaxAuto($result,'删除');
    }
}
