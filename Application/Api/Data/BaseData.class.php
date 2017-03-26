<?php
namespace Api\Data;

use Api\Model\BaseModel;
use Common\Util\Page;
use Think\Model;

/**
 * 基础数据层
 * 所有数据层模型都需要继承此模型
 */
class BaseData extends BaseModel
{
    protected $autoCheckFields=false;

    /**
     * 获取分页数据
     * @return Page
     */
    public function selectPage()
    {
        $options = $this->options;
        $page = new Page();
        $pagePara = $options['page'];
        $page->setPageIndex($pagePara[0]);
        $page->setPageSize($pagePara[1]);
        $data = $this->select();
        if ($data) {
            $page->setItems($data);
        } else {
            return false;
        }

        //统计数量
        $options['page'] = null; //去除分页条件
        $options['order'] = null; //去除排序
        $sql = ($this->buildSql($options));
        $sql = 'SELECT COUNT(*) AS item_count FROM' . $sql . 'count_temp';
        $result = $this->query($sql);
        if($result){
            $page->setItemsCount((int)$result[0]['item_count']);
        }else{
            $page->setItemsCount(0);
        }

        $page->calcTotalPages(); //计算分页数
        return $page;
    }

    /**
     * 指定查询字段 支持字段排除  重写
     * @access public
     * @param mixed $field
     * @param boolean $except 是否排除
     * @return Model
     */
    public function field($field, $except = false)
    {
        if (true === $field) { // 获取全部字段
            $fields = $this->getDbFields();
            $field = $fields ? : '*';
        } elseif ($except) { // 字段排除
            if (is_string($field)) {
                $field = explode(',', $field);
            }
            $fields = $this->getDbFields();
            $field = $fields ? array_diff($fields, $field) : $field;
        }
        if (is_string($field)) {
            $field = explode(',', $field);
        }
        if (isset($this->options['field'])) {
            $this->options['field'] = array_merge($this->options['field'], $field);
        } else {
            $this->options['field'] = $field;
        }
        return $this;
    }
}
