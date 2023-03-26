<?php

use common\components\FHtml;

$moduleName = 'SettingsApi';
$moduleTitle = 'Settings Api';
$moduleKey = 'settings_api';

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
        FHtml::buildBulkActionsMenu('', 'settings_api', 'settings_api', 'is_active'),
        FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}

$swaggerButton = FHtml::showLinkButton('Swagger', FHtml::getFullURL('/apps/swagger'), 'btn btn-warning');
?>
<div class='row'>
    <div class='col-md-12' style="padding-bottom:15px">
        <div>
            <?= $createButton . $deleteButton . $bulkActionButton . $swaggerButton ?>
        </div>
        <div class='pull-right'>
            <?= '{export}' . '{toggleData}' ?>
        </div>
    </div>
</div>