<?php

use common\components\FHtml;

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';
$modulePath = 'object-category';
$object_type = 'object-category';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole($model, 'update', $currentRole)) {
    $controlName = '_form.php';
} else {
    $controlName = '_view.php';
}

$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */
?>
<div class="object-category-update">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>