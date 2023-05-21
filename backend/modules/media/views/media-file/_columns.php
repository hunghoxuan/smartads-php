<?php

/*
 * This is the customized model class for table "MediaFile".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'MediaFile';
$moduleTitle = 'Media File';
$moduleKey = 'media-file';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole($moduleName, 'edit');

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
    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function ($model) {
            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'media_file', 'id', 'bigint(20)', 'media-file') . '</b>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'name', $form_type, true),
        'format' => 'raw',
        'attribute' => 'name',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'name', $form_type, true),
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
    [ //name: image, dbType: varchar(300), phpType: string, size: 300, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'image', $form_type, true),
        'format' => 'html',
        'attribute' => 'image',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'image', $form_type, true),
        'value' => function ($model) {
            return FHtml::showImageThumbnail($model->image, FHtml::config(FHtml::SETTINGS_THUMBNAIL_SIZE, 50), 'media-file');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
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
    [ //name: file_path, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'file_path', $form_type, true),
        'format' => 'raw',
        'attribute' => 'file_path',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'file_path', $form_type, true),
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
    //'value' => function($model) { return FHtml::showContent($model-> file_type, FHtml::SHOW_LABEL, 'media_file', 'file_type','varchar(100)','media-file'); }, 
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2, 
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('media_file', 'media_file', 'file_type', true, 'id', 'name'),
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
            return FHtml::showContent($model->is_active, FHtml::SHOW_BOOLEAN, 'media_file', 'is_active', 'tinyint(1)', 'media-file');
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
    //'value' => function($model) { return FHtml::showContent($model-> sort_order, FHtml::SHOW_NUMBER, 'media_file', 'sort_order','tinyint(5)','media-file'); }, 
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
    //'displayFormat'=> FHtml::settingDateFormat(),
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
    //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'media_file', 'created_user','varchar(100)','media-file'); }, 
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2, 
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('media_file', 'media_file', 'created_user', true, 'id', 'name'),
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
            'update' => FHtml::isInRole($moduleKey, 'update', $currentRole),
            'delete' => FHtml::isInRole($moduleKey, 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
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
