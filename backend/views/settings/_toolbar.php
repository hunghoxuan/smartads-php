<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;

use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'delete', $currentRole))
{
    $createButton = FHtml::buttonDeleteAll();
}

$deleteButton = '';
if (FHtml::isInRole('', 'delete', $currentRole))
{
    $deleteButton = FHtml::buttonDeleteBulk();
}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole)) {
    $bulkActionButton = FHtml::buttonBulkActions(
        [
            FHtml::buildChangeFieldMenu($moduleKey, 'group'),

        ]
    );
}
?>

<div class='row'>
    <div class='col-md-12'>
        <div>
            <?= $createButton ?>
        </div>
    </div>
</div>
