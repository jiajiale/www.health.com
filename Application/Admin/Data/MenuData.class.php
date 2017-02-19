<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


class MenuData extends BaseData{
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
            $where['menu.id'] = array('EQ', $conditions['id']);
        }

        if (isset($conditions['name']) && !empty($conditions['name'])) {
            $where['menu.name'] = array('EQ', $conditions['name']);
        }

        if (isset($conditions['parent']) && !empty($conditions['parent'])) {
            $where['menu.parent'] = array('EQ', $conditions['parent']);
        }else{
            $where['menu.parent'] = array('EQ', 0);
        }

        if (isset($conditions['path']) && !empty($conditions['path'])) {
            $where['menu.path'] = array('EQ', $conditions['path']);
        }

        if (isset($conditions['grade']) && !empty($conditions['grade'])) {
            $where['menu.grade'] = array('EQ', $conditions['grade']);
        }

        if (isset($conditions['url']) && !empty($conditions['url'])) {
            $where['menu.url'] = array('EQ', $conditions['url']);
        }

        if (isset($conditions['sort']) && !empty($conditions['sort'])) {
            $where['menu.sort'] = array('EQ', $conditions['sort']);
        }

        if (isset($conditions['status']) && !empty($conditions['status'])) {
            $where['menu.status'] = array('EQ', $conditions['status']);
        }

        return $where;
    }

    /**
     * 单条记录查找
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->table('__MENU__ AS menu')
                    ->field('menu.*')
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

        $data = $this->table('__MENU__ AS menu')
            ->field('menu.*')
            ->where($where)
            ->page($pagePara->pageIndex, $pagePara->pageSize)
            ->selectPage();

        return $data;
    }

    /**
     * 根据角色id查询菜单项
     * @param $id
     * @return mixed
     */
    public function getListByRoleId($id){
        $sql = "SELECT DISTINCT
                    menu.*
                FROM
                    st_common_menu AS menu,
                    st_common_menu AS menu1
                        INNER JOIN
                    st_common_permission AS permission ON menu1.code = permission.code
                        INNER JOIN
                    st_common_role_permission_relation AS relation ON relation.permission_id = permission.id
                        INNER JOIN
                    st_common_role AS role ON role.id = relation.role_id
                WHERE
                    menu1.path LIKE CONCAT(menu.path,'%')
                AND relation.role_id = " . $id ."
                ORDER BY menu.sort ASC
                ";

        $data = $this->query($sql);
        return $data;
    }
}