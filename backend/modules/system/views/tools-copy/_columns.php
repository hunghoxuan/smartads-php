<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ToolsCopy".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ToolsCopy';
$moduleTitle = 'Tools Copy';
$moduleKey = 'tools-copy';
$object_type = 'tools_copy';

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
        'attribute'=>'name',
        'filterType' => GridView::FILTER_SELECT2, 
        'filter'=> FHtml::getComboArray('tools_copy', 'tools_copy', 'name', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-md-5 nowrap'],
    ],
    [ 
        'attribute'=>'folders',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [ 
        'attribute'=>'files',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [ 
        'attribute'=>'description',
        'contentOptions'=>['class'=>'col-md-5 nowrap'],
    ],
    //[ 
        //'attribute'=>'created_date',
        //'width'=>'50px',
    //],
    //[ 
        //'attribute'=>'modified_date',
        //'width'=>'50px',
    //],
    //[ 
        //'attribute'=>'created_user',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filter'=> FHtml::getComboArray('tools_copy', 'tools_copy', 'created_user', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
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
            'data-confirm-title'=>FHtml::t('message', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'Are you sure to delete?')
        ],
    ],
];