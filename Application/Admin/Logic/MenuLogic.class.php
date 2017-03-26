<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/16
 * Time: 15:55
 */

namespace Admin\Logic;

use Org\Util\TreeBuild;

class MenuLogic extends BaseLogic{

    /**
     * @var \Admin\Data\MenuData
     */
    protected $menuData;

    public function _initialize(){
        $this->menuData = D('Menu', 'Data');
    }

    /**
     * 获取单条数据
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->menuData->getById($id);
    }

    /**
     * 获取多条数据
     * @param $conditions
     * @param null $pagePara
     * @return mixed
     */
    public function getList($conditions,$pagePara = null){
        return $this->menuData->getList($conditions,$pagePara);
    }

    /**
     * 添加数据
     * @param $data
     * @return bool
     */
    public function saveMenu($data){

        $Menu = D('Menu');

        if($Menu->create($data)){
            $menuId = $Menu->add();
            $menu = $this->menuData->getById($menuId);

            if($menu['parent'] == 0){
                $menu['grade'] = 1;
                $menu['path'] = ',0,'.$menu['id'].',';
            }else{
                $parentMenu = $this->menuData->getById($menu['parent']);
                $menu['grade'] = $parentMenu['grade'] + 1;
                $menu['path'] = $parentMenu['path'].$menu['id'].',';
            }

            return $Menu->save($menu);
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     * @param $data
     * @return bool
     */
    public function editMenu($data){
        $Menu = D('Menu');

        if($Menu->create($data)){
            return $Menu->save();
        }else{
            return false;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return mixed
     */
    public function delMenu($id){
        $Menu = D('Menu');

        return $Menu->delete($id);
    }

    /**
     * 根据用户的角色获取用户的菜单列表
     * @param $id
     * @return mixed
     */
    public function getListByRoleId($id){
        return $this->menuData->getListByRoleId($id);
    }

    /**
     * 根据用户的角色获取用户菜单树形结构
     * @param $id
     * @return mixed
     */
    public function getMenuTreeByRoleId($id){
        $menuList = $this->getListByRoleId($id);

        $TreeBuild = new TreeBuild($menuList);
        $TreeBuild->make();
        $menuTree = $TreeBuild->getResult();

        return $menuTree;
    }

}