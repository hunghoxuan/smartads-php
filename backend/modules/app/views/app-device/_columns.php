<?php

/**
 *
 * **
 * This is the customized model class for table "AppDevice".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppDevice';
$moduleTitle = 'App Device';
$moduleKey = 'app-device';
$object_type = 'app_device';

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
    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
        'value' => function ($model) {
            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'app_device', 'id', 'int(11)', 'app-device') . '</b>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'user_id', $form_type),
        'format' => 'raw',
        'attribute' => 'user_id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'user_id', $form_type),
        'value' => function ($model) {
            return FHtml::showContent($model->user_id, FHtml::SHOW_LOOKUP, '@app_user', 'user_id', 'int(11)', 'app-device');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('@app_user', 'app_user', 'user_id', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => function ($model, $key, $index, $widget) {
            $fields = FHtml::getComboArray('@app_user', 'app_user', 'user_id', true, 'id', 'name');
            return [
                'inputType' => 'dropDownList',
                'displayValueConfig' => $fields,
                'data' => $fields
            ];
        },
    ],
    [ //name: ime, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'ime', $form_type),
        'format' => 'raw',
        'attribute' => 'ime',
        'visible' => FHtml::isVisibleInGrid($object_type, 'ime', $form_type),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'widgetClass' => 'kartik\datecontrol\InputControl',
            'options' => [
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
    [ //name: gcm_id, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'gcm_id', $form_type),
        'format' => 'raw',
        'attribute' => 'gcm_id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'gcm_id', $form_type),
        'value' => function ($model) {
            return FHtml::showContent($model->gcm_id, FHtml::SHOW_LABEL, 'app_device', 'gcm_id', 'varchar(255)', 'app-device');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'width' => '100px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('app_device', 'app_device', 'gcm_id', true, 'id', 'name'),
    ],
    [ //name: type, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw',
        'attribute' => 'type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'type', $form_type),
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: status, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw',
        'attribute' => 'status',
        'visible' => FHtml::isVisibleInGrid($object_type, 'status', $form_type),
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
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
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];
