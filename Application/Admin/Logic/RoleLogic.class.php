<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;


class RoleLogic extends BaseLogic{

    /**
     * @var \Admin\Data\RoleData
     */
    protected $roleData;
    /**
     * @var \Admin\Logic\RolePermissionRelationLogic
     */
    protected $rolePermissionRelationLogic;

    public function _initialize(){
        $this->roleData = D('Role', 'Data');
        $this->rolePermissionRelationLogic = D('RolePermissionRelation', 'Logic');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->roleData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->roleData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveRole($data){

        $Role = D('Role');
        $RolePermissionRelation = D('RolePermissionRelation');

        if($Role->create($data)){
            $roleId = $Role->add();

            // 批量插入
            $map = array();
            foreach($data['permissions'] as $val){
                $map[] = array(
                    'role_id'  =>  $roleId,
                    'permission_id'  =>  $val
                );
            }

            return $RolePermissionRelation->addAll($map);
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editRole($data){
        $Role = D('Role');
        $RolePermissionRelation = D('RolePermissionRelation');

        if($Role->create($data)){
            $result = $Role->save();

            if($result !== false){
                $roleId = $data['id'];

                // 删除以前的权限关联
                $this->rolePermissionRelationLogic->deleteRolePermissions($roleId);

                // 批量插入
                $map = array();
                foreach($data['permissions'] as $val){
                    $map[] = array(
                        'role_id'  =>  $roleId,
                        'permission_id'  =>  $val
                    );
                }

                return $RolePermissionRelation->addAll($map);
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delRole($id){
        $Role = D('Role');

        return $Role->delete($id);
    }

}