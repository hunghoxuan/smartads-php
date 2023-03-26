<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenContent".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SmartscreenContent';
$moduleTitle = 'Smartscreen Content';
$moduleKey = 'smartscreen-content';
$object_type = 'smartscreen_content';

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
    [
        'attribute'=>'id',
        'width'=>'50px',
    ],

    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute'=>'title',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    ],
    [
        'class' => FHtml::COLUMN_VIEW,

        'attribute'=>'type',
        'hAlign' => 'left',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'filterType' => GridView::FILTER_SELECT2,
        'filter'=> FHtml::getComboArray('smartscreen_content', 'smartscreen_content', 'type', true, 'id', 'name'),
    ],
    [
        'class' => FHtml::COLUMN_VIEW,

        'attribute'=>'content',
        'value'=>function ($model, $key, $index, $column) {
            $result = \backend\modules\smartscreen\Smartscreen::showContentOverview($model);
            return $result;
        },
        'contentOptions'=>['class'=>'col-md-3 nowrap'],
    ],
//    [
//        'attribute'=>'url',
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//    ],
//    [
//        'class' => FHtml::COLUMN_EDIT,
//
//        'attribute'=>'description',
//        'contentOptions'=>['class'=>'col-md-3 nowrap'],
//    ],

//    [
//        'attribute'=>'expire_date',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter'=> FHtml::getComboArray('smartscreen_content', 'smartscreen_content', 'expire_date', true, 'id', 'name'),
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//    ],
    //[ 
        //'attribute'=>'sort_order',
        //'width'=>'50px',
    //],
//    [
//        'attribute'=>'owner_id',
//        'width'=>'80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter'=> FHtml::getComboArray('smartscreen_content', 'smartscreen_content', 'owner_id', true, 'id', 'name'),
//    ],
    [
        'class' => FHtml::COLUMN_EDIT,
        'attribute'=>'is_active',
        'width'=>'20px',
    ],
    [
        'class' => FHtml::COLUMN_EDIT,
        'attribute'=>'is_default',
        'width'=>'20px',
    ],
    //[ 
        //'attribute'=>'created_date',
        //'width'=>'50px',
    //],
    //[ 
        //'attribute'=>'modified_date',
        //'width'=>'50px',
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
            'view' => FHtml::isInRole('', 'view', $currentRole),
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'View'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'Update'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'Delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'Are you sure to delete')
        ],
    ],
];