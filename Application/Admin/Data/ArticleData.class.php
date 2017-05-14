<?php

namespace Admin\Data;

class ArticleData extends BaseData{

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

        return $where;
    }

    public function getSayTextCondition($conditions){
        $where = array();

        if (isset($conditions['userID']) && !empty($conditions['userID'])) {
            $where['user.userID'] = array('EQ', $conditions['userID']);
        }

        if (isset($conditions['sayID']) && !empty($conditions['sayID'])) {
            $where['sayText.sayID'] = array('EQ', $conditions['sayID']);
        }

        if (isset($conditions['userName']) && !empty($conditions['userName'])) {
            $where['user.userName'] = array('LIKE', '%' . $conditions['userName'] . '%');
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $sayID
     * @return mixed
     */
    public function getById($sayID){
        return $this->table('__SAYTABLE__ AS say')
            ->field('say.*,user.userName')
            ->join('__USERINFORMATION__ AS user ON user.userID = say.userID')
            ->where('say.sayID=%d',$sayID)
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

        $data = $this->table('__SAYTABLE__ AS say')
            ->field('say.*,user.userName')
            ->join('__USERINFORMATION__ AS user ON user.userID = say.userID')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->order('say.sayTime DESC')
            ->selectPage();

        return $data;
    }

    /**
     * 获取动态的评论
     * @param $conditions
     * @param $pagePara
     * @return mixed
     */
    public function getSayTextList($conditions,$pagePara){
        $where = $this->getSayTextCondition($conditions);

        $data = $this->table('__SAYTEXTTABLE__ AS sayText')
            ->field('sayText.*,user.userName')
            ->join('__USERINFORMATION__ AS user ON user.userID = sayText.userID')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->order('sayText.textTime DESC')
            ->selectPage();

        return $data;
    }
}