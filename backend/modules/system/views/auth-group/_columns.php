<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AuthGroup".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AuthGroup';
$moduleTitle = 'Auth Group';
$moduleKey = 'auth-group';
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
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'auth_group', 'id','int(11)','auth-group') . '</b>' ; }, 
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
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'class' => FHtml::COLUMN_VIEW,
        'label' => FHtml::t('common', 'Rights'),
        'format'=>'html',
        //'attribute'=>'groups',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-4 nowrap'],
        'value' => function($model) { return implode(', ', $model->getRights()) . FHtml::showItemsArray($model->getRolesModels(), '{name}', ['name'], '; ') ; },

    ],
//    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
//        'class' => FHtml::COLUMN_VIEW,
//        'label' => FHtml::t('common', 'Users'),
//        'format'=>'html',
//        //'attribute'=>'groups',
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-4 nowrap'],
//        'value' => function($model) {  return FHtml::showItemsArray(\common\models\User::findAll(['id' => $model->getUsers()]), '{name}', ['name'], '; ') ; },
//
//    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_active',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_active', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'auth_group', 'is_active','tinyint(1)','auth-group'); }, 
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
            'data-confirm-title'=>FHtml::t('message', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];