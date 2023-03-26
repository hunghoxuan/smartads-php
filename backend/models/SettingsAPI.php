<?php

namespace backend\models;

use common\base\BasePHPObject;
use common\components\FConstant;
use common\components\FHtml;
use yii\helpers\BaseInflector;
use yii\helpers\StringHelper;


class SettingsApi extends BasePHPObject
{

    const COLUMNS_UPLOAD = ['image'];

    public static function tableName()
    {
        return 'settings_api'; // TODO: Change the autogenerated stub
    }

    public function fields() {
        return ['id', 'code', 'module', 'name', 'summary', 'description', 'method', 'is_active', 'type',  'data', 'parameters', 'responses', 'data_html', 'data_link', 'data_array', 'data_array_columns', 'permissions'];
    }

    public static function getDbType()
    {
        return FConstant::DB_TYPE_PHP;
    }

    public function getFullUrl() {
        $url = FHtml::getFullURL($this->code);
        return $url;
    }

    public static function findAll($condition = [], $order_by = '', $page_size = -1, $page = 1, $is_cached = false, $display_fields = [], $asArray = true, $loadActiveOnly = true) {
        $models = parent::findAll($condition);
        $actions = FHtml::getApiControllerActions();

        $search_module = FHtml::getRequestParam('module');
        foreach ($models as $i => $model) {
            if (is_numeric($i))
            {
                unset($models[$i]);
                $models[$model->code] = $model;
            }
        }
        $i = 0;
        foreach ($actions as $action => $class) {
            $i += 1;
            $arr = explode('/', $action);
            $module =  $arr[0];

            if (key_exists($action, $models))
                continue;

            if (!empty($search_module) && strtolower($module) != strtolower($search_module))
                continue;

            $model = new SettingsApi();
            $model->code = $action;
            $model->name = $class;
            $model->module = $module;
            $model->is_active = true;
            $model->id = $action;
            $models[$action] = $model;
        }

        return $models;
    }

    public static function findOne($condition, $selected_fields = [], $asArray = false, $applications_enabled = true)
    {
        $models = static::findAll();
        foreach ($models as $model1) {
            if ($model1->code == $condition)
                return $model1;
        }
        return parent::findOne($condition, $selected_fields, $asArray, $applications_enabled);
    }
}