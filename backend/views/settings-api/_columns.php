<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsApi';
$moduleTitle = 'Settings Api';
$moduleKey = 'settings-api';
$object_type = 'settings_api';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    /*
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '30px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view', ['model' => $model, 'print' => false]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
*/
    //[ 
        //'attribute' => 'id',
        //'width' => '50px',
    //],
    [
        'attribute' => 'module',
        'group' => true,
        //'groupedRow' => true,
        'visible' => empty(FHtml::getRequestParam('module')),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'name',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function($model) {
            //$url = $model->getFullUrl();
            return '/' . $model->code . "<br/><small style='color: darkgrey'> $model->name </small>";
        },
    ],
    [
        'attribute' => 'description',
        'contentOptions' => ['class' => 'col-md-3 nowrap'],
        'value' => function($model) {
//            $arr = [];
//            $arr1 = FHtml::decode($model->parameters);
//            if (!is_array($arr1))
//                return $arr1;
//            foreach ($arr1 as $parameter) {
//                $arr2 = !is_array($parameter) ? FHtml::decode($parameter) : $parameter;
//                $name = isset($arr2['name']) ? $arr2['name'] : '';
//                $description = isset($arr2['description']) ? $arr2['description'] : '';
//
//                if (empty($name))
//                    continue;
//                $arr = array_merge($arr, [$name => $description]);
//            }
            $arr = [];
            $result = $model->summary . "<br/><small style='color: darkgrey'> $model->description </small>";
            //$result .= (!empty($arr) ? "<br/><b>parameters </b> <br/>" : "") . FHtml::showArray($arr);
            return $result;
            //return $model->summary . "<br/><small style='color: darkgrey'> $model->description </small>";
        },
    ],
//    [
//        'attribute' => 'type',
//        'width' => '80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter' => FHtml::getComboArray('settings_api', 'settings_api', 'type', true, 'id', 'name'),
//    ],

//    [
//        'attribute' => 'type',
//        'width' => '80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter' => FHtml::getComboArray('settings_api', 'settings_api', 'type', true, 'id', 'name'),
//    ],
    //[ 
        //'attribute' => 'data',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'permissions',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    [
        'hAlign' => 'center',
        'attribute' => 'method',
        'width' => '20px',
        'value' => function($model) {
            $method = empty($model->method) ? 'GET' : strtoupper($model->method);
            $css = $method == 'GET' ? 'primary' : 'success';
            return "<div class='label-$css'>$method</div>";
        },
    ],
    [ 
        'attribute' => 'is_active',
        'width' => '20px',
    ],
    [
        'value' => function($model) {
            $url = $model->getFullUrl();
            return FHtml::showLink($url, 'Open', ['class' => 'btn btn-xs', 'target' => '_blank', 'data-pjax' => '0']);
        },
        'hAlign' => 'center',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    //[ 
        //'attribute' => 'modified_date',
        //'width' => '50px',
    //],
    //[ 
        //'attribute' => 'modified_user',
        //'width' => '80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filter' => FHtml::getComboArray('settings_api', 'settings_api', 'modified_user', true, 'id', 'name'),
    //],
    [
        'class' => 'common\widgets\FActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->getPrimaryKeyValue()]);
        },
        'visibleButtons' => [
            'view' => false,
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'View'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'Update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'Delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('message', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete?')
        ],
    ],
];