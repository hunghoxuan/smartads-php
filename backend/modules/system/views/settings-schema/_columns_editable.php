<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsSchema".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsSchema';
$moduleTitle = 'Settings Schema';
$moduleKey = 'settings-schema';
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
//    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
//        'class'=>'kartik\grid\DataColumn',
//        'format'=>'html',
//        'attribute'=>'id',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
//        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'settings_schema', 'id','int(11)','settings-schema') . '</b>' ; },
//        'hAlign'=>'center',
//        'vAlign'=>'middle',
//        'width'=>'50px',
//    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull:
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'group' => true,
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_type', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'settings_schema', 'object_type','varchar(100)','settings-schema'); },
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'object_type', true, 'id', 'name'),
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>'kartik\grid\DataColumn',
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

    [ //name: dbType, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'dbType',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'dbType', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'dbType', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('settings_schema', 'settings_schema', 'dbType', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: description, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description', $form_type, true),
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
    [ //name: editor, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'editor', $form_type, true),
        'format'=>'raw',
        'attribute'=>'editor',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'editor', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'editor', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('settings_schema', 'settings_schema', 'editor', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: lookup, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'lookup', $form_type, true),
        'format'=>'raw',
        'attribute'=>'lookup',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'lookup', $form_type, true),
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
    [ //name: format, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'format', $form_type, true),
        'format'=>'raw',
        'attribute'=>'format',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'format', $form_type, true),
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
//    [ //name: algorithm, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'algorithm', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'algorithm',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'algorithm', $form_type, true),
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
    [ //name: group, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'group', $form_type, true),
        'format'=>'raw',
        'attribute'=>'group',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'group', $form_type, true),
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
//    [ //name: grid_size, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'grid_size', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'grid_size',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'grid_size', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//                            'pluginOptions'=>['allowClear' => true],
//                            ],
//        'filterInputOptions'=>['placeholder'=>''],
//        'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'grid_size', true, 'id', 'name'),
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//        'editableOptions'=> function ($model, $key, $index, $widget) {
//                                    $fields = FHtml::getComboArray('settings_schema', 'settings_schema', 'grid_size', true, 'id', 'name');
//                                    return [
//                                    'inputType' => 'dropDownList',
//                                    'displayValueConfig' => $fields,
//                                    'data' => $fields
//                                    ];
//                                    },
//    ],
//    [ //name: roles, dbType: varchar(500), phpType: string, size: 500, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'roles', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'roles',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'roles', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-2 nowrap'],
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
    //[ //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull: 1 
        //'class' => FHtml::getColumnClass($moduleName, 'sort_order', $form_type, true),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'sort_order',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'sort_order', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model-> sort_order, FHtml::SHOW_NUMBER, 'settings_schema', 'sort_order','int(5)','settings-schema'); }, 
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

//    [ //name: is_system, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
//        'class'=>'kartik\grid\BooleanColumn',
//        'format'=>'raw',
//        'attribute'=>'is_system',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_system', $form_type, true),
//        'value' => function($model) { return FHtml::showContent($model-> is_system, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_system','tinyint(1)','settings-schema'); },
//        'hAlign'=>'center',
//        'vAlign'=>'middle',
//        'width'=>'20px',
//    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_group',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_group', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_group, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_group','tinyint(1)','settings-schema'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    [ //name: is_readonly, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_readonly',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_readonly', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_readonly, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_readonly','tinyint(1)','settings-schema'); }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_column',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_column', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model->is_column, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_column','tinyint(1)','settings-schema'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'settings_schema', 'is_active','tinyint(1)','settings-schema'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
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
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'created_user',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'created_user', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'settings_schema', 'created_user','varchar(100)','settings-schema'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'created_user', true, 'id', 'name'),
    //],
    //[ //name: modified_date, dbType: date, phpType: string, size: , allowNull: 1 
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
        //'value' => function($model) { return FHtml::showContent($model-> modified_user, FHtml::SHOW_LABEL, 'settings_schema', 'modified_user','varchar(100)','settings-schema'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('settings_schema', 'settings_schema', 'modified_user', true, 'id', 'name'),
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' =>[
            'view' => false,
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>$this->params['displayType'], 'title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
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