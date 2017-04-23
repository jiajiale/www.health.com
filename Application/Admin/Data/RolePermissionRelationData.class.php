<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


class RolePermissionRelationData extends BaseData{
    //定义表前缀
    protected $tablePrefix = '';

    /**
     * 获取查询条件
     * @param $conditions
     * @return array
     */
    public function getCondition($conditions){
        $where = array();

        if (isset($conditions['id']) && !empty($conditions['id'])) {
            $where['relation.id'] = array('EQ', $conditions['id']);
        }

        if (isset($conditions['role_id']) && !empty($conditions['role_id'])) {
            $where['relation.role_id'] = array('EQ', $conditions['role_id']);
        }

        if (isset($conditions['permission_id']) && !empty($conditions['permission_id'])) {
            $where['relation.permission_id'] = array('EQ', $conditions['permission_id']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__ROLE_PERMISSION_RELATION__ AS relation')
                    ->field('relation.*')
                    ->where('id=%d',$id)
                    ->find();
    }

    /**
     * 多条记录查找
     * @param $conditions
     * @param $pageBounds
     * @return mixed
     */
    public function getList($conditions,$pagePara){

        $where = $this->getCondition($conditions);

        $data = $this->table('__ROLE_PERMISSION_RELATION__ AS relation')
            ->field('relation.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }

    /**
     * 根据角色id查找权限
     * @param $roleId
     * @return mixed
     */
    public function getRolePermissions($roleId){
        $data = $this->table('__ROLE_PERMISSION_RELATION__ AS relation')
            ->field('relation.*,permission.code')
            ->join('__PERMISSION__ AS permission ON permission.id = relation.permission_id')
            ->where('role_id = %d',$roleId)
            ->select();

        return $data;
    }

}