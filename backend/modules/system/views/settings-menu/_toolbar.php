<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;

use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'SettingsMenu';
$moduleTitle = 'Cms Menu';
$moduleKey = 'settings_menu';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate();
}

$deleteButton = ''; $deleteAllButton = '';
if (FHtml::isInRole('', 'delete', $currentRole)) {
    $deleteButton = FHtml::buttonDeleteBulk();
    $deleteAllButton = FHtml::buttonDeleteAll();

}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole)) {
    $bulkActionButton = FHtml::buttonBulkActions(
        [
            FHtml::buildChangeFieldMenu($moduleKey, 'is_active'),

        ]
    );
}

return [
    [
        'content' =>
            $createButton . $deleteButton . $deleteAllButton .
            '{toggleData}' .
            $bulkActionButton,
        'options'=> ['class' => 'text-right kv-panel-before']
    ],
];