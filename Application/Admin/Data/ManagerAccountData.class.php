<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


class ManagerAccountData extends BaseData{
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
            $where['manager_account.id'] = array('EQ', $conditions['id']);
        }

        if (isset($conditions['username']) && !empty($conditions['username'])) {
            $where['manager_account.username'] = array('EQ', $conditions['username']);
        }

        if (isset($conditions['password']) && !empty($conditions['password'])) {
            $where['manager_account.password'] = array('EQ', $conditions['password']);
        }

        if (isset($conditions['gmt_create']) && !empty($conditions['gmt_create'])) {
            $where['manager_account.gmt_create'] = array('EQ', $conditions['gmt_create']);
        }

        if (isset($conditions['gmt_last_login']) && !empty($conditions['gmt_last_login'])) {
            $where['manager_account.gmt_last_login'] = array('EQ', $conditions['gmt_last_login']);
        }

        if (isset($conditions['last_ip']) && !empty($conditions['last_ip'])) {
            $where['manager_account.last_ip'] = array('EQ', $conditions['last_ip']);
        }

        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $where['manager_account.name'] = array('EQ', $conditions['name']);
        }

        if (isset($conditions['mobile']) && !empty($conditions['mobile'])) {
            $where['manager_account.mobile'] = array('EQ', $conditions['mobile']);
        }

        if (isset($conditions['role_id']) && !empty($conditions['role_id'])) {
            $where['manager_account.role_id'] = array('EQ', $conditions['role_id']);
        }

        if (isset($conditions['status']) && !empty($conditions['status'])) {
            $where['manager_account.status'] = array('EQ', $conditions['status']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__MANAGER_ACCOUNT__ AS manager_account')
                    ->field('manager_account.*')
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

        $data = $this->table('__MANAGER_ACCOUNT__ AS manager_account')
            ->field('manager_account.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }

}