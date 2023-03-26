<?php

use common\components\FHtml;

$moduleName = 'AppVersion';
$moduleTitle = 'App Version';
$moduleKey = 'app_version';

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
        FHtml::buildBulkActionsMenu('', 'app_version', 'app_version', 'is_active'),
        FHtml::buildBulkActionsMenu('', 'app_version', 'app_version', 'is_default'),
        FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}
?>
<div class='row'>
    <div class='col-md-12'>
        <div>
            <?= $createButton . $deleteButton ?>
        </div>
    </div>
</div>