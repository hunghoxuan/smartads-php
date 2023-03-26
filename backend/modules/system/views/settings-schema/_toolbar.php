<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;

use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'SettingsSchema';
$moduleTitle = 'Settings Schema';
$moduleKey = 'settings_schema';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole))
{
    $createButton = FHtml::a('<i class="glyphicon glyphicon-plus"></i>&nbsp;' . FHtml::t('common', 'Create'), ['create'],
        [
            'role' => $this->params['editType'],
            'data-pjax' =>  $this->params['isAjax'] == true ? 1 : 0,
            'title' => FHtml::t('common', 'title.create'),
            'class' => 'btn btn-success',
            'style' => 'float:left;'
        ]);
}

$deleteButton = '';
if (FHtml::isInRole('', 'delete', $currentRole))
{
    $deleteButton = BulkButtonWidget::widget([
        'buttons' => FHtml::a('<i class="glyphicon glyphicon-trash"></i>',
        ["bulk-delete"],
        [
        "class" => "btn btn-danger",
        'role' => 'modal-remote-bulk',
        'data-confirm' => false, 'data-method' => false,// for overide yii data api
        'data-request-method' => 'post',
        'data-confirm-title' => FHtml::t('common', ''),
        'data-confirm-message' => FHtml::t('common', 'Are you sure to delete?'),
        'style' => 'float:left; margin-left:2px;'
        ]),
        ]);
}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole))
{
$bulkActionButton = '<div class="dropdown pull-left">&nbsp;<button class="btn btn-default" data-toggle="dropdown">'. FHtml::t('common', 'Actions'). '</button>' . DropdownX::widget([
    'items' =>
    \yii\helpers\ArrayHelper::merge(
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('SettingsSchema', 'Object Type') . ']:', 'settings_schema', 'settings_schema', 'object_type')],
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('SettingsSchema', 'Is Column') . ']:', 'settings_schema', 'settings_schema', 'is_column')],
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('SettingsSchema', 'Is Group') . ']:', 'settings_schema', 'settings_schema', 'is_group')],
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('SettingsSchema', 'Is Readonly') . ']:', 'settings_schema', 'settings_schema', 'is_readonly')],
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('SettingsSchema', 'Is Active') . ']:', 'settings_schema', 'settings_schema', 'is_active')],

    [FHtml::buildBulkDividerMenu()],
    [FHtml::buildBulkDeleteMenu()],
        [FHtml::buildPopulateMenu()]

    )
    ]). '</div>';
}

return [
    [
        'content' =>
            $createButton .
            '{toggleData}' .
            $bulkActionButton,
        'options'=> ['class' => 'text-right kv-panel-before']
    ],
];