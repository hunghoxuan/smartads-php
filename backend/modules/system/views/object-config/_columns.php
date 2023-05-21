<?php

/**
 *
 ***
 * This is the customized model class for table "ObjectActions".
 */

use common\components\FHtml;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ObjectActions';
$moduleTitle = 'Object Actions';
$moduleKey = 'object-actions';
$object_type = 'object_actions';

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
    [ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'created_date', $form_type),
        'format' => 'raw', // date
        'attribute' => 'created_date',
        'visible' => FHtml::isVisibleInGrid($object_type, 'created_date', $form_type),
        'hAlign' => 'right',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
            'widgetClass' => 'kartik\datecontrol\DateControl',
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'displayFormat' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
                'saveFormat' => 'php:Y-m-d',
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
    [ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'created_user', $form_type),
        'format' => 'raw',
        'attribute' => 'created_user',
        'visible' => FHtml::isVisibleInGrid($object_type, 'created_user', $form_type),
        'value' => function ($model) {
            return FHtml::showContent($model->created_user, FHtml::SHOW_LABEL, 'object_actions', 'created_user', 'varchar(100)', 'object-actions');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('object_actions', 'object_actions', 'created_user', true, 'id', 'name'),
    ],
    [ //name: action, dbType: varchar(100), phpType: string, size: 100, allowNull:
        'class' => FHtml::getColumnClass($object_type, 'action', $form_type),
        'format' => 'raw',
        'attribute' => 'action',
        'visible' => FHtml::isVisibleInGrid($object_type, 'action', $form_type),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('object_actions', 'object_actions', 'action', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => function ($model, $key, $index, $widget) {
            $fields = FHtml::getComboArray('object_actions', 'object_actions', 'action', true, 'id', 'name');
            return [
                'inputType' => 'dropDownList',
                'displayValueConfig' => $fields,
                'data' => $fields
            ];
        },
    ],
    //    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:
    //        'class'=>'kartik\grid\DataColumn',
    //        'format'=>'html',
    //        'attribute'=>'id',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
    //        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'object_actions', 'id','bigint(20)','object-actions') . '</b>' ; },
    //        'hAlign'=>'center',
    //        'vAlign'=>'middle',
    //        'width'=>'50px',
    //    ],
    //    [ //name: object_id, dbType: varchar(100), phpType: string, size: 100, allowNull:
    //        'class' => FHtml::getColumnClass($object_type, 'object_id', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'object_id',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'object_id', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> object_id, FHtml::SHOW_LABEL, 'object_actions', 'object_id','varchar(100)','object-actions'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('object_actions', 'object_actions', 'object_id', true, 'id', 'name'),
    //    ],
    //    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull:
    //        'class' => FHtml::getColumnClass($object_type, 'object_type', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'object_type',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'object_type', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'object_actions', 'object_type','varchar(100)','object-actions'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('object_actions', 'object_actions', 'object_type', true, 'id', 'name'),
    //    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull:
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'name',
        'label' => FHtml::t('common', 'Changed Fields'),
        'visible' => FHtml::isVisibleInGrid($object_type, 'name', $form_type),
        'value' => function ($model) {
            return FHtml::showObjectActions($model, ['code', 'old_value', 'new_value']);
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-8 nowrap'],


    ],
    //    [ //name: old_content, dbType: text, phpType: string, size: , allowNull: 1
    //        'class' => FHtml::getColumnClass($object_type, 'old_content', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'old_content',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'old_content', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> old_content, '', 'object_actions', 'old_content','text','object-actions'); },
    //        'hAlign'=>'right',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //    ],
    //    [ //name: content, dbType: text, phpType: string, size: , allowNull:
    //        'class' => FHtml::getColumnClass($object_type, 'content', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'content',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'content', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> content, '', 'object_actions', 'content','text','object-actions'); },
    //        'hAlign'=>'right',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //    ],

    //    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
    //        'class'=>'kartik\grid\BooleanColumn',
    //        'format'=>'raw',
    //        'attribute'=>'is_active',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'is_active', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'object_actions', 'is_active','tinyint(1)','object-actions'); },
    //        'hAlign'=>'center',
    //        'vAlign'=>'middle',
    //        'width'=>'20px',
    //    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' => [
            'view' => false,
            'update' => false,
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
            'data-confirm-title' => FHtml::t('message', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];
