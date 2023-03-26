<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\components\FHtml;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$moduleName = StringHelper::basename($generator->modelClass);
$moduleTitle = BaseInflector::camel2words($moduleName);
$moduleKey = BaseInflector::camel2id($moduleName, '_');
$tableSchema = $generator->getTableSchema();

echo "<?php\n";
?>

use common\components\FHtml;

$moduleName = '<?= StringHelper::basename($generator->modelClass) ?>';
$moduleTitle = '<?= BaseInflector::camel2words($moduleName) ?>';
$moduleKey = '<?= BaseInflector::camel2id($moduleName, '_') ?>';

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
<?php foreach ($generator->getColumnNames() as $attribute) {
        $column = $tableSchema->columns[$attribute];
        $commentArray = FHtml::toArrayFromDbComment($column->comment);
        $lookup_key = isset($commentArray['lookup']) ? $commentArray['lookup'] : $tableSchema->name;
        $lookup_table = StringHelper::startsWith($lookup_key, '@') ? str_replace('@', '', $lookup_key) : $lookup_key;
        $attribute_label = Inflector::camel2words($attribute);
        if (FHtml::isInArray($attribute, FHtml::getFIELDS_GROUP()) && !FHtml::isInArray($attribute, FHtml::FIELDS_HIDDEN)) { ?>
        <?= "FHtml::buildBulkActionsMenu('', '$lookup_key', '$lookup_table', '$attribute'),\n"; ?>
<?php }
} ?>
        FHtml::buildBulkDividerMenu(), $deleteAllButton]);
}
?>
<div class='row'>
    <div class='col-md-12' style="padding-bottom:15px">
        <div>
            <?= "<?= " ?>$createButton . $deleteButton . $bulkActionButton ?>
        </div>
        <div class='pull-right'>
            <?= "<?= " ?>'{export}' . '{toggleData}' ?>
        </div>
    </div>
</div>