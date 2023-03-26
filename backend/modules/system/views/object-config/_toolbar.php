<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;
use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'ObjectActions';
$moduleTitle = 'Object Actions';
$moduleKey = 'object_actions';

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

$clearButton = '';
if (FHtml::isInRole('', 'delete', $currentRole))
{
    $clearButton = FHtml::a('<i class="glyphicon glyphicon-remove"></i>&nbsp;' . FHtml::t('common', 'Clear'), ['delete-all'],
        [
            'role' => $this->params['editType'],
            'data-pjax' =>  $this->params['isAjax'] == true ? 1 : 0,
            'title' => FHtml::t('common', 'Clear'),
            'class' => 'btn btn-warning',
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
    [FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('ObjectActions', 'Object Type') . ']:', 'object_actions', 'object_actions', 'object_type')],
[FHtml::buildBulkActionsMenu(FHtml::t('common', 'Set'). ' ['. FHtml::t('ObjectActions', 'Is Active') . ']:', 'object_actions', 'object_actions', 'is_active')],
    [FHtml::buildBulkDividerMenu()],
    [FHtml::buildBulkDeleteMenu()]
    )
    ]). '</div>';
}

?>

<div class='row'>
    <div class='col-md-12'>
        <div>
            <?= $clearButton ?>
        </div>
        <div class='pull-right'>
        </div>
    </div>
</div>