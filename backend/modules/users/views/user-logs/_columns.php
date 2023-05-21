<?php

/**



 * This is the customized model class for table "UserLogs".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'UserLogs';
$moduleTitle = 'User Logs';
$moduleKey = 'user-logs';
$object_type = 'user_logs';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = false;

return [
    //    [
    //        'class' => 'common\widgets\grid\CheckboxColumn',
    //        'width' => '20px',
    //    ],
    /*
        [
            'class' => 'kartik\grid\SerialColumn',
            'width' => '30px',
        ],*/
    //    [
    //        'class'=>'kartik\grid\ExpandRowColumn',
    //        'width'=>'30px',
    //        'value'=>function ($model, $key, $index, $column) {
    //        return GridView::ROW_COLLAPSED;
    //        },
    //        'detail'=>function ($model, $key, $index, $column) {
    //             return Yii::$app->controller->renderPartial('_view', ['model'=>$model, 'print' => false]);
    //        },
    //        'headerOptions'=>['class'=>'kartik-sheet-style'],
    //        'expandOneOnly'=>false
    //    ],
    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'id',
        'visible' => true,
        'value' => function ($model) {
            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'user_logs', 'id', 'bigint(20)', 'user-logs') . '</b>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: created_date, dbType: timestamp, phpType: string, size: , allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'created_date',
        'label' => 'Start',
        'visible' => true,
        'value' => function ($model) {
            return FHtml::showDateTime($model->created_date, '', 'user_logs', 'created_date', 'timestamp', 'user-logs');
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    //    [ //name: log_date, dbType: varchar(18), phpType: string, size: 18, allowNull: 1
    //        'class' => FHtml::getColumnClass($object_type, 'log_date', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'log_date',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'log_date', $form_type),
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //                            'widgetClass'=> 'kartik\datecontrol\InputControl',
    //                            'options'=>[
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],
    [ //name: user_id, dbType: varchar(100), phpType: string, size: 100, allowNull:
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'user_id',
        'visible' => true,
        'value' => function ($model) {
            return FHtml::showContent($model->user_id, FHtml::SHOW_LOOKUP, '@user', 'user_id', 'varchar(100)', 'user-logs');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('@user', 'user', 'user_id', true, 'id', 'name'),
    ],
    [ //name: action, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'action',
        'visible' => true,
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
    [ //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'object_type',
        'visible' => true,
        'value' => function ($model) {
            return FHtml::showContent($model->object_type, FHtml::SHOW_LABEL, 'user_logs', 'object_type', 'varchar(255)', 'user-logs') . ' #' . $model->object_id;
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'width' => '100px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('user_logs', 'user_logs', 'object_type', true, 'id', 'name'),
    ],
    //    [ //name: object_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1
    //        'class' => FHtml::getColumnClass($object_type, 'object_id', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'object_id',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'object_id', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model->object_id, FHtml::SHOW_LABEL, 'user_logs', 'object_id','int(11)','user-logs'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('user_logs', 'user_logs', 'object_id', true, 'id', 'name'),
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'editableOptions'=> function ($model, $key, $index, $widget) {
    //                                    $fields = FHtml::getComboArray('user_logs', 'user_logs', 'object_id', true, 'id', 'name');
    //                                    return [
    //                                    'inputType' => 'dropDownList',
    //                                    'displayValueConfig' => $fields,
    //                                    'data' => $fields
    //                                    ];
    //                                    },
    //    ],
    [ //name: link_url, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'link_url',
        'visible' => true,
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
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
    [ //name: ip_address, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'ip_address',
        'visible' => true,
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
    //    [ //name: duration, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    //        'class' => FHtml::getColumnClass($object_type, 'duration', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'duration',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'duration', $form_type),
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //                            'widgetClass'=> 'kartik\datecontrol\InputControl',
    //                            'options'=>[
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],

    //[ //name: modified_date, dbType: timestamp, phpType: string, size: , allowNull: 1
    //'class' => FHtml::getColumnClass($object_type, 'modified_date', $form_type),
    //'format'=>'raw',
    //'attribute'=>'modified_date',
    //'visible' => FHtml::isVisibleInGrid($object_type, 'modified_date', $form_type),
    //'value' => function($model) { return FHtml::showContent($model-> modified_date, '', 'user_logs', 'modified_date','timestamp','user-logs'); },
    //'hAlign'=>'right',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    [ //name: created_date, dbType: timestamp, phpType: string, size: , allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'status',
        'label' => 'Status',
        'visible' => true,
        'value' => function ($model) {
            return $model->status;
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ //name: created_date, dbType: timestamp, phpType: string, size: , allowNull: 1
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'modified_date',
        'label' => 'End',
        'visible' => true,
        'value' => function ($model) {
            return FHtml::showDateTime($model->modified_date, '', 'user_logs', 'modified_date', 'timestamp', 'user-logs');
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
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
