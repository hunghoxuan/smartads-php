<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$moduleName = 'AppUserTransaction';
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
    //[ //name: id, dbType: bigint(20), phpType: string, size: 20, allowNull:  
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>['raw'],
        //'attribute'=>'id',
        //'value' => function($model) { return FHtml::showContent($model-> id, '', 'app_user_transaction', 'id','bigint(20)','app-user-transaction'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    [ //name: transaction_id, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'transaction_id',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: user_id, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'user_id',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: receiver_user_id, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'receiver_user_id',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: object_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'object_id',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: object_type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'object_type',
        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'app_user_transaction', 'object_type','varchar(100)','app-user-transaction'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('app_user_transaction.object_type', 'app_user_transaction', 'object_type', true, 'id', 'name'),
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: amount, dbType: decimal(20,2), phpType: string, size: 20, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'amount',
        'value' => function($model) { return FHtml::showContent($model-> amount, '', 'app_user_transaction', 'amount','decimal(20,2)','app-user-transaction'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: currency, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'currency',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: payment_method, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'payment_method',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: note, dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'note',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    [ //name: time, dbType: varchar(20), phpType: string, size: 20, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'time',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    ],
    [ //name: action, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'action',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    ],
    //[ //name: type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'type',
        //'value' => function($model) { return FHtml::showContent($model-> type, FHtml::SHOW_LABEL, 'app_user_transaction', 'type','varchar(100)','app-user-transaction'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('app_user_transaction.type', 'app_user_transaction', 'type', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: status, dbType: varchar(100), phpType: string, size: 100, allowNull:  
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'status',
        //'value' => function($model) { return FHtml::showContent($model-> status, FHtml::SHOW_LABEL, 'app_user_transaction', 'status','varchar(100)','app-user-transaction'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('app_user_transaction.status', 'app_user_transaction', 'status', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'date',
        //'attribute'=>'created_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-xs-1 nowrap'],
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'created_user',
        //'hAlign'=>'left',
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
    //[ //name: modified_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class'=>'kartik\grid\DataColumn',
        //'attribute'=>'modified_user',
        //'hAlign'=>'left',
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
            return Url::to([$action, 'id' => $model->id]);
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