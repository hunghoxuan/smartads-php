<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;
use kartik\nav\NavX;
use kartik\dropdown\DropdownX;
use yii\helpers\BaseInflector;

$moduleName = 'User';
$moduleTitle = 'User';
$moduleKey = 'user';

$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate();
    $populateButton = FHtml::buttonAction('<i class="glyphicon glyphicon-flash"></i>&nbsp;' , 'Populate', 'populate', 'primary', true);

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
            FHtml::buildChangeFieldMenu($moduleKey, 'type'),
            FHtml::buildChangeFieldMenu($moduleKey, 'status')
        ]
    );
}
?>

<div class='row'>
    <div class='col-md-12'>
        <div>
            <?= $createButton . $deleteButton . $deleteAllButton . $bulkActionButton ?>
        </div>
        <div class='pull-right'>
            <?=  '{export}' . '{toggleData}' ?>
        </div>
    </div>
</div>