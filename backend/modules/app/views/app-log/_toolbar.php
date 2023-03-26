<?php

use common\components\FHtml;

$moduleName = 'AppLog';
$moduleTitle = 'App Log';
$moduleKey = 'app_log';

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
        FHtml::buildBulkActionsMenu('', 'app_log', 'app_log', 'status'),
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