<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;
use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'MediaFile';
$moduleTitle = 'Media File';
$moduleKey = 'media_file';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole($moduleName, 'create', $currentRole))
{
    $createButton = FHtml::a('<i class="glyphicon glyphicon-plus"></i>&nbsp;' . FHtml::t('common', 'button.create'), ['create'],
        [
            'role' => $this->params['editType'],
            'data-pjax' =>  $this->params['isAjax'] == true ? 1 : 0,
            'title' => FHtml::t('common', 'title.create'),
            'class' => 'btn btn-success',
            'style' => 'float:left;'
        ]);
}

$deleteButton = '';
if (FHtml::isInRole($moduleName, 'delete', $currentRole))
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
        'data-confirm-message' => FHtml::t('common', 'message.confirmdelete'),
        'style' => 'float:left; margin-left:2px;'
        ]),
        ]);
}

$bulkActionButton = '';
if (FHtml::isInRole($moduleName, 'action', $currentRole))
{
    $bulkActionButton = '<div class="dropdown pull-left">&nbsp;<button class="btn btn-default" data-toggle="dropdown">'. FHtml::t('common', 'Actions'). '</button>' . DropdownX::widget([
    'items' =>
    \yii\helpers\ArrayHelper::merge(
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('MediaFile', 'File Type') . ']:', 'media_file', 'media_file', 'file_type')],
[FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('MediaFile', 'Is Active') . ']:', 'media_file', 'media_file', 'is_active')],
    [FHtml::buildBulkDividerMenu()],
    [FHtml::buildBulkDeleteMenu()]
    )
    ]). '</div>';
}

return [
    [
        'content' =>
            $createButton .
            '{export}' .
            FHtml::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
            '{toggleData}' .
            $bulkActionButton,
        'options'=> ['class' => 'text-right kv-panel-before']
    ],
];