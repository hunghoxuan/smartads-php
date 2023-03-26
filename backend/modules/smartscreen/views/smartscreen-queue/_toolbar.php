<?php

use common\components\FHtml;

$moduleName = 'SmartscreenQueue';
$moduleTitle = 'Smartscreen Queue';
$moduleKey = 'smartscreen_queue';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate();
}

$deleteButton = '';
$deleteAllButton = '';
if (FHtml::isInRole('', 'delete', $currentRole)) {
    $deleteButton = FHtml::buttonDeleteBulk();
    $deleteAllButton = FHtml::buildDeleteAllMenu();
}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole)) {
    $bulkActionButton = FHtml::buttonBulkActions([
        FHtml::buildBulkActionsMenu('', 'smartscreen_queue', 'smartscreen_queue', 'status'),
        FHtml::buildBulkActionsMenu('', 'smartscreen_queue', 'smartscreen_queue', 'is_active'),
        FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}
?>
<div class='row'>
    <div class='col-md-12'>
        <div>
            <?= $createButton . $deleteButton . $bulkActionButton ?>
        </div>
        <div class='pull-right'>
            <?= '{export}' . '{toggleData}' ?>
        </div>
    </div>
</div>