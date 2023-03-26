<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;


class FActiveDataProvider extends ActiveDataProvider
{
    public $db;
    public $model;

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount()
    {
        if (!$this->query instanceof QueryInterface) {
            return 0;
        }
        return parent::prepareTotalCount();
    }

    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            return '';
        }

        if (isset($this->db) && !is_object($this->db)) {
            //$this->models = $this->model::findAll();
            return;
        }

        return parent::prepareModels();
    }
}