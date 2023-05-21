<?php

/**
 * 

 * 
 * This is the customized model class for table "SmartscreenStation".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole($moduleName, 'edit');
$hisEnabled = \backend\modules\smartscreen\Smartscreen::settingHISEnabled();
//$hisEnabled = false;

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    //    [
    //        'class' => 'kartik\grid\SerialColumn',
    //        'width' => '30px',
    //    ],
    //    [
    //        'class' => 'kartik\grid\ExpandRowColumn',
    //        'width' => '30px',
    //        'value' => function ($model, $key, $index, $column) {
    //            return GridView::ROW_COLLAPSED;
    //        },
    //        'detail' => function ($model, $key, $index, $column) {
    //            return Yii::$app->controller->renderPartial('_view', ['model' => $model, 'print' => false]);
    //        },
    //        'headerOptions' => ['class' => 'kartik-sheet-style'],
    //        'expandOneOnly' => false
    //    ],

    [ //name: branch_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'channel_id',
        'value' => function ($model) {
            if (empty($model->channel_id))
                return FHtml::NULL_VALUE;
            else
                return FHtml::showContent($model->channel_id, FHtml::SHOW_LOOKUP, '@smartscreen_channels', 'channel_id', 'varchar(100)', 'smartscreen-channels');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'visible' => empty(FHtml::getRequestParam('channel_id')),
        'contentOptions' => ['class' => 'col-md-2 nowrap', 'style' => 'text-decoration:uppercase;font-weight:bold'],
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'group' => true,
        'groupedRow' => true,
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('@smartscreen_channels', 'smartscreen_channel', 'channel_id', true, 'id', 'name'),
    ],
    //    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
    //        'class' => 'kartik\grid\DataColumn',
    //        'format' => 'html',
    //        'attribute' => 'id',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
    //        'value' => function ($model) {
    //            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'smartscreen_station', 'id', 'int(11)', 'smartscreen-station') . '</b>';
    //        },
    //        'hAlign' => 'center',
    //        'vAlign' => 'middle',
    //        'width' => '50px',
    //    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'name', $form_type, true),
        'format' => 'raw',
        'attribute' => 'name',
        'value' => function ($model) {
            $css = $model->status ? "success" : "default";
            $result = $model->name . " <label class='label label-$css'>id: $model->id</label>";

            if (!empty($model->description))
                $result .= "<br/><small style='color: darkgrey;margin-top:10px'>" . $model->description . "</small>";
            if (!empty($model->ScreenName))
                $result .= "<br/><small style='color: darkgrey;margin-top:10px'>" . $model->ScreenName . "</small>";

            return $result;
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],

    ],
    !$hisEnabled ? null :
        [
            'attribute' => 'dept_id',
            'contentOptions' => ['class' => 'col-md-1 nowrap'],

        ],
    !$hisEnabled ? null :
        [
            'attribute' => 'room_id',
            'contentOptions' => ['class' => 'col-md-1 nowrap'],

        ],


    //    [
    //        'attribute' => 'ScreenName',
    //        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //        'value' => function ($model) {
    //            $result = (!empty($model->ScreenName) ? ($model->ScreenName . '<br/>') : '') . '<p>' . $model->description . '</p>';
    //            return $result;
    //        },
    //
    //    ],

    //    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
    //        'class' => FHtml::getColumnClass($moduleName, 'description', $form_type, true),
    //        'format' => 'raw',
    //        'attribute' => 'description',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'description', $form_type, true),
    //        'hAlign' => 'left',
    //        'vAlign' => 'middle',
    //
    //        'contentOptions' => ['class' => 'col-md-2 nowrap'],
    //    ],
    [
        'attribute' => 'ime',
        'contentOptions' => ['class' => 'col-md-2 nowrap'],
        'value' => function ($model) {
            $disk_size =  !empty($model->disk_storage) ? 'Free storage: ' . FHtml::showNumberInFileSize($model->disk_storage) : '';
            $result = "$model->ime";
            if (!empty($model->MACAddress))
                $result .= "<br/><small style='color: darkgrey;margin-top:10px'> $model->MACAddress </small>";
            if (!empty($disk_size))
                $result .= "<br/><small> $disk_size </small>";
            if (!empty($model->LicenseKey))
                $result .= "<br/><small class='label-success' style='font-size:80%;padding-left: 5px; padding-right: 5px'>$model->LicenseKey</small>";
            return $result;
        },

    ],


    //    [ //name: ScreenName, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    //        'class' => FHtml::getColumnClass($moduleName, 'ScreenName', $form_type, true),
    //        'format'=>'raw',
    //        'attribute'=>'ScreenName',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'ScreenName', $form_type, true),
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //                            'widgetClass'=> 'kartik\datecontrol\InputControl',
    //                            'options'=>[
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],
    //    [ //name: MACAddress, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    //        'class' => FHtml::getColumnClass($moduleName, 'MACAddress', $form_type, true),
    //        'format'=>'raw',
    //        'attribute'=>'MACAddress',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'MACAddress', $form_type, true),
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //                            'widgetClass'=> 'kartik\datecontrol\InputControl',
    //                            'options'=>[
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],
    //    [ //name: LicenseKey, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    //        'class' => FHtml::getColumnClass($moduleName, 'LicenseKey', $form_type, true),
    //        'format'=>'raw',
    //        'attribute'=>'LicenseKey',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'LicenseKey', $form_type, true),
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
    //                            'widgetClass'=> 'kartik\datecontrol\InputControl',
    //                            'options'=>[
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],

    //    [ //name: branch_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
    //        'class'=>'kartik\grid\DataColumn',
    //        'format'=>'raw',
    //        'attribute'=>'branch_id',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'branch_id', $form_type, true),
    //        'value' => function($model) { return FHtml::showContent($model-> branch_id, FHtml::SHOW_LOOKUP, '@qms_branch', 'branch_id','varchar(100)','smartscreen-station'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('@qms_branch', 'qms_branch', 'branch_id', true, 'id', 'name'),
    //    ],

    //    [
    //        'class'=>'kartik\grid\DataColumn',
    //        'attribute' => 'last_activity',
    //        'value' => function ($model) {
    //            return $model->last_activity;
    //        },
    //        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    //    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'last_update',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model) {
            return \backend\modules\smartscreen\Smartscreen::showDeviceLastUpdate($model);
        },
    ],

    [
        'class' => 'kartik\grid\DataColumn',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => FHtml::getComboArray('smartscreen_station', 'smartscreen_station', 'status', true, 'id', 'name'),
        'attribute' => 'status',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model) {
            if (!empty($model->status)) {
                $result = '<div class="glyphicon glyphicon-ok text-success"> ' . '</div>';
            } else {
                $result = '<span class="glyphicon glyphicon-remove text-danger"> '  .  '</span>';
            }
            return FHtml::showBooleanEditable($result, $model->status, 'status', $model->id, 'smartscreen_station');
            return $result;
        },
    ],
    [
        'attribute' => 'Actions',
        'label' => FHtml::t('Schedules'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'content' => function ($model) {
            $result = '';
            $url = FHtml::createUrl('/smartscreen/smartscreen-schedules/index', ['device_id' => $model->id]);
            $result .= "<a style='float:left; margin: 2px' href='$url' title='Manage Schedules' data-pjax='0' class='label label-warning label-xs'> " . FHtml::t('Schedules') . " </a>";
            return $result;
        }
    ],
    [
        'attribute' => 'Actions',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'content' => function ($model) {
            $result = '';

            $url = FHtml::createUrl('/smartscreen/schedules/index', ['device_id' => $model->id, 'ime' => $model->ime, 'layout' => 'no']);
            $result .= "<a style='float:left; margin: 2px' href='$url' title='Preview' target='_blank' data-pjax='0' class='label label-primary label-xs'> <span class=\"glyphicon glyphicon-eye-open\"></span> </a>";

            $url = FHtml::createUrl('/smartscreen/api/schedules', ['device_id' => $model->id, 'ime' => $model->ime, 'debug' => 1]);
            $result .= "<a style='float:left; margin: 2px' href='$url' target='_blank' data-pjax='0' class='label label-default label-xs'> API </a>";

            $url = FHtml::createUrl('/smartscreen/api/schedules', ['device_id' => $model->id, 'ime' => $model->ime, 'log' => 'true']);
            $result .= "<a style='float:left; margin: 2px' href='$url' target='_blank' data-pjax='0' class='label label-danger label-xs'> Log </a>";

            return $result;
        }
    ],

    !$hisEnabled ? null :
        [
            'class' => 'kartik\grid\DataColumn',
            'attribute' => 'HIS',
            'contentOptions' => ['class' => 'col-md-1 nowrap'],
            'value' => function ($model) {
                $url = \backend\modules\smartscreen\Smartscreen::getHisContentUrl($model, ['api' => true]);
                $result = "<a style='float:left; margin: 2px' href='$url' target='_blank' data-pjax='0' class='label label-default label-xs'> API </a>";

                $url = \backend\modules\smartscreen\Smartscreen::getHisContentUrl($model);
                $result .= "<a style='float:left; margin: 2px' href='$url' target='_blank' data-pjax='0' class='label label-primary label-xs'> HIS </a>";
                return $result;
            },
        ],
    //    [
    //        'class'=>'kartik\grid\DataColumn',
    //        'attribute' => 'active',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'value' => function ($model) {
    //
    //
    //        },
    //    ],

    //    [ //name: script_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
    //        'class'=>'kartik\grid\DataColumn',
    //        'format'=>'raw',
    //        'attribute'=>'script_id',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'script_id', $form_type, true),
    //        'value' => function($model) { return FHtml::showContent($model-> script_id, FHtml::SHOW_LOOKUP, '@smartscreen_scripts', 'script_id','varchar(100)','smartscreen-station'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('@smartscreen_scripts', 'smartscreen_scripts', 'script_id', true, 'id', 'name'),
    //    ],
    //    [ //name: script_update, dbType: date, phpType: string, size: , allowNull: 1
    //        'class' => FHtml::getColumnClass($moduleName, 'script_update', $form_type, true),
    //        'format'=>'raw', // date
    //        'attribute'=>'script_update',
    //        'visible' => FHtml::isVisibleInGrid($moduleName, 'script_update', $form_type, true),
    //        'hAlign'=>'right',
    //        'vAlign'=>'middle',
    //        'width'=>'50px',
    //        'editableOptions'=>[
    //                            'size'=>'md',
    //                            'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
    //                            'widgetClass'=> 'kartik\datecontrol\DateControl',
    //                            'options'=>[
    //                                'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
    //                                'displayFormat'=> FHtml::settingDateFormat(),
    //                                'saveFormat'=>'php:Y-m-d',
    //                                'options'=>[
    //                                    'pluginOptions'=>[
    //                                        'autoclose'=>true
    //                                    ]
    //                                ]
    //                            ]
    //                        ],
    //    ],
    //[ //name: created_date, dbType: date, phpType: string, size: , allowNull: 1 
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
    //'displayFormat'=> FHtml::settingDateFormat(),
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
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' => [
            'view' => false,
            'update' => FHtml::isInRole($moduleKey, 'update', $currentRole),
            'delete' => FHtml::isInRole($moduleKey, 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];
