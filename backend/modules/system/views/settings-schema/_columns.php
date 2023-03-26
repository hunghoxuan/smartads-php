<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$moduleName = 'SettingsSchema';
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
        //'value' => function($model) { return FHtml::showContent($model-> id, '', 'settings_schema', 'id','int(11)','settings-schema'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'settings_schema', 'object_type','varchar(100)','settings-schema'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_schema.object_type', 'settings_schema', 'object_type', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'name',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    //[ //name: description, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'description',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    [ //name: dbType, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'dbType',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: editor, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'editor',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: lookup, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'lookup',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: format, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'format',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: algorithm, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'algorithm',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: group, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'group',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    //[ //name: grid_size, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'grid_size',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: roles, dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'roles',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    //[ //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>['raw'],
        //'attribute'=>'sort_order',
        //'value' => function($model) { return FHtml::showContent($model-> sort_order, '', 'settings_schema', 'sort_order','int(5)','settings-schema'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    //[ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'attribute'=>'is_active',
        //'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_active','tinyint(1)','settings-schema'); }, 
        //'hAlign'=>'center',
        //'vAlign'=>'middle',
        //'width'=>'20px',
    //],
    //[ //name: is_system, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'attribute'=>'is_system',
        //'value' => function($model) { return FHtml::showContent($model-> is_system, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_system','tinyint(1)','settings-schema'); }, 
        //'hAlign'=>'center',
        //'vAlign'=>'middle',
        //'width'=>'20px',
    //],
    //[ //name: is_readonly, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'attribute'=>'is_readonly',
        //'value' => function($model) { return FHtml::showContent($model-> is_readonly, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_readonly','tinyint(1)','settings-schema'); }, 
        //'hAlign'=>'center',
        //'vAlign'=>'middle',
        //'width'=>'20px',
    //],
    //[ //name: created_date, dbType: date, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'created_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'created_user',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: modified_date, dbType: date, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'modified_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: modified_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'modified_user',
        //'hAlign'=>'left',
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