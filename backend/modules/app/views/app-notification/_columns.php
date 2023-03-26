<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppNotification".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppNotification';
$moduleTitle = 'App Notification';
$moduleKey = 'app-notification';
$object_type = 'app_notification';

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
//    [ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:
//        'class'=>'kartik\grid\DataColumn',
//        'format'=>'html',
//        'attribute'=>'id',
//        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
//        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'app_notification', 'id','bigint(20)','app-notification') . '</b>' ; },
//        'hAlign'=>'center',
//        'vAlign'=>'middle',
//        'width'=>'50px',
//    ],
    [ //name: message, dbType: varchar(2000), phpType: string, size: 2000, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'message', $form_type),
        'format'=>'raw',
        'attribute'=>'message',
        'visible' => FHtml::isVisibleInGrid($object_type, 'message', $form_type),
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
    [ //name: action, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'action', $form_type),
        'format'=>'raw',
        'attribute'=>'action',
        'visible' => FHtml::isVisibleInGrid($object_type, 'action', $form_type),
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
    [ //name: params, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'params', $form_type),
        'format'=>'raw',
        'attribute'=>'params',
        'visible' => FHtml::isVisibleInGrid($object_type, 'params', $form_type),
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
    [ //name: sent_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'sent_type', $form_type),
        'format'=>'raw',
        'attribute'=>'sent_type',
        'visible' => FHtml::isVisibleInGrid($object_type, 'sent_type', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> sent_type, FHtml::SHOW_LABEL, 'app_notification', 'sent_type','varchar(100)','app-notification'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_notification', 'app_notification', 'sent_type', true, 'id', 'name'),
    ],
    [ //name: sent_date, dbType: datetime, phpType: string, size: , allowNull: 1
        'class' => FHtml::getColumnClass($object_type, 'sent_date', $form_type),
        'format'=>'raw', // date
        'attribute'=>'sent_date',
        'visible' => FHtml::isVisibleInGrid($object_type, 'sent_date', $form_type),
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                            'widgetClass'=> 'kartik\datecontrol\DateControl',
                            'options'=>[
                                'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                'displayFormat'=> FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
                                'saveFormat'=>'php:Y-m-d',
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: receiver_count, dbType: bigint(20), phpType: string, size: 20, allowNull: 1
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'receiver_count',
        'visible' => FHtml::isVisibleInGrid($object_type, 'receiver_count', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> receiver_count, FHtml::SHOW_NUMBER, 'app_notification', 'receiver_count','bigint(20)','app-notification'); },
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    //[ //name: receiver_users, dbType: text, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'receiver_users', $form_type),
        //'format'=>'raw',
        //'attribute'=>'receiver_users',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'receiver_users', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> receiver_users, '', 'app_notification', 'receiver_users','text','app-notification'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'created_date', $form_type),
        //'format'=>'raw', // date 
        //'attribute'=>'created_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_date', $form_type),
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
        //'class' => FHtml::getColumnClass($object_type, 'created_user', $form_type),
        //'format'=>'raw',
        //'attribute'=>'created_user',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_user', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'app_notification', 'created_user','varchar(100)','app-notification'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('app_notification', 'app_notification', 'created_user', true, 'id', 'name'),
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