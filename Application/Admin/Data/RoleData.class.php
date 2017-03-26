<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


class RoleData extends BaseData{
    //定义表前缀
    protected $tablePrefix = 'st_common_';

    /**
     * 获取查询条件
     * @param $conditions
     * @return array
     */
    public function getCondition($conditions){
        $where = array();

        if (isset($conditions['id']) && !empty($conditions['id'])) {
            $where['role.id'] = array('EQ', $conditions['id']);
        }

        if (isset($conditions['type']) && !empty($conditions['type'])) {
            $where['role.type'] = array('EQ', $conditions['type']);
        }

        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $where['role.name'] = array('EQ', $conditions['name']);
        }

        if (isset($conditions['status']) && !empty($conditions['status'])) {
            $where['role.status'] = array('EQ', $conditions['status']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__ROLE__ AS role')
                    ->field('role.*')
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

        $data = $this->table('__ROLE__ AS role')
            ->field('role.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }

}