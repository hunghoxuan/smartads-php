<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$colorPluginOptions = [
    'showPalette' => true,
    'showPaletteOnly' => true,
    'showSelectionPalette' => true,
    'showAlpha' => false,
    'allowEmpty' => true,
    'preferredFormat' => 'name',
    'palette' => [
        [
            "white", "black", "grey", "silver", "gold", "brown",
        ],
        [
            "red", "orange", "yellow", "indigo", "maroon", "pink"
        ],
        [
            "blue", "green", "violet", "cyan", "magenta", "purple",
        ],
    ]
];

$moduleName = 'ObjectSetting';
$currentRole = FHtml::getCurrentRole();

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    //[
    //    'class' => 'kartik\grid\SerialColumn',
    //    'width' => '30px',
    //],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
    //[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>['decimal', 0],
    //'attribute'=>'id',
    //'value' => function($model) { return FHtml::showContent($model-> id, '', 'object_setting', 'id','int(11)','object-setting'); },
    //'hAlign'=>'right',
    //'vAlign'=>'middle',
    //'width'=>'50px',
    //],
    [ //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'object_type',
        'value' => function ($model) {
            return FHtml::showContent($model->object_type, FHtml::SHOW_LABEL, 'object_setting', 'object_type', 'varchar(255)', 'object-setting');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('object_setting.object_type', 'object_setting', 'object_type', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ //name: meta_key, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'meta_key',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
    [ //name: key, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'key',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
    [ //name: value, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'value',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
    [ //name: description, dbType: text, phpType: string, size: , allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description',
        'value' => function ($model) {
            return FHtml::showContent($model->description, '', 'object_setting', 'description', 'text', 'object-setting');
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ //name: icon, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'icon',
        'value' => function ($model) {
            return FHtml::showImageThumbnail($model->icon, FHtml::config('ADMIN_THUMBNAIL_WIDTH', 50), 'object-setting');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: color, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'color',
        'value' => function ($model) {
            return FHtml::showContent($model->color, FHtml::SHOW_COLOR, 'object_setting', 'color', 'varchar(255)', 'object-setting');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'width' => '20px',
        'filterType' => GridView::FILTER_COLOR,

        'filterWidgetOptions' => [
            'showDefaultPalette' => false,
            'pluginOptions' => $colorPluginOptions,
        ],
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => 'kartik\grid\BooleanColumn',
        'attribute' => 'is_active',
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_BOOLEAN, 'object_setting', 'is_active', 'tinyint(1)', 'object-setting');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
    ],
    [ //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => ['decimal', 0],
        'attribute' => 'sort_order',
        'value' => function ($model) {
            return FHtml::showContent($model->sort_order, '', 'object_setting', 'sort_order', 'int(5)', 'object-setting');
        },
        'hAlign' => 'right',
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
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' => [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];