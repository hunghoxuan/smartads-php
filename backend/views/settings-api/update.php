<?php

use common\components\FHtml;

$moduleName = 'SettingsApi';
$moduleTitle = 'Settings Api';
$moduleKey = 'settings-api';
$modulePath = 'settings-api';
$object_type = 'settings-api';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole($model, 'update', $currentRole)) {
    $controlName = FHtml::settingPageView('_form', 'Form');
} else {
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\SettingsApi */
?>
<div class="settings-api-update">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>