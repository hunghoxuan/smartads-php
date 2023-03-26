<?php

use common\components\FHtml;

$moduleName = 'ObjectContent';
$moduleTitle = 'Object Content';
$moduleKey = 'object_content';

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
        FHtml::buildBulkActionsMenu('', 'object_content', 'object_content', 'object_type'),
        FHtml::buildBulkActionsMenu('', 'object_content', 'object_content', 'is_active'),
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