<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "ObjectFile".
 */

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ObjectFile';
$moduleTitle = 'Object File';
$moduleKey = 'object-file';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view_preview', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function ($model) {
            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'object_file', 'id', 'bigint(20)', 'object-file') . '</b>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: object_id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'object_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_id', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->object_id, FHtml::SHOW_LABEL, 'object_file', 'object_id', 'int(11)', 'object-file');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => function ($model, $key, $index, $widget) {
            $fields = FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name');
            return [
                'inputType' => 'dropDownList',
                'displayValueConfig' => $fields,
                'data' => $fields
            ];
        },
    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'object_type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_type', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->object_type, FHtml::SHOW_LABEL, 'object_file', 'object_type', 'varchar(100)', 'object-file');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'width' => '80px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name'),
    ],
    [ //name: file, dbType: varchar(555), phpType: string, size: 555, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'file', $form_type, true),
        'format' => 'raw',
        'attribute' => 'file',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'file', $form_type, true),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-5 nowrap'],
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
    [ //name: title, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'title', $form_type, true),
        'format' => 'raw',
        'attribute' => 'title',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'title', $form_type, true),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-5 nowrap'],
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
    //[ //name: description, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'description', $form_type, true),
    //'format'=>'raw',
    //'attribute'=>'description',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'description', $form_type, true),
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-md-5 nowrap'],
    //'editableOptions'=>[
    //'size'=>'md',
    //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //'widgetClass'=> 'kartik\datecontrol\InputControl',
    //'options'=>[
    //'options'=>[
    //'pluginOptions'=>[
    //'autoclose'=>true
    //]
    //]
    //]
    //],
    //],
    //[ //name: file_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>'raw',
    //'attribute'=>'file_type',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'file_type', $form_type, true),
    //'value' => function($model) { return FHtml::showContent($model-> file_type, FHtml::SHOW_LABEL, 'object_file', 'file_type','varchar(100)','object-file'); },
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2,
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('object_file', 'object_file', 'file_type', true, 'id', 'name'),
    //],
    //[ //name: file_size, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'file_size', $form_type, true),
    //'format'=>'raw',
    //'attribute'=>'file_size',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'file_size', $form_type, true),
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //'editableOptions'=>[
    //'size'=>'md',
    //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //'widgetClass'=> 'kartik\datecontrol\InputControl',
    //'options'=>[
    //'options'=>[
    //'pluginOptions'=>[
    //'autoclose'=>true
    //]
    //]
    //]
    //],
    //],
    //[ //name: file_duration, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'file_duration', $form_type, true),
    //'format'=>'raw',
    //'attribute'=>'file_duration',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'file_duration', $form_type, true),
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //'editableOptions'=>[
    //'size'=>'md',
    //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //'widgetClass'=> 'kartik\datecontrol\InputControl',
    //'options'=>[
    //'options'=>[
    //'pluginOptions'=>[
    //'autoclose'=>true
    //]
    //]
    //]
    //],
    //],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw',
        'attribute' => 'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_BOOLEAN, 'object_file', 'is_active', 'tinyint(1)', 'object-file');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
    ],
    //[ //name: sort_order, dbType: tinyint(5), phpType: integer, size: 5, allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'sort_order', $form_type, true),
    //'format'=>'raw', //['decimal', 0],
    //'attribute'=>'sort_order',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'sort_order', $form_type, true),
    //'value' => function($model) { return FHtml::showContent($model-> sort_order, FHtml::SHOW_NUMBER, 'object_file', 'sort_order','tinyint(5)','object-file'); },
    //'hAlign'=>'right',
    //'vAlign'=>'middle',
    //'width'=>'50px',
    //'editableOptions'=>[
    //'size'=>'md',
    //'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
    //'options'=>[
    //'pluginOptions'=>[
    //'min'=>0, 'max' => 50000000000, 'precision' => 0,
    //]
    //]
    //],
    //],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'created_date', $form_type, true),
    //'format'=>'raw', // date
    //'attribute'=>'created_date',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'created_date', $form_type, true),
    //'hAlign'=>'right',
    //'vAlign'=>'middle',
    //'width'=>'50px',
    //'editableOptions'=>[
    //'size'=>'md',
    //'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
    //'widgetClass'=> 'kartik\datecontrol\DateControl',
    //'options'=>[
    //'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
    //'displayFormat'=> FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
    //'saveFormat'=>'php:Y-m-d',
    //'options'=>[
    //'pluginOptions'=>[
    //'autoclose'=>true
    //]
    //]
    //]
    //],
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>'raw',
    //'attribute'=>'created_user',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'created_user', $form_type, true),
    //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'object_file', 'created_user','varchar(100)','object-file'); },
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2,
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('object_file', 'object_file', 'created_user', true, 'id', 'name'),
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' => [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('message', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];