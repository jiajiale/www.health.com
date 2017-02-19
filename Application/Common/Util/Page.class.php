<?php

namespace Common\Util;

/**
 * Class Page
 * @package Common\Util
 */
class Page
{

    public $pageIndex;
    public $totalPages;
    public $itemsCount;
    public $pageSize;
    public $items;


    /**
     * @param $pageIndex
     * @param $pageSize
     * @param $items
     * @param $itemsCount
     */
    public function __construct($pageIndex=0,$pageSize=0,$items=0,$itemsCount=0){
        $this->items=$items;
        $this->pageIndex=$pageIndex;
        $this->itemsCount=$itemsCount;
        $this->pageSize=$pageSize;
        $this->calcTotalPages();
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $pageIndex
     */
    public function setPageIndex($pageIndex)
    {
        $this->pageIndex = $pageIndex;
    }

    /**
     * @return mixed
     */
    public function getPageIndex()
    {
        return $this->pageIndex;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }


    /**
     * @param mixed $itemCount
     */
    public function setItemsCount($itemCount)
    {
        $this->itemsCount = $itemCount;
    }

    /**
     * @return mixed
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * @param mixed $totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * @return mixed
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function calcTotalPages()
    {
        if (!$this->pageSize) {
            $this->pageSize = 20;
        }
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
    }
}
