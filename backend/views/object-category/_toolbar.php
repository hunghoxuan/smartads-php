<?php

use common\components\FHtml;

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object_category';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');

if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate('<i class="glyphicon glyphicon-plus"></i>', 'Create', 'create?object_type=' . $object_type);
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
        FHtml::buildBulkActionsMenu('', 'object_category', 'object_category', 'parent_id'),
        FHtml::buildBulkActionsMenu('', 'object_category', 'object_category', 'is_active'),
        FHtml::buildBulkActionsMenu('', 'object_category', 'object_category', 'is_top'),
        FHtml::buildBulkActionsMenu('', 'object_category', 'object_category', 'is_hot'),
        FHtml::buildBulkActionsMenu('', 'object_category', 'object_category', 'object_type'),
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