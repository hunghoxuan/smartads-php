<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "User".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'User';
$moduleTitle = 'User';
$moduleKey = 'user';
$object_type = 'user';

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
    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'attribute'=>'id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'user', 'id','int(11)','user') . '</b>' ; }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: image, dbType: varchar(300), phpType: string, size: 300, allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'image', $form_type),
        'format'=>'html',
        'attribute'=>'image',
        'visible' => FHtml::isVisibleInGrid($object_type, 'image', $form_type),
        'value' => function($model) { return FHtml::showImageThumbnail($model-> image, FHtml::config(FHtml::SETTINGS_THUMBNAIL_SIZE, 50), 'user'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
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

    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
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
    [ //name: code, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'code', $form_type),
        'format'=>'raw',
        'attribute'=>'code',
        'visible' => FHtml::isVisibleInGrid($object_type, 'code', $form_type),
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
    [ //name: username, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'username', $form_type),
        'format'=>'raw',
        'attribute'=>'username',
        'visible' => FHtml::isVisibleInGrid($object_type, 'username', $form_type),
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

//    [ //name: overview, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1
//        'class' => FHtml::getColumnClass($object_type, 'overview', $form_type),
//        'format'=>'raw',
//        'attribute'=>'overview',
//        'visible' => FHtml::isVisibleInGrid($object_type, 'overview', $form_type),
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
    //[ //name: content, dbType: text, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'content', $form_type),
        //'format'=>'raw',
        //'attribute'=>'content',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'content', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> content, '', 'user', 'content','text','user'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    //[ //name: auth_key, dbType: varchar(32), phpType: string, size: 32, allowNull:  
        //'class' => FHtml::getColumnClass($object_type, 'auth_key', $form_type),
        //'format'=>'raw',
        //'attribute'=>'auth_key',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'auth_key', $form_type),
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
    //[ //name: password_hash, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        //'class' => FHtml::getColumnClass($object_type, 'password_hash', $form_type),
        //'format'=>'raw',
        //'attribute'=>'password_hash',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'password_hash', $form_type),
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
    //[ //name: password_reset_token, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'password_reset_token', $form_type),
        //'format'=>'raw',
        //'attribute'=>'password_reset_token',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'password_reset_token', $form_type),
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
    [ //name: email, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'email', $form_type),
        'format'=>'raw',
        'attribute'=>'email',
        'visible' => FHtml::isVisibleInGrid($object_type, 'email', $form_type),
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
    [ //name: phone, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'phone', $form_type),
        'format'=>'raw',
        'attribute'=>'phone',
        'visible' => FHtml::isVisibleInGrid($object_type, 'phone', $form_type),
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
    [ //name: skype, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'skype', $form_type),
        'format'=>'raw',
        'attribute'=>'skype',
        'visible' => FHtml::isVisibleInGrid($object_type, 'skype', $form_type),
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
//    [ //name: address, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1
//        'class' => FHtml::getColumnClass($object_type, 'address', $form_type),
//        'format'=>'raw',
//        'attribute'=>'address',
//        'visible' => FHtml::isVisibleInGrid($object_type, 'address', $form_type),
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
    //[ //name: city, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'city', $form_type),
        //'format'=>'raw',
        //'attribute'=>'city',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'city', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('user', 'user', 'city', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=> function ($model, $key, $index, $widget) {
                                    //$fields = FHtml::getComboArray('user', 'user', 'city', true, 'id', 'name');
                                    //return [
                                    //'inputType' => 'dropDownList',
                                    //'displayValueConfig' => $fields,
                                    //'data' => $fields
                                    //];
                                    //},
    //],
    //[ //name: department, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'department', $form_type),
        //'format'=>'raw',
        //'attribute'=>'department',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'department', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('user', 'user', 'department', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=> function ($model, $key, $index, $widget) {
                                    //$fields = FHtml::getComboArray('user', 'user', 'department', true, 'id', 'name');
                                    //return [
                                    //'inputType' => 'dropDownList',
                                    //'displayValueConfig' => $fields,
                                    //'data' => $fields
                                    //];
                                    //},
    //],
    //[ //name: start_date, dbType: date, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'start_date', $form_type),
        //'format'=>'raw', // date 
        //'attribute'=>'start_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'start_date', $form_type),
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
    //[ //name: end_date, dbType: date, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'end_date', $form_type),
        //'format'=>'raw', // date 
        //'attribute'=>'end_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'end_date', $form_type),
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
    //[ //name: role, dbType: int(2), phpType: integer, size: 2, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'role', $form_type),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'role',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'role', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> role, FHtml::SHOW_NUMBER, 'user', 'role','int(2)','user'); }, 
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
    [ //name: type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'type', $form_type),
        'format'=>'raw',
        'attribute'=>'type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'type', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> type, FHtml::SHOW_LABEL, 'user', 'type','varchar(100)','user'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('user', 'user', 'type', true, 'id', 'name'),
    ],
    [ //name: status, dbType: smallint(6), phpType: integer, size: 6, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'status', $form_type),
        'format'=>'raw',
        'attribute'=>'status',
        'visible' => FHtml::isVisibleInGrid($object_type, 'status', $form_type),
        'value' => function($model) { return FHtml::showContent($model->status, FHtml::SHOW_LABEL, 'user', 'status','smallint(6)','user'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('user', 'user', 'status', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('user', 'user', 'status', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    //[ //name: created_at, dbType: int(11), phpType: integer, size: 11, allowNull:  
        //'class' => FHtml::getColumnClass($object_type, 'created_at', $form_type),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'created_at',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_at', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> created_at, FHtml::SHOW_NUMBER, 'user', 'created_at','int(11)','user'); }, 
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
    //[ //name: updated_at, dbType: int(11), phpType: integer, size: 11, allowNull:  
        //'class' => FHtml::getColumnClass($object_type, 'updated_at', $form_type),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'updated_at',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'updated_at', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> updated_at, FHtml::SHOW_NUMBER, 'user', 'updated_at','int(11)','user'); }, 
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
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];