<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;
use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen_schedules';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
$updateButton = '';

if (FHtml::isInRole('', 'create', $currentRole))
{
    $createButton = FHtml::a('<i class="fa fa-plus"></i> ' . FHtml::t('button', 'Create'), ['create', 'channel_id' => $channel_id, 'date' => $date, 'device_id' => $device_id], ['class' => 'btn btn-success', 'data-pjax' => 0]);
}

if (FHtml::isInRole('', 'edit', $currentRole))
{
    $updateButton = FHtml::a('<i class="fa fa-edit"></i> ' . FHtml::t('button', 'Update'), ['update', 'channel_id' => $channel_id, 'date' => $date, 'device_id' => $device_id], ['class' => 'btn btn-primary', 'data-pjax' => 0]);
}

$deleteButton = '';  $deleteAllButton = '';
if (FHtml::isInRole('', 'delete', $currentRole))
{
    $deleteButton = FHtml::buttonDeleteBulk();
    $deleteAllButton = FHtml::buildDeleteAllMenu();
}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole))
{
    $bulkActionButton = FHtml::buttonBulkActions([
    FHtml::buildBulkActionsMenu('', 'smartscreen_schedules', 'smartscreen_schedules', 'type'),
    FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}

?>

<div class='row'>
    <div class='col-md-12'>
           <span class="pull-left"><?= FHtml::buttonSearch() ?></span>
    </div>
</div>