<?php

namespace Admin\Controller;

class ArticleController extends BaseController{


    /**
     * @var \Admin\Logic\ArticleLogic
     */
    protected $articleLogic;

    public function _initialize(){
        $this->articleLogic = D('Article', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $articleList = $this->articleLogic->getList($conditions,$pagePara);

        $this->assign("list",$articleList['items']);
        $this->assign("pager",$articleList['pager']);
        $this->assign("params",$conditions);
        $this->display();
    }

    /**
     * 添加视图
     */
    public function add(){
        $this->display();
    }

    /**
     * 编辑视图
     */
    public function edit($id){
        $article = $this->articleLogic->getById($id);

        $this->assign("article",$article);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($sayID){
        $article = $this->articleLogic->getById($sayID);

        $conditions = $this->getAvailableData();
        $conditions['sayID'] = $sayID;
        $pagePara = get_page_para();

        $commentList = $this->articleLogic->getSayTextList($conditions,$pagePara);
        $this->assign("list",$commentList['items']);
        $this->assign("pager",$commentList['pager']);
        $this->assign("params",$conditions);

        $this->assign("article",$article);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->articleLogic->saveArticle($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->articleLogic->editArticle($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($sayID){
        $result = $this->articleLogic->delArticle($sayID);

        $this->ajaxAuto($result,'删除');
    }

    /**
     * 删除动态评论
     * @param $number
     */
    public function do_del_comment($number){
        $result = $this->articleLogic->delArticleComment($number);

        $this->ajaxAuto($result,'删除');
    }
}
