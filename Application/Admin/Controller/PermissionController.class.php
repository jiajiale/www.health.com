<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/6
 * Time: 21:13
 */
namespace Admin\Controller;



class PermissionController extends BaseController{


    /**
     * @var \Admin\Logic\PermissionLogic
     */
    protected $permissionLogic;

    public function _initialize(){
        $this->permissionLogic = D('Permission', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $permissionList = $this->permissionLogic->getList($conditions,$pagePara);

        $this->assign("list",$permissionList['items']);
        $this->assign("pager",$permissionList['pager']);
        $this->assign("params",$conditions);
        $this->display();
    }

    /**
     * 添加视图
     */
    public function add(){
        $modules = $this->permissionLogic->getModules();

        $this->assign('modules',$modules);
        $this->display();
    }

    /**
     * 编辑视图
     */
    public function edit($id){
        $permission = $this->permissionLogic->getById($id);
        $modules = $this->permissionLogic->getModules();

        $this->assign('modules',$modules);
        $this->assign("permission",$permission);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($id){
        $permission = $this->permissionLogic->getById($id);

        $this->assign("permission",$permission);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->permissionLogic->savePermission($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->permissionLogic->editPermission($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->permissionLogic->delPermission($id);

        $this->ajaxAuto($result,'删除');
    }
}