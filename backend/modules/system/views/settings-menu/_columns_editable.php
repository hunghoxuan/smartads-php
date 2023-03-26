<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "SettingsMenu".
 */

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsMenu';
$moduleTitle = 'Cms Menu';
$moduleKey = 'settings-menu';
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
//    [ //name: group, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'group', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'group',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'group', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//            'pluginOptions'=>['allowClear' => true],
//        ],
//        'group' => true,
//        'filterInputOptions'=>['placeholder'=>''],
//        'filter'=> FHtml::getComboArray('settings_menu', 'settings_menu', 'group', true, 'id', 'name'),
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//        'editableOptions'=> function ($model, $key, $index, $widget) {
//            $fields = FHtml::getComboArray('settings_menu', 'settings_menu', 'group', true, 'id', 'name');
//            return [
//                'inputType' => 'dropDownList',
//                'displayValueConfig' => $fields,
//                'data' => $fields
//            ];
//        },
//    ],
    [ //name: module, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'module', $form_type, true),
        'format' => 'raw',
        'attribute' => 'module',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'module', $form_type, true),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'group' => true,
        'groupedRow' => true,
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('settings_menu', 'settings_menu', 'module', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => function ($model, $key, $index, $widget) {
            $fields = FHtml::getComboArray('settings_menu', 'settings_menu', 'module', true, 'id', 'name');
            return [
                'inputType' => 'dropDownList',
                'displayValueConfig' => $fields,
                'data' => $fields
            ];
        },
    ],
    //[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>'raw',
    //'attribute'=>'id',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
    //'value' => function($model) { return FHtml::showContent($model->id, FHtml::SHOW_LABEL, 'settings_menu', 'id','int(11)','settingsmenu'); },
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'filterType' => GridView::FILTER_SELECT2,
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('settings_menu', 'settings_menu', 'id', true, 'id', 'name'),
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //'editableOptions'=> function ($model, $key, $index, $widget) {
    //$fields = FHtml::getComboArray('settings_menu', 'settings_menu', 'id', true, 'id', 'name');
    //return [
    //'inputType' => 'dropDownList',
    //'displayValueConfig' => $fields,
    //'data' => $fields
    //];
    //},
    //],
//    [ //name: icon, dbType: varchar(300), phpType: string, size: 300, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'icon', $form_type, true),
//        'format' => 'raw',
//        'attribute' => 'icon',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'icon', $form_type, true),
//        'hAlign' => 'left',
//        'vAlign' => 'middle',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//        'editableOptions' => [
//            'size' => 'md',
//            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//            'widgetClass' => 'kartik\datecontrol\InputControl',
//            'options' => [
//                'options' => [
//                    'pluginOptions' => [
//                        'autoclose' => true
//                    ]
//                ]
//            ]
//        ],
//    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'name', $form_type, true),
        'format' => 'raw',
        'attribute' => 'name',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'name', $form_type, true),
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
    [ //name: url, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'url', $form_type, true),
        'format' => 'raw',
        'attribute' => 'url',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'url', $form_type, true),
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
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'object_type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_type', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->object_type, FHtml::SHOW_LABEL, 'settings_menu', 'object_type', 'varchar(100)', 'settingsmenu');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('settings_menu', 'settings_menu', 'object_type', true, 'id', 'name'),
    ],
//
//
//    [ //name: menu_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
//        'class' => 'kartik\grid\DataColumn',
//        'format' => 'raw',
//        'attribute' => 'menu_type',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'menu_type', $form_type, true),
//        'value' => function ($model) {
//            return FHtml::showContent($model->menu_type, FHtml::SHOW_LABEL, 'settings_menu', 'menu_type', 'varchar(100)', 'settingsmenu');
//        },
//        'hAlign' => 'left',
//        'vAlign' => 'middle',
//        'width' => '80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['allowClear' => true],
//        ],
//        'filterInputOptions' => ['placeholder' => ''],
//        'filter' => FHtml::getComboArray('settings_menu', 'settings_menu', 'menu_type', true, 'id', 'name'),
//    ],
//    [ //name: display_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
//        'class' => 'kartik\grid\DataColumn',
//        'format' => 'raw',
//        'attribute' => 'display_type',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'display_type', $form_type, true),
//        'value' => function ($model) {
//            return FHtml::showContent($model->display_type, FHtml::SHOW_LABEL, 'settings_menu', 'display_type', 'varchar(100)', 'settingsmenu');
//        },
//        'hAlign' => 'left',
//        'vAlign' => 'middle',
//        'width' => '80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['allowClear' => true],
//        ],
//        'filterInputOptions' => ['placeholder' => ''],
//        'filter' => FHtml::getComboArray('settings_menu', 'settings_menu', 'display_type', true, 'id', 'name'),
//    ],
//    [ //name: sort_order, dbType: int(11), phpType: integer, size: 11, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'sort_order', $form_type, true),
//        'format'=>'raw', //['decimal', 0],
//        'attribute'=>'sort_order',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'sort_order', $form_type, true),
//        'value' => function($model) { return FHtml::showContent($model-> sort_order, FHtml::SHOW_NUMBER, 'settings_menu', 'sort_order','int(11)','settingsmenu'); },
//        'hAlign'=>'right',
//        'vAlign'=>'middle',
//        'width'=>'50px',
//        'editableOptions'=>[
//                            'size'=>'md',
//                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
//                            'options'=>[
//                                'pluginOptions'=>[
//                                    'min'=>0, 'max' => 50000000000, 'precision' => 0,
//                                ]
//                            ]
//                        ],
//    ],
    [ //name: role, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'role', $form_type, true),
        'format' => 'raw',
        'attribute' => 'role',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'role', $form_type, true),
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
    [ //name: is_active, dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw', //['decimal', 0],
        'attribute' => 'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_BOOLEAN, 'settings_menu', 'is_active', 'tinyint(4)', 'settingsmenu');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
            'options' => [
                'pluginOptions' => [
                    'min' => 0, 'max' => 50000000000, 'precision' => 0,
                ]
            ]
        ],
    ],
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
    //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'settings_menu', 'created_user','varchar(100)','settingsmenu'); },
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2,
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('settings_menu', 'settings_menu', 'created_user', true, 'id', 'name'),
    //],
    //[ //name: modified_date, dbType: datetime, phpType: string, size: , allowNull: 1 
    //'class' => FHtml::getColumnClass($moduleName, 'modified_date', $form_type, true),
    //'format'=>'raw', // date
    //'attribute'=>'modified_date',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'modified_date', $form_type, true),
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
    //[ //name: modified_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>'raw',
    //'attribute'=>'modified_user',
    //'visible' => FHtml::isVisibleInGrid($moduleName, 'modified_user', $form_type, true),
    //'value' => function($model) { return FHtml::showContent($model-> modified_user, FHtml::SHOW_LABEL, 'settings_menu', 'modified_user','varchar(100)','settingsmenu'); },
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'width'=>'80px',
    //'filterType' => GridView::FILTER_SELECT2,
    //'filterWidgetOptions'=>[
    //'pluginOptions'=>['allowClear' => true],
    //],
    //'filterInputOptions'=>['placeholder'=>''],
    //'filter'=> FHtml::getComboArray('settings_menu', 'settings_menu', 'modified_user', true, 'id', 'name'),
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