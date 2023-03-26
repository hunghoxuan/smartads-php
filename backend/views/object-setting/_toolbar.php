<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;

use kartik\nav\NavX;
use kartik\dropdown\DropdownX;

$moduleName = 'ObjectSetting';
$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate();
}

$deleteButton = '';
if (FHtml::isInRole('', 'delete', $currentRole)) {
    $deleteButton = FHtml::buttonDeleteBulk();
    $deleteAllButton = FHtml::buttonDeleteAll();

}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole)) {
    $bulkActionButton = FHtml::buttonBulkActions(
                    [FHtml::buildChangeFieldMenu('object_setting', 'is_active')
                        ]
                );
}

return [
    [
        'content' =>
            $createButton . $deleteButton . $deleteAllButton .
            '{toggleData}' .
            $bulkActionButton,
        'options' => ['class' => 'text-right kv-panel-before']
    ],
];