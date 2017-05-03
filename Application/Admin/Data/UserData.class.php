<?php

namespace Admin\Data;

class UserData extends BaseData{

    //定义表前缀
    protected $tablePrefix = '';

    /**
     * 获取查询条件
     * @param $conditions
     * @return array
     */
    public function getCondition($conditions){
        $where = array();

        if (isset($conditions['userID']) && !empty($conditions['userID'])) {
            $where['user.userID'] = array('EQ', $conditions['userID']);
        }

        if (isset($conditions['userName']) && !empty($conditions['userName'])) {
            $where['user.userName'] = array('LIKE', '%' . $conditions['userName'] . '%');
        }

        if (isset($conditions['userPhone']) && !empty($conditions['userPhone'])) {
            $where['user.userPhone'] = array('EQ', $conditions['userPhone']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__USERINFORMATION__ AS user')
            ->field('user.*')
            ->where('id=%d',$id)
            ->find();
    }

    /**
     * 多条记录查找
     * @param $conditions
     * @param $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara){

        $where = $this->getCondition($conditions);

        $data = $this->table('__USERINFORMATION__ AS user')
            ->field('user.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }
}