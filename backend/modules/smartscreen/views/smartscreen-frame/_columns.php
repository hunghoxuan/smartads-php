<?php

/**
 *
 ***
 * This is the customized model class for table "SmartscreenFrame".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SmartscreenFrame';
$moduleTitle = 'Smartscreen Frame';
$moduleKey = 'smartscreen-frame';
$object_type = 'smartscreen_frame';

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
    ],*/
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
        'class' => FHtml::COLUMN_VIEW,

        'attribute' => 'preview',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model, $key, $index, $column) {
            return \backend\modules\smartscreen\Smartscreen::showFramePreview($model, 'background-color: #666;width: 100%;height:50px;position: relative;');
        },
    ],
    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute' => 'name',
        'contentOptions' => ['class' => 'col-md-3 nowrap'],

    ],
    // [
    //     'class' => FHtml::COLUMN_EDIT,
    //     'attribute' => 'contentLayout',
    //     'contentOptions' => ['class' => 'col-md-1 nowrap'],
    // ],
    [
        'class' => FHtml::COLUMN_EDIT,
        'attribute' => 'marginTop',
        'width' => '50px',
    ],
    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute' => 'marginLeft',
        'width' => '50px',
    ],
    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute' => 'percentWidth',
        'width' => '50px',
    ],
    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute' => 'percentHeight',
        'width' => '50px',
    ],

    [
        'attribute' => 'is_active',
        'width' => '50px',
    ],
    //    [
    //        'attribute'=>'contentLayout',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //    ],
    //[ 
    //'attribute'=>'created_date',
    //'width'=>'50px',
    //],
    //[ 
    //'attribute'=>'modified_date',
    //'width'=>'50px',
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
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete')
        ],
    ],
];
