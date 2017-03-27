<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/6
 * Time: 21:13
 */
namespace Admin\Controller;



class RolePermissionRelationController extends BaseController{


    /**
     * @var \Admin\Logic\RolePermissionRelationLogic
     */
    protected $rolePermissionRelationLogic;

    public function _initialize(){
        $this->rolePermissionRelationLogic = D('RolePermissionRelation', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $rolePermissionRelationList = $this->rolePermissionRelationLogic->getList($conditions,$pagePara);

        $this->assign("list",$rolePermissionRelationList['items']);
        $this->assign("pager",$rolePermissionRelationList['pager']);
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
        $rolePermissionRelation = $this->rolePermissionRelationLogic->getById($id);

        $this->assign("rolePermissionRelation",$rolePermissionRelation);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($id){
        $rolePermissionRelation = $this->rolePermissionRelationLogic->getById($id);

        $this->assign("rolePermissionRelation",$rolePermissionRelation);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->rolePermissionRelationLogic->saveRolePermissionRelation($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->rolePermissionRelationLogic->editRolePermissionRelation($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->rolePermissionRelationLogic->delRolePermissionRelation($id);

        $this->ajaxAuto($result,'删除');
    }
}