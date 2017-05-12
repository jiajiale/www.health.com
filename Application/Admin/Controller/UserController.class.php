<?php

namespace Admin\Controller;

class UserController extends BaseController{


    /**
     * @var \Admin\Logic\UserLogic
     */
    protected $userLogic;

    public function _initialize(){
        $this->userLogic = D('User', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $userList = $this->userLogic->getList($conditions,$pagePara);

        $this->assign("list",$userList['items']);
        $this->assign("pager",$userList['pager']);
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
    public function edit($userID){
        $user = $this->userLogic->getById($userID);

        $this->assign("user",$user);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($userID){
        $user = $this->userLogic->getById($userID);

        $this->assign("user",$user);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->userLogic->saveUser($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->userLogic->editUser($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->userLogic->delUser($id);

        $this->ajaxAuto($result,'删除');
    }
}