<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ToolsImport".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ToolsImport';
$moduleTitle = 'Tools Import';
$moduleKey = 'tools-import';
$object_type = 'tools_import';

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
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'30px',
        'value'=>function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
             return Yii::$app->controller->renderPartial('_view', ['model'=>$model, 'print' => false]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>false
    ],
    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'attribute'=>'id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'tools_import', 'id','bigint(20)','tools-import') . '</b>' ; }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'name', $form_type),
        'format'=>'raw',
        'attribute'=>'name',
        'visible' => FHtml::isVisibleInGrid($object_type, 'name', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: file, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'file', $form_type),
        'format'=>'raw',
        'attribute'=>'file',
        'visible' => FHtml::isVisibleInGrid($object_type, 'file', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: sheet_name, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'sheet_name', $form_type),
        'format'=>'raw',
        'attribute'=>'sheet_name',
        'visible' => FHtml::isVisibleInGrid($object_type, 'sheet_name', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: first_row, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'first_row', $form_type),
        'format'=>'raw', //['decimal', 0],
        'attribute'=>'first_row',
        'visible' => FHtml::isVisibleInGrid($object_type, 'first_row', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> first_row, FHtml::SHOW_NUMBER, 'tools_import', 'first_row','int(11)','tools-import'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
                            'options'=>[
                                'pluginOptions'=>[
                                    'min'=>0, 'max' => 50000000000, 'precision' => 0, 
                                ]
                            ]
                        ],
    ],
    [ //name: last_row, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'last_row', $form_type),
        'format'=>'raw', //['decimal', 0],
        'attribute'=>'last_row',
        'visible' => FHtml::isVisibleInGrid($object_type, 'last_row', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> last_row, FHtml::SHOW_NUMBER, 'tools_import', 'last_row','int(11)','tools-import'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
                            'options'=>[
                                'pluginOptions'=>[
                                    'min'=>0, 'max' => 50000000000, 'precision' => 0, 
                                ]
                            ]
                        ],
    ],
    [ //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'object_type', $form_type),
        'format'=>'raw',
        'attribute'=>'object_type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'object_type', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'tools_import', 'object_type','varchar(255)','tools-import'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'100px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('tools_import', 'tools_import', 'object_type', true, 'id', 'name'),
    ],
    [ //name: key_columns, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'key_columns', $form_type),
        'format'=>'raw',
        'attribute'=>'key_columns',
        'visible' => FHtml::isVisibleInGrid($object_type, 'key_columns', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: columns, dbType: text, phpType: string, size: , allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'columns', $form_type),
        'format'=>'raw',
        'attribute'=>'columns',
        'visible' => FHtml::isVisibleInGrid($object_type, 'columns', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> columns, '', 'tools_import', 'columns','text','tools-import'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [ //name: default_values, dbType: text, phpType: string, size: , allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'default_values', $form_type),
        'format'=>'raw',
        'attribute'=>'default_values',
        'visible' => FHtml::isVisibleInGrid($object_type, 'default_values', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> default_values, '', 'tools_import', 'default_values','text','tools-import'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [ //name: override_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'override_type', $form_type),
        'format'=>'raw',
        'attribute'=>'override_type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'override_type', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> override_type, FHtml::SHOW_LABEL, 'tools_import', 'override_type','varchar(100)','tools-import'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('tools_import', 'tools_import', 'override_type', true, 'id', 'name'),
    ],
    [ //name: type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'type', $form_type),
        'format'=>'raw',
        'attribute'=>'type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'type', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> type, FHtml::SHOW_LABEL, 'tools_import', 'type','varchar(100)','tools-import'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('tools_import', 'tools_import', 'type', true, 'id', 'name'),
    ],
    //[ //name: created_date, dbType: timestamp, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'created_date', $form_type),
        //'format'=>'raw',
        //'attribute'=>'created_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_date', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> created_date, '', 'tools_import', 'created_date','timestamp','tools-import'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'created_user', $form_type),
        //'format'=>'raw',
        //'attribute'=>'created_user',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_user', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'tools_import', 'created_user','varchar(100)','tools-import'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('tools_import', 'tools_import', 'created_user', true, 'id', 'name'),
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' =>[
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'title.delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('message', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];