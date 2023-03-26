<?php

namespace common\models;

use common\components\FHtml;
use yii\data\ActiveDataProvider;

class BaseDataList extends ActiveDataProvider
{
    public $viewModels;
    public $page_size;
    public $models;
    public $display_fields;
    public $asArray;

    public function getViewModels()
    {
        if (!isset($this->viewModels)) {
            $models = $this->getModels();

            $this->viewModels = FHtml::toViewModel($models);
        }

        return $this->viewModels;
    }

    public function getModels()
    {
        if (isset($this->models))
            return $this->models;

        if (!isset($this->query))
            return [];
        
        $this->models = $this->asArray ? $this->query->asArray->all() : $this->query->all();
        $selected_fields = $this->display_fields;

        if (!empty($selected_fields) && is_array($this->models)) {
            //var_dump($this->models);var_dump($selected_fields); die;
            $list1 = [];
            foreach ($this->models as $model) {
                if (is_object($model) && isset($model) && method_exists($model, 'setFields')) {
                    $model->setFields($selected_fields);
                }
                $list1[] = $model;
            }
            $this->models = $list1;
        }


        return $this->models;
    }

    /**
     * Returns the pagination object used by this data provider.
     * Note that you should call [[prepare()]] or [[getModels()]] first to get correct values
     * of [[Pagination::totalCount]] and [[Pagination::pageCount]].
     * @return Pagination|boolean the pagination object. If this is false, it means the pagination is disabled.
     */
    public function getPagination()
    {
        $p = parent::getPagination();

        //$p->totalCount = $this->getTotalCount();
        return $p;
    }

//    public function getTotalCount()
//    {
//        if (isset($this->query)) {
//            $count = $this->query->count();
//
//            return $count;
//        }
//
//        return count($this->getModels());
//
//        if ($this->getPagination() === false) {
//            return $this->getCount();
//        } elseif ($this->_totalCount === null) {
//            $this->_totalCount = $this->prepareTotalCount();
//        }
//
//        return $this->_totalCount;
//    }
}
