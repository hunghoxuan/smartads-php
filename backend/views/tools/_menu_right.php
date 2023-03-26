<?php
use common\components\CrudAsset;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'System';
$moduleTitle = 'System';
$moduleKey = 'System';
$action = FHtml::currentAction();
$links = [
    'cache', 'backup', 'copy','database', 'setup'
];
?>
<div class="col-md-12 form-label" style="margin-bottom: 30px; padding: 10px">
    <span class="caption-title font-blue-madison bold uppercase">
        <?= FHtml::t('common', $moduleTitle) ?>
    </span>
    <div class="pull-right">
<?php
    $i = count($links) + 1;
    foreach ($links as $name => $link) {
        $i -= 1;
        if (is_numeric($name))
            $name = $link;
?>
<a class="btn btn-lg <?= ($action == $name || $action == '') ? 'btn-primary' : 'btn-default' ?> pull-right" href="<?= FHtml::createUrl("tools/$name") ?>"><?= $i . '. ' . FHtml::t('common', $name) ?> </a>
<?php } ?>
    </div>
</div>