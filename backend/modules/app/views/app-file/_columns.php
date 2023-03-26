<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppFile';
$moduleTitle = 'App File';
$moduleKey = 'app-file';
$object_type = 'app_file';

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
//    [
//        'attribute' => 'id',
//        'width' => '50px',
//    ],
    [
        'class' => FHtml::COLUMN_VIEW,
        'attribute' => 'file_name',
        'contentOptions' => ['class' => 'col-md-4 nowrap'],
    ],
    [
        'class' => FHtml::COLUMN_VIEW,
        'attribute' => 'file_size',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
//    [
//        'attribute' => 'user_id',
//        'width' => '80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter' => FHtml::getComboArray('app_file', 'app_file', 'user_id', true, 'id', 'name'),
//    ],
    [
        'class' => FHtml::COLUMN_VIEW,
        'attribute' => 'ime',
        'contentOptions' => ['class' => 'col-md-3 nowrap'],
    ],
    [
        'class' => FHtml::COLUMN_VIEW,
        'attribute' => 'status',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'filterType' => GridView::FILTER_SELECT2, 
        'filter' => FHtml::getComboArray('app_file', 'app_file', 'status', true, 'id', 'name'),
        'value' => function ($model, $key, $index, $column) {
            return $model->status;
        },
    ],
    [
        'attribute' => 'download_time',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
//    [
//        'attribute' => 'created_date',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
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
            'view' => false,
            'update' => false,
            'delete' => false,
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
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete?')
        ],
    ],
];