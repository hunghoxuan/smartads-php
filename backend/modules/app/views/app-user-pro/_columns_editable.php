<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppUserPro".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppUserPro';
$moduleTitle = 'App User Pro';
$moduleKey = 'app-user-pro';
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
    [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'user_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'user_id', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model->user_id, FHtml::SHOW_LABEL, 'app_user_pro', 'user_id','int(11)','app-user-pro'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_pro', 'app_user_pro', 'user_id', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('app_user_pro', 'app_user_pro', 'user_id', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: rate, dbType: float(3,1), phpType: double, size: 3, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'rate', $form_type, true),
        'format'=>'raw',
        'attribute'=>'rate',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'rate', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> rate, '', 'app_user_pro', 'rate','float(3,1)','app-user-pro'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [ //name: rate_count, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'rate_count',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'rate_count', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> rate_count, FHtml::SHOW_NUMBER, 'app_user_pro', 'rate_count','int(11)','app-user-pro'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    //[ //name: description, dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
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
    [ //name: business_name, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'business_name', $form_type, true),
        'format'=>'raw',
        'attribute'=>'business_name',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'business_name', $form_type, true),
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
    [ //name: business_email, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'business_email', $form_type, true),
        'format'=>'raw',
        'attribute'=>'business_email',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'business_email', $form_type, true),
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
    [ //name: business_address, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'business_address', $form_type, true),
        'format'=>'raw',
        'attribute'=>'business_address',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'business_address', $form_type, true),
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
    [ //name: business_website, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'business_website', $form_type, true),
        'format'=>'raw',
        'attribute'=>'business_website',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'business_website', $form_type, true),
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
    [ //name: business_phone, dbType: varchar(20), phpType: string, size: 20, allowNull: 1 
        'class' => FHtml::getColumnClass($moduleName, 'business_phone', $form_type, true),
        'format'=>'raw',
        'attribute'=>'business_phone',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'business_phone', $form_type, true),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_pro', 'app_user_pro', 'business_phone', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=> function ($model, $key, $index, $widget) {
                                    $fields = FHtml::getComboArray('app_user_pro', 'app_user_pro', 'business_phone', true, 'id', 'name');
                                    return [
                                    'inputType' => 'dropDownList',
                                    'displayValueConfig' => $fields,
                                    'data' => $fields
                                    ];
                                    },
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'app_user_pro', 'is_active','tinyint(1)','app-user-pro'); }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
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