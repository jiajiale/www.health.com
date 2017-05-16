<?php
/**
 * Created by PhpStorm.
 * User: KU04PC
 * Date: 2015/5/11
 * Time: 21:07
 */

namespace Admin\Data;


use Admin\Model\BaseModel;

class BaseData extends BaseModel{

    protected $autoCheckFields=false;
    /**
     * 获取分页数据
     * @return Page
     */
    public function selectPage()
    {
        $options = $this->options;
        $pagePara = $options['page'];
        $page['pager']['index'] = $pagePara[0];
        $page['pager']['size'] = $pagePara[1];
        $data = $this->select();
        if ($data) {
            $page['items'] = $data;
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
            $page['pager']['total'] = (int)$result[0]['item_count'];
        }else{
            $page['pager']['total'] = 0;
        }

        if (!$page['pager']['size']) {
            $page['pager']['size'] = 20;
        }
        $page['pager']['count'] = ceil($page['pager']['total'] / $page['pager']['size']); //计算分页数
        return $page;
    }
}