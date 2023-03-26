<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FSortOrderColumn extends DataColumn
{
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the data cell.
     *
     * @param Model $model
     * @param mixed $key
     * @param int   $index
     *
     * @return mixed|string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (FHtml::field_exists($model, 'sort_order'))
            return FHtml::showSortOrderArrowsButton(FHtml::showModelFieldValue($model, 'sort_order'));
        else
            return parent::renderDataCellContent($model, $key, $index);
    }
}
