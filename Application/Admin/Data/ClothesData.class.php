<?php

namespace Admin\Data;

class ClothesData extends BaseData{

    //定义表前缀
    protected $tablePrefix = '';

    /**
     * 获取查询条件
     * @param $conditions
     * @return array
     */
    public function getCondition($conditions){
        $where = array();

        return $where;
    }

    /**
     * 单条记录查找
     * @param $clothesID
     * @return mixed
     */
    public function getById($clothesID){
        return $this->table('__CLOTHESINFORMATION__ AS clothes')
            ->field('clothes.*')
            ->where('clothesID=%d',$clothesID)
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

        $data = $this->table('__CLOTHESINFORMATION__ AS clothes')
            ->field('clothes.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }
}