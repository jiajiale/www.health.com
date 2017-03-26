<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/6
 * Time: 21:13
 */
namespace Admin\Controller;



class RoleController extends BaseController{


    /**
     * @var \Admin\Logic\RoleLogic
     */
    protected $roleLogic;
    /**
     * @var \Admin\Logic\PermissionLogic
     */
    protected $permissionLogic;
    /**
     * @var \Admin\Logic\RolePermissionRelationLogic
     */
    protected $rolePermissionRelationLogic;

    public function _initialize(){
        $this->roleLogic = D('Role', 'Logic');
        $this->permissionLogic = D('Permission', 'Logic');
        $this->rolePermissionRelationLogic = D('RolePermissionRelation', 'Logic');
    }


    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $roleList = $this->roleLogic->getList($conditions,$pagePara);

        $this->assign("list",$roleList['items']);
        $this->assign("pager",$roleList['pager']);
        $this->assign("params",$conditions);
        $this->display();
    }

    /**
     * 添加视图
     */
    public function add(){
        $modules = $this->permissionLogic->getModules();
        $modules_list = array();

        foreach($modules as $key => $val){
            $modules_list[] = array(
                'module_key'  => $key,
                'module_val'  => $val,
                'permissions' => $this->permissionLogic->getPermissionsByModule($val)
            );
        }

        $this->assign('modules_list',$modules_list);
        $this->display();
    }

    /**
     * 编辑视图
     */
    public function edit($id){
        // 获取用户的权限
        $role_permissions = get_array_column(
            $this->rolePermissionRelationLogic->getRolePermissions($id),'permission_id');

        // 获取所有的模块
        $modules = $this->permissionLogic->getModules();
        $role = $this->roleLogic->getById($id);

        $modules_list = array();

        foreach($modules as $key => $val){
            $permissions = $this->permissionLogic->getPermissionsByModule($val);

            foreach($permissions as $itemKey => $itemVal){
                if(in_array($itemVal['id'],$role_permissions)){
                    $permissions[$itemKey]['checked'] = 'checked';
                }else{
                    $permissions[$itemKey]['checked'] = '';
                }
            }

            $flag = count(array_diff(get_array_column($permissions,'id'),$role_permissions));

            $modules_list[] = array(
                'module_key'  => $key,
                'module_val'  => $val,
                'permissions' => $permissions,
                'checked'     => $flag ? '' : 'checked'
            );
        }

        $this->assign('role',$role);
        $this->assign('modules_list',$modules_list);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($id){
        $role = $this->roleLogic->getById($id);

        $this->assign("permission",$role);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->roleLogic->saveRole($data);

        $this->ajaxAuto($result,'添加',U('role/index'));
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->roleLogic->editRole($data);

        $this->ajaxAuto($result,'修改',U('role/index'));
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->roleLogic->delRole($id);

        $this->ajaxAuto($result,'删除');
    }
}