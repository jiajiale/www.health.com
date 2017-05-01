<?php

namespace Admin\Logic;

class ArticleLogic extends BaseLogic{

    /**
     * @var \Admin\Data\ArticleData
     */
    protected $articleData;

    public function _initialize(){
        $this->articleData = D('Article', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->articleData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->articleData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveArticle($data){

        $Article = D('Articleinformation');

        if($Article->create($data)){
            return $Article->add();
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editArticle($data){
        $Article = D('Articleinformation');

        if($Article->create($data)){
            return $Article->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delArticle($id){
        $Article = D('Articleinformation');

        return $Article->delete($id);
    }
}