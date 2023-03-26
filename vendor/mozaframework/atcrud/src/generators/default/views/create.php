<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use common\components\FHtml;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = '<?=StringHelper::basename($generator->modelClass)?>';
$moduleTitle = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$moduleKey = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$modulePath = '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole($model, 'create', $currentRole)) {
    $controlName = FHtml::settingPageView('_form', 'Form');
} else {
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = FHtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
    <?= "<?php "?>if ($canCreate === true) {<?= " ?>\n" ?>
        <?= "<?= "?>FHtml::render($controlName, $folder, [
            'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
        ]);<?= " ?>\n" ?>
    <?= "<?php "?>} else {<?= " ?>\n" ?>
        <?= "<?= " ?>Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);<?= " ?>\n" ?>
    <?= "<?php "?>}<?= " ?>\n" ?>
</div>