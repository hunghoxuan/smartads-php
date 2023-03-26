<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ObjectCalendar';
$moduleTitle = 'Object Calendar';
$moduleKey = 'object-calendar';
$object_type = 'object_calendar';

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
    */
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
    [ 
        'attribute' => 'id',
        'width' => '50px',
    ],
    [ 
        'attribute' => 'object_id',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'object_id', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'object_type',
        'width' => '80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'object_type', true, 'id', 'name'),
    ],
    [ 
        'attribute' => 'color',
        'width' => '20px',
        'filterType' => GridView::FILTER_COLOR, 
    ],
    [ 
        'attribute' => 'title',
        'contentOptions' => ['class' => 'col-md-5 nowrap'],
    ],
    [ 
        'attribute' => 'start_date',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'end_date',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'all_day',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'all_day', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'status',
        'width' => '80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'status', true, 'id', 'name'),
    ],
    //[ 
        //'attribute' => 'link_url',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    [ 
        'attribute' => 'type',
        'width' => '80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'type', true, 'id', 'name'),
    ],
    //[ 
        //'attribute' => 'created_user',
        //'width' => '80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filter' => FHtml::getComboArray('object_calendar', 'object_calendar', 'created_user', true, 'id', 'name'),
    //],
    //[ 
        //'attribute' => 'created_date',
        //'width' => '50px',
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' => [
            'view' => FHtml::isInRole('', 'view', $currentRole),
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