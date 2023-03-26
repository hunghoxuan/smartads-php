<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\grid\ActionColumn;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FActionColumn extends ActionColumn
{
    public $actionLayout = '{delete}';
    public $jsFunction = '{name}Pjax("{id}")';
    public $object_type;

    public function init()
    {
        $this->options['class'] = 'kv-align-middle';
       parent::init();
    }

    /**
     * Render default action buttons
     *
     * @return string
     */
    protected function initDefaultButtons()
    {
        return parent::initDefaultButtons();
    }

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        return parent::renderDataCell($model, $key, $index);
    }

    public static function render($model, $key, $index, $layout, $object_type = '', $jsFunction = '') {
        $column = new FActionColumn();
        $column->actionLayout = $layout;
        $column->object_type = $object_type;
        $column->jsFunction = $jsFunction;

        return $column->renderDataCellContentPublic($model, $key, $index);
    }

    public function renderDataCellContentPublic($model, $key, $index) {
        return $this->renderDataCellContent($model, $key, $index);
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
        if (!in_array($this->dropdown, ['ajax', 'open', 'modal', 'modal-remote', 'iframe'])) {
            return "<div class='hidden-print'>" . parent::renderDataCellContent($model, $key, $index) . "</div>";
        }

        if (!isset($model))
            return '';

        if (empty($this->object_type)) {
            $table = FHtml::getTableName($model);
            $model_id = $model->primaryKey;
        }
        else {
            $table = $this->object_type;
            $model_id = FHtml::getFieldValue($model, ['object2_id', 'id'], $model->getPrimaryKeyValue());
        }

        $return_url = FHtml::currentUrl();

        $result = $this->actionLayout;
        $container = BaseInflector::camelize($this->grid->object_type . '_' . $this->grid->getId());

        $js_function = FHtml::strReplace($this->jsFunction, ['{name}' => $container, '{id}' => $model_id]);

        $delete_icon = FHtml::ICON_REMOVE;

        if (in_array($this->dropdown, ['ajax', 'open'])) {
            $result = str_replace('{delete}', "<span class='' title='Delete'  style='margin-bottom:0px !important;' onclick='delete{$js_function}'> $delete_icon </span>", $result);
            $result = str_replace('{reset}', "<span class='' title='Update' style='margin-bottom:0px !important;'  onclick='reset{$js_function}'>  <span class='glyphicon glyphicon-repeat btn btn-xs btn-primary'></span> </span>", $result);
            $result = str_replace('{unlink}', "<span class='' title='Remove' style='margin-bottom:0px !important;'  onclick='unlink{$js_function}'> <span class='glyphicon glyphicon-remove btn btn-xs btn-warning'></span> </span>", $result);
            $result = str_replace('{view}', FHtml::buttonModal('<span class=\'glyphicon glyphicon-eye-open \'></span>', FHtml::createModelUrl($table, 'view', ['id' => $model_id,  'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url]) , 'modal-remote', 'btn btn-xs btn-default'), $result);

            //$result = str_replace('{view}', "<a class='' title='View' style='margin-bottom:0px !important;' data-pjax='0' href='" . FHtml::createModelUrl($table, 'view', ['id' => $model_id, 'return_url' => $return_url]) . "'> <span class='glyphicon glyphicon-eye-open btn btn-xs btn-default'></span> </a>", $result);
            $result = str_replace('{update}', "<a class='' title='Edit' style='margin-bottom:0px !important;' data-pjax='0' href='" . FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'return_url' => $return_url]) . "'> <span class='glyphicon glyphicon-pencil btn btn-xs btn-primary'></span> </a>", $result);
            $result = str_replace('{edit}', "<a class='' title='Edit' style='margin-bottom:0px !important;' data-pjax='0' href='" . FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'return_url' => $return_url]) . "'> <span class='glyphicon glyphicon-pencil btn btn-xs btn-primary'></span> </a>", $result);

            return "<div class='hidden-print'>$result</div>";
        } else if (in_array($this->dropdown, ['modal', 'modal-remote'])) {
            $result = str_replace('{delete}', "<span class='' title='Delete'  style='margin-bottom:0px !important;' onclick='delete{$js_function}'> $delete_icon </span>", $result);
            $result = str_replace('{reset}', "<span class='' title='Update' style='margin-bottom:0px !important;'  onclick='reset{$js_function}'>  <span class='glyphicon glyphicon-repeat btn btn-xs btn-primary'></span> </span>", $result);
            $result = str_replace('{unlink}', "<span class='' title='Remove' style='margin-bottom:0px !important;'  onclick='unlink{$js_function}'> <span class='glyphicon glyphicon-remove btn btn-xs btn-warning'></span> </span>", $result);

            $result = str_replace('{view}', FHtml::buttonModal('<span class=\'glyphicon glyphicon-eye-open \'></span>', FHtml::createModelUrl($table, 'view', ['id' => $model_id,  'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url]) , 'modal-remote', 'btn btn-xs btn-default'), $result);
            $result = str_replace('{update}', FHtml::buttonModal('<span class=\'glyphicon glyphicon-pencil \'></span>', FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url]) , 'modal-remote', 'btn btn-xs btn-primary'), $result);
            $result = str_replace('{edit}', FHtml::buttonModal('<span class=\'glyphicon glyphicon-pencil \'></span>', FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url]) , 'modal-remote', 'btn btn-xs btn-primary'), $result);

            return "<div class='hidden-print'>$result</div>";
        }  else if (in_array($this->dropdown, ['iframe'])) {
            $result = str_replace('{delete}', "<button type='button' class='btn btn-xs btn-danger' title='Delete'  style='margin-bottom:0px !important;' onclick='delete{$js_function}'> &nbsp;$delete_icon </button>", $result);
            $result = str_replace('{reset}', "<button type='button' class='btn btn-xs btn-warning' title='Update' style='margin-bottom:0px !important;'  onclick='reset{$js_function}'>  &nbsp;<span class='glyphicon glyphicon-repeat'></span> </button>", $result);
            $result = str_replace('{unlink}', "<button type='button' class='btn btn-xs btn-warning' title='Remove' style='margin-bottom:0px !important;'  onclick='unlink{$js_function}'> &nbsp;<span class='glyphicon glyphicon-remove'></span> </button>", $result);

            $result = str_replace('{view}', FHtml::buttonLink('', '<span class=\'glyphicon glyphicon-eye-open \'></span>', FHtml::createModelUrl($table, 'view', ['id' => $model_id,  'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url, 'layout' => 'no']) , 'default', '', 'iframe', ['class' => 'btn btn-xs btn-default']), $result);
            $result = str_replace('{update}', FHtml::buttonLink('', '<span class=\'glyphicon glyphicon-pencil \'></span>', FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url, 'layout' => 'no']) , 'primary', '', 'iframe', ['class' => 'btn btn-xs btn-primary']), $result);
            $result = str_replace('{edit}', FHtml::buttonLink('', '<span class=\'glyphicon glyphicon-pencil \'></span>', FHtml::createModelUrl($table, 'update', ['id' => $model_id, 'pjax_container' => $this->grid->getId() . '-pjax', 'return_url' => $return_url, 'layout' => 'no']) , 'primary', '', 'iframe', ['class' => 'btn btn-xs btn-primary']), $result);

            return "<div class='hidden-print'>$result</div>";
        } else {
            return "<div class='hidden-print'>" . parent::renderDataCellContent($model, $key, $index) . "</div>";
        }
    }

}
