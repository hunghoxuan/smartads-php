<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SmartscreenScripts';
$moduleTitle = 'Smartscreen Scripts';
$moduleKey = 'smartscreen-scripts';
$object_type = 'smartscreen_scripts';

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
    ],
    */
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '30px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return $model->script_content;
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
//    [
//        'attribute' => 'id',
//        'width' => '50px',
//    ],
    [ 
        'attribute' => 'name',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
//    [
//        'attribute' => 'Logo',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'TopBanner',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'BotBanner',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'ClipHeader',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'ClipFooter',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
    [ 
        'attribute' => 'ScrollText',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    [ 
        'attribute' => 'Clipnum',
        'width' => '50px',
    ],
//    [
//        'attribute' => 'Clip1',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'Clip2',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
//    [
//        'attribute' => 'Clip3',
//        'contentOptions' => ['class' => 'col-md-1 nowrap'],
//    ],
    //[ 
        //'attribute' => 'Clip4',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip5',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip6',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip7',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip8',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip9',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip10',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip11',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip12',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip13',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'Clip14',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    [
        'attribute' => 'CommandNumber',
        'width' => '50px',
    ],
    //[ 
        //'attribute' => 'Line1',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line2',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line3',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line4',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line5',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line6',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line7',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line8',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line9',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line10',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line11',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line12',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line13',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line14',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line15',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'Line16',
        //'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //],
    //[ 
        //'attribute' => 'scripts_content',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    //[ 
        //'attribute' => 'scripts_file',
        //'contentOptions' => ['class' => 'col-md-1 nowrap'],
    //],
    [
        'attribute' => 'ReleaseDate',
        'width' => '50px',
    ],
    //[ 
        //'attribute' => 'sort_order',
        //'width' => '50px',
    //],
//    [
//        'attribute' => 'is_active',
//        'width' => '20px',
//    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' => [
            'view' => FHtml::isInRole('', 'view', $currentRole),
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'View'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'Update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'Delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete')
        ],
    ],
];