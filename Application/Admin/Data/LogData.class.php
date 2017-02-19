<?php

namespace Admin\Data;

class LogData extends BaseData{

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
            $where['log.id'] = array('EQ', $conditions['id']);
        }
        if (isset($conditions['account_type']) && !empty($conditions['account_type'])) {
            $where['log.account_type'] = array('EQ', $conditions['account_type']);
        }
        if (isset($conditions['account_id']) && !empty($conditions['account_id'])) {
            $where['log.account_id'] = array('EQ', $conditions['account_id']);
        }
        if (isset($conditions['operate_name']) && !empty($conditions['operate_name'])) {
            $where['log.operate_name'] = array('EQ', $conditions['operate_name']);
        }
        if (isset($conditions['operate_params']) && !empty($conditions['operate_params'])) {
            $where['log.operate_params'] = array('EQ', $conditions['operate_params']);
        }
        if (isset($conditions['operate_url']) && !empty($conditions['operate_url'])) {
            $where['log.operate_url'] = array('EQ', $conditions['operate_url']);
        }
        if (isset($conditions['ip']) && !empty($conditions['ip'])) {
            $where['log.ip'] = array('EQ', $conditions['ip']);
        }
        if (isset($conditions['target']) && !empty($conditions['target'])) {
            $where['log.target'] = array('EQ', $conditions['target']);
        }
        if (isset($conditions['operate']) && !empty($conditions['operate'])) {
            $where['log.operate'] = array('EQ', $conditions['operate']);
        }
        if (isset($conditions['gmt_operate']) && !empty($conditions['gmt_operate'])) {
            $where['log.gmt_operate'] = array('EQ', $conditions['gmt_operate']);
        }
        if (isset($conditions['status']) && !empty($conditions['status'])) {
            $where['log.status'] = array('EQ', $conditions['status']);
        }
        if (isset($conditions['note']) && !empty($conditions['note'])) {
            $where['log.note'] = array('EQ', $conditions['note']);
        }
        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__LOG__ AS log')
                    ->field('log.*')
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

        $data = $this->table('__LOG__ AS log')
            ->field('log.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }
}