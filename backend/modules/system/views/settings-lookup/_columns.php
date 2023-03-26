<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$moduleName = 'SettingsLookup';
$currentRole = FHtml::getCurrentRole();

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view_print', ['model'=>$model]);
        },
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
        'expandOneOnly'=>true
    ],
    //[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>['raw'],
        //'attribute'=>'id',
        //'value' => function($model) { return FHtml::showContent($model-> id, '', 'settings_lookup', 'id','int(11)','settings-lookup'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'name',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'settings_lookup', 'object_type','varchar(100)','settings-lookup'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_lookup.object_type', 'settings_lookup', 'object_type', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: params, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'params',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: fields, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'fields',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: orderby, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'orderby',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: limit, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'limit',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: sql, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'sql',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    //[ //name: is_cached, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'attribute'=>'is_cached',
        //'value' => function($model) { return FHtml::showContent($model-> is_cached, FHtml::SHOW_BOOLEAN, 'settings_lookup', 'is_cached','tinyint(1)','settings-lookup'); }, 
        //'hAlign'=>'center',
        //'vAlign'=>'middle',
        //'width'=>'20px',
    //],
    //[ //name: is_active, dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'format'=>['raw'],
        //'attribute'=>'is_active',
        //'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'settings_lookup', 'is_active','tinyint(4)','settings-lookup'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'20px',
    //],
    //[ //name: sort_order, dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>['raw'],
        //'attribute'=>'sort_order',
        //'value' => function($model) { return FHtml::showContent($model-> sort_order, '', 'settings_lookup', 'sort_order','tinyint(4)','settings-lookup'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'created_user',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: created_date, dbType: date, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'created_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false, // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return Url::to([$action, 'id' => $model->id]);
        },
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
        'visibleButtons' =>[
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
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