<?php

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SmartscreenChannels';
$moduleTitle = 'Smartscreen Channels';
$moduleKey = 'smartscreen-channels';
$object_type = 'smartscreen_channels';

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
            return Yii::$app->controller->renderPartial('_view', ['model' => $model, 'print' => false]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
    [
        'attribute' => 'id',
        'width' => '50px',
    ],
    [
        'attribute' => 'name',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    ],
    [
        'attribute' => 'description',
        'contentOptions' => ['class' => 'col-md-3 nowrap'],
    ],
    [
        'class'          => FHtml::COLUMN_VIEW,
        'attribute'      => 'Schedules',
        'label'          => FHtml::t('Schedules'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
        'value'          => function ($model) {
            $url = FHtml::createUrl('/smartscreen/smartscreen-schedules', ['channel_id' => $model->id]);
            $result = "<a style='float: left;' href='$url' data-pjax='0' class='label label-warning label-xs'><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;" . FHtml::t('Schedules') . " </a>";

            return $result;
        },
    ],
    [
        'class'          => FHtml::COLUMN_VIEW,
        'attribute'      => 'Devices',
        'label'          => FHtml::t('Devices'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
        'value'          => function ($model) {
            $url = FHtml::createUrl('/smartscreen/smartscreen-station', ['channel_id' => $model->id]);
            $result = "<a style='float: left;' href='$url' data-pjax='0' class='label label-primary label-xs'><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;" . FHtml::t('Devices') . " </a>";
            return $result;
        },
    ],

    [
        'class' => FHtml::COLUMN_EDIT,

        'attribute' => 'is_active',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
    ],
    // [
    //     'class' => FHtml::COLUMN_EDIT,
    //     'attribute' => 'is_default',
    //     'contentOptions' => ['class' => 'col-md-1 nowrap'],
    // ],
    //[ 
    //'attribute' => 'created_date',
    //'width' => '50px',
    //],
    //[ 
    //'attribute' => 'created_user',
    //'width' => '80px',
    //'filterType' => GridView::FILTER_SELECT2, 
    //'filter' => FHtml::getComboArray('smartscreen_channels', 'smartscreen_channels', 'created_user', true, 'id', 'name'),
    //],
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
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete')
        ],
    ],
];
