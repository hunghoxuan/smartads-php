<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppUserTransaction".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';
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
    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'attribute'=>'id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'app_user_transaction', 'id','bigint(20)','app-user-transaction') . '</b>' ; }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: transaction_id, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'transaction_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'transaction_id', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> transaction_id, FHtml::SHOW_LABEL, 'app_user_transaction', 'transaction_id','varchar(255)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'100px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'transaction_id', true, 'id', 'name'),
    ],
    [ //name: user_id, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'user_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'user_id', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> user_id, FHtml::SHOW_LABEL, 'app_user_transaction', 'user_id','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'user_id', true, 'id', 'name'),
    ],
    [ //name: receiver_user_id, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'receiver_user_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'receiver_user_id', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> receiver_user_id, FHtml::SHOW_LOOKUP, '@user', 'receiver_user_id','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('@user', 'user', 'receiver_user_id', true, 'id', 'name'),
    ],
    [ //name: object_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_id', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> object_id, FHtml::SHOW_LABEL, 'app_user_transaction', 'object_id','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_id', true, 'id', 'name'),
    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'object_type', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'app_user_transaction', 'object_type','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'object_type', true, 'id', 'name'),
    ],
    [ //name: amount, dbType: decimal(20,2), phpType: string, size: 20, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'amount', $form_type, true),
        'format'=>'raw',//['decimal', 2],
        'attribute'=>'amount',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'amount', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> amount, FHtml::SHOW_DECIMAL, 'app_user_transaction', 'amount','decimal(20,2)','app-user-transaction'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=> '\kartik\money\MaskMoney', //\kartik\editable\Editable::INPUT_SPIN,
                            'options'=>[
                                'pluginOptions'=>[
                                    'min'=>0, 'max' => 50000000000
                                ]
                            ]
                        ],
    ],
    [ //name: currency, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'currency', $form_type, true),
        'format'=>'raw',
        'attribute'=>'currency',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'currency', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'currency', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'currency', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: payment_method, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'payment_method', $form_type, true),
        'format'=>'raw',
        'attribute'=>'payment_method',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'payment_method', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'payment_method', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'payment_method', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: note, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'note', $form_type, true),
        'format'=>'raw',
        'attribute'=>'note',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'note', $form_type, true),
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
    [ //name: time, dbType: varchar(20), phpType: string, size: 20, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'time', $form_type, true),
        'format'=>'raw',
        'attribute'=>'time',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'time', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'time', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'time', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: action, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'action', $form_type, true),
        'format'=>'raw',
        'attribute'=>'action',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'action', $form_type, true),
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
    [ //name: type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'type', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> type, FHtml::SHOW_LABEL, 'app_user_transaction', 'type','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'type', true, 'id', 'name'),
    ],
    [ //name: status, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'status',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'status', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> status, FHtml::SHOW_LABEL, 'app_user_transaction', 'status','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'status', true, 'id', 'name'),
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
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'app_user_transaction', 'created_user','varchar(100)','app-user-transaction'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'created_user', true, 'id', 'name'),
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
    //[ //name: modified_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'modified_user',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'modified_user', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model-> modified_user, FHtml::SHOW_LABEL, 'app_user_transaction', 'modified_user','varchar(100)','app-user-transaction'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('app_user_transaction', 'app_user_transaction', 'modified_user', true, 'id', 'name'),
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
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];