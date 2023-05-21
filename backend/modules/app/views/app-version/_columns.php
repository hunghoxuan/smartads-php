<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppVersion';
$moduleTitle = 'App Version';
$moduleKey = 'app-version';
$object_type = 'app_version';

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
        'attribute' => 'platform',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'hAlign' => 'left',
        'group' => true,
        'groupedRow' => true,
        'readonly' => true,
    ],
    [
        'attribute' => 'package_name',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'group' => true,
    ],
    [
        'attribute' => 'package_version',
        'width' => '50px',
    ],
    [
        'attribute' => 'name',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
    [
        'attribute' => 'description',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],


    //    [
    //        'attribute' => 'platform_info',
    //        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //    ],

    //    [
    //        'attribute' => 'file',
    //        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //    ],
    //[ 
    //'attribute' => 'count_views',
    //'width' => '50px',
    //],
    //[ 
    //'attribute' => 'count_downloads',
    //'width' => '50px',
    //],
    [
        'attribute' => 'is_active',
        'width' => '20px',
    ],
    [
        'attribute' => 'is_default',
        'width' => '20px',
    ],
    //[ 
    //'attribute' => 'history',
    //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
    //'attribute' => 'created_date',
    //'width' => '50px',
    //],
    //[ 
    //'attribute' => 'created_user',
    //'width' => '80px',
    //'filterType' => GridView::FILTER_SELECT2, 
    //'filter' => FHtml::getComboArray('app_version', 'app_version', 'created_user', true, 'id', 'name'),
    //],
    [
        'attribute' => 'modified_date',
        'width' => '50px',
    ],
    //[ 
    //'attribute' => 'modified_user',
    //'width' => '80px',
    //'filterType' => GridView::FILTER_SELECT2, 
    //'filter' => FHtml::getComboArray('app_version', 'app_version', 'modified_user', true, 'id', 'name'),
    //],
    [
        'attribute' => 'file',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model) {
            return FHtml::showImage($model->file, 'app-version');
        }
    ],
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
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete?')
        ],
    ],
];
