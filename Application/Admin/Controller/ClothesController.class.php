<?php

namespace Admin\Controller;

class ClothesController extends BaseController{


    /**
     * @var \Admin\Logic\ClothesLogic
     */
    protected $clothesLogic;

    public function _initialize(){
        $this->clothesLogic = D('Clothes', 'Logic');
    }

    /**
     * 数据列表
     */
    public function index(){
        $conditions = $this->getAvailableData();
        $pagePara = get_page_para();
        $clothesList = $this->clothesLogic->getList($conditions,$pagePara);

        $this->assign("list",$clothesList['items']);
        $this->assign("pager",$clothesList['pager']);
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
        $clothes = $this->clothesLogic->getById($id);

        $this->assign("clothes",$clothes);
        $this->display();
    }

    /**
     * 查看视图
     */
    public function detail($id){
        $clothes = $this->clothesLogic->getById($id);

        $this->assign("clothes",$clothes);
        $this->display();
    }

    /**
     * 添加操作
     */
    public function do_add(){
        $data = $this->getAvailableData();
        $result = $this->clothesLogic->saveClothes($data);

        $this->ajaxAuto($result,'添加');
    }

    /**
     * 编辑操作
     */
    public function do_edit(){
        $data = $this->getAvailableData();
        $result = $this->clothesLogic->editClothes($data);

        $this->ajaxAuto($result,'修改');
    }

    /**
     * 删除操作
     */
    public function do_del($id){
        $result = $this->clothesLogic->delClothes($id);

        $this->ajaxAuto($result,'删除');
    }
}
