<?php

use common\components\FHtml;

$moduleName = 'ObjectRequest';
$moduleTitle = 'Object Request';
$moduleKey = 'object_request';

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
        FHtml::buildBulkActionsMenu('', 'object_request', 'object_request', 'object_type'),
        FHtml::buildBulkActionsMenu('', 'object_request', 'object_request', 'type'),
        FHtml::buildBulkActionsMenu('', 'object_request', 'object_request', 'is_active'),
        FHtml::buildBulkActionsMenu('', 'object_request', 'object_request', 'user_type'),
        FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}
?>
<div class='row'>
    <div class='col-md-12' style="padding-bottom:15px">
        <div>
            <?= $createButton . $deleteButton . $bulkActionButton ?>
        </div>
        <div class='pull-right'>
            <?= '{export}' . '{toggleData}' ?>
        </div>
    </div>
</div>