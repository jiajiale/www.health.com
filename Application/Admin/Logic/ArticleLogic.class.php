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
     * @param $sayID
     * @return mixed
     */
    public function getById($sayID){
        return $this->articleData->getById($sayID);
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
     * 获取动态的评论
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getSayTextList($conditions,$pagePara = null){
        return $this->articleData->getSayTextList($conditions,$pagePara);
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
     * @param $sayID
     * @return mixed
     */
    public function delArticle($sayID){
        $Saytexttable = D('Saytexttable');
        $Saytable = D('Saytable');
        $Daytask = D('Daytask');
        $Achiveprogress = D('Achiveprogress');

        $Saytexttable->startTrans();
        $userID = $Saytexttable->where("sayID = %d",$sayID)->getField('userID');

        $flag1 = $Saytexttable->where('sayID = %d',$sayID)->delete();
        $flag2 = $Saytable->where('sayID = %d',$sayID)->delete();

        if($flag1 !== false && $flag2 !== false){
            $Daytask->where('userID = %d',$userID)->setDec('dayRelease');
            $Achiveprogress->where('userID = %d',$userID)->setDec('`Release`');

            $Saytexttable->commit();
            return true;
        }else{
            $Saytexttable->rollback();
            return false;
        }
    }

    /**
     * 删除评论
     * @param $number
     * @return bool
     */
    public function delArticleComment($number){
        $Saytexttable = D('Saytexttable');
        $Daytask = D('Daytask');
        $Achiveprogress = D('Achiveprogress');

        $userID = $Saytexttable->where("number = %d",$number)->getField('userID');

        $Daytask->where('userID = %d',$userID)->setDec('daycomment');
        $Achiveprogress->where('userID = %d',$userID)->setDec('comment');

        return $Saytexttable->where("number = %d",$number)->delete();
    }
}