<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


class PermissionData extends BaseData{
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
            $where['permission.id'] = array('EQ', $conditions['id']);
        }

        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $where['permission.name'] = array('LIKE', '%' . $conditions['name'] . '%');
        }

        if (isset($conditions['status']) && !empty($conditions['status'])) {
            $where['permission.status'] = array('EQ', $conditions['status']);
        }

        if (isset($conditions['code']) && !empty($conditions['code'])) {
            $where['permission.code'] = array('EQ', $conditions['code']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__PERMISSION__ AS permission')
                    ->field('permission.*')
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

        $data = $this->table('__PERMISSION__ AS permission')
            ->field('permission.*')
            ->where($where)
            ->order('id DESC')
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }

    /**
     * 根据模块名获取权限列表
     * @param $module
     * @return mixed
     */
    public function getPermissionsByModule($module){
        $data = $this->table('__PERMISSION__ AS permission')
            ->field('permission.*')
            ->where("permission.module = '%s'",$module)
            ->select();

        return $data;
    }

}