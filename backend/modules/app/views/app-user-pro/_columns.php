<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$moduleName = 'AppUserPro';
$currentRole = FHtml::getCurrentRole();

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view_print', ['model'=>$model]);
        },
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
        'expandOneOnly'=>true
    ],
    [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>['raw'],
        'attribute'=>'user_id',
        'value' => function($model) { return FHtml::showContent($model-> user_id, '', 'app_user_pro', 'user_id','int(11)','app-user-pro'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: rate, dbType: float(3,1), phpType: double, size: 3, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'rate',
        'value' => function($model) { return FHtml::showContent($model-> rate, '', 'app_user_pro', 'rate','float(3,1)','app-user-pro'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: rate_count, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>['raw'],
        'attribute'=>'rate_count',
        'value' => function($model) { return FHtml::showContent($model-> rate_count, '', 'app_user_pro', 'rate_count','int(11)','app-user-pro'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    //[ //name: description, dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'description',
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    [ //name: business_name, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'business_name',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: business_email, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'business_email',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: business_address, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'business_address',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: business_website, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'business_website',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: business_phone, dbType: varchar(20), phpType: string, size: 20, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'business_phone',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class'=>'kartik\grid\BooleanColumn',
        'attribute'=>'is_active',
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'app_user_pro', 'is_active','tinyint(1)','app-user-pro'); }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'created_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: modified_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'modified_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false, // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return Url::to([$action, 'id' => $model->user_id]);
        },
        'headerOptions'=>['class'=>'hidden-print'],
        'contentOptions'=>['class'=>'hidden-print'],
        'visibleButtons' =>[
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
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