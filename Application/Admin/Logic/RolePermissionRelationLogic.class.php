<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;


class RolePermissionRelationLogic extends BaseLogic{

    /**
     * @var \Admin\Data\RolePermissionRelationData
     */
    protected $rolePermissionRelationData;

    public function _initialize(){
        $this->rolePermissionRelationData = D('RolePermissionRelation', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->rolePermissionRelationData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->rolePermissionRelationData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveRolePermissionRelation($data){

        $RolePermissionRelation = D('RolePermissionRelation');

        if($RolePermissionRelation->create($data)){
            return $RolePermissionRelation->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editRolePermissionRelation($data){
        $RolePermissionRelation = D('RolePermissionRelation');

        if($RolePermissionRelation->create($data)){
            return $RolePermissionRelation->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delRolePermissionRelation($id){
        $RolePermissionRelation = D('RolePermissionRelation');

        return $RolePermissionRelation->delete($id);
    }

    /**
     * 根据角色id查找角色权限
     * @param $roleId
     * @return mixed
     */
    public function getRolePermissions($roleId){
        return $this->rolePermissionRelationData->getRolePermissions($roleId);
    }

    /**
     * 根据角色id删除角色权限
     * @param $roleId
     * @return mixed
     */
    public function deleteRolePermissions($roleId){
        $RolePermissionRelation = D('RolePermissionRelation');

        return $RolePermissionRelation->where('role_id = %d',$roleId)->delete();
    }
}