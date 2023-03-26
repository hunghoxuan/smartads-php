<?php
use common\components\CrudAsset;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Api';
$moduleTitle = 'Api';
$moduleKey = 'api';
$action = FHtml::currentAction();

?>

<a class="btn btn-lg <?= ($action == 'setup' || $action == '') ? 'btn-primary' : 'btn-default' ?> col-md-12" href="<?= FHtml::createUrl('tools/tools/setup') ?>">1. <?= FHtml::t('common', 'Setup') ?> </a>
<a class="btn btn-lg <?= $action == 'backup' ? 'btn-primary' : 'btn-default' ?> col-md-12" href="<?= FHtml::createUrl('tools/tools/backup') ?>">2. <?= FHtml::t('common', 'Backup & Restore') ?> </a>
<a class="btn btn-lg <?= $action == 'cache' ? 'btn-primary' : 'btn-default' ?> col-md-12" href="<?= FHtml::createUrl('tools/tools/cache') ?>">3. <?= FHtml::t('common', 'Cache') ?> </a>