<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsLookup".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsLookup';
$moduleTitle = 'Settings Lookup';
$moduleKey = 'settings-lookup';
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
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'30px',
        'value'=>function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
             return Yii::$app->controller->renderPartial('_view_preview', ['model'=>$model]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>false
    ],
    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'attribute'=>'id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'settings_lookup', 'id','int(11)','settings-lookup') . '</b>' ; }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'name', $form_type, true),
        'format'=>'raw',
        'attribute'=>'name',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'name', $form_type, true),
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
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_type', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'settings_lookup', 'object_type','varchar(100)','settings-lookup'); },
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_lookup', 'settings_lookup', 'object_type', true, 'id', 'name'),
    ],
    [ //name: params, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'params', $form_type, true),
        'format'=>'raw',
        'attribute'=>'params',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'params', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-4 nowrap'],
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
    [ //name: fields, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'fields', $form_type, true),
        'format'=>'raw',
        'attribute'=>'fields',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'fields', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-4 nowrap'],
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
    [ //name: orderby, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'orderby', $form_type, true),
        'format'=>'raw',
        'attribute'=>'orderby',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'orderby', $form_type, true),
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
    [ //name: limit, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'limit', $form_type, true),
        'format'=>'raw',
        'attribute'=>'limit',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'limit', $form_type, true),
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
    //[ //name: sql, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        //'class' => FHtml::getColumnClass($moduleName, 'sql', $form_type, true),
        //'format'=>'raw',
        //'attribute'=>'sql',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'sql', $form_type, true),
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
    [ //name: is_cached, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_cached',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_cached', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_cached, FHtml::SHOW_BOOLEAN, 'settings_lookup', 'is_cached','tinyint(1)','settings-lookup'); }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    [ //name: is_active, dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw', //['decimal', 0],
        'attribute'=>'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'settings_lookup', 'is_active','tinyint(4)','settings-lookup'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'20px',
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
    //[ //name: sort_order, dbType: tinyint(4), phpType: integer, size: 4, allowNull: 1 
        //'class' => FHtml::getColumnClass($moduleName, 'sort_order', $form_type, true),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'sort_order',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'sort_order', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model-> sort_order, FHtml::SHOW_NUMBER, 'settings_lookup', 'sort_order','tinyint(4)','settings-lookup'); }, 
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
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'created_user',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'created_user', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'settings_lookup', 'created_user','varchar(100)','settings-lookup'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('settings_lookup', 'settings_lookup', 'created_user', true, 'id', 'name'),
    //],
    //[ //name: created_date, dbType: date, phpType: string, size: , allowNull: 1 
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
        'viewOptions'=>['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
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