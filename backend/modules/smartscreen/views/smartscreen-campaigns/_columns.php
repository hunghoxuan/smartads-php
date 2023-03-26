<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "SmartscreenSchedules".
 */

use common\components\FHtml;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use backend\modules\smartscreen\Smartscreen;
use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenContent;

$currentRole = FHtml::getCurrentRole();
$moduleName  = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey   = 'smartscreen-schedules';
$object_type = 'smartscreen_schedules';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
	[
		'class' => 'common\widgets\grid\CheckboxColumn',
		'width' => '20px',
	],

    [
        'class' => FHtml::COLUMN_VIEW,

        'attribute'=>'name',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'value'          => function($model) {
            return $model->name . " <label class='label label-success'>$model->id</label>";
        },
    ],

	[
		'class'          => FHtml::COLUMN_VIEW,
		'attribute'      => 'content_id',
		'label'          => FHtml::t('common', 'Devices'),
		'contentOptions' => ['class' => 'col-md-4 nowrap text-left'],
		'value'          => function($model) {
            return $model->showPreview('device') ;

		},
	],
    [
        'class'          => FHtml::COLUMN_VIEW,
        'attribute'      => 'Time',
        'label'          => FHtml::t('common', 'Time'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
        'value'          => function($model) {
            return $model->showPreview( 'datetime');

        },
    ],
    [
        'class'          => FHtml::COLUMN_VIEW,
        'attribute'      => 'Actions',
        'label'          => FHtml::t('Schedules'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-center'],
        'value'          => function($model) {
            $url = FHtml::createUrl('/smartscreen/smartscreen-schedules', ['campaign_id' => $model->id]);
            $result = "<a href='$url' data-pjax='0' class='label label-warning label-xs'><span class=\"glyphicon glyphicon-th-list\"></span> </a>";
            return $result;
        },
    ],
    [
        'attribute'      => 'is_active',
        'label'          => FHtml::t('common', 'Status'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
    ],

	[
		'class'          => 'kartik\grid\ActionColumn',
		'dropdown'       => $this->params['buttonsType'], // Dropdown or Buttons
		'hAlign'         => 'center',
		'vAlign'         => 'middle',
		'width'          => '80px',
		'urlCreator'     => function($action, $model) {
            $params = Smartscreen::getCurrentParams([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id', 'channel_id'])]);
			return FHtml::createBackendActionUrl($params, ['device_id', 'action', 'date', 'date_end']);
		},
		'visibleButtons' => [
			'view'   => false,
			'update' => function($model, $currentRole) { return empty($model->id) ? false : FHtml::isInRole('', 'update', $currentRole); },
			'delete' => function($model, $currentRole) { return empty($model->id) ? false : FHtml::isInRole('', 'delete', $currentRole); }
		],
		'viewOptions'    => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'View'), 'data-toggle' => 'tooltip'],
		'updateOptions'  => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'Update'), 'data-toggle' => 'tooltip'],
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