<?php

use yii\helpers\Html;
use common\components\FHtml;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = 'SettingsApi';
$moduleTitle = 'Settings Api';
$moduleKey = 'settings-api';
$modulePath = 'settings-api';
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
/* @var $model backend\modules\system\models\SettingsApi */

?>
<div class="settings-api-create">
    <?php if ($canCreate === true) { ?>
        <?= FHtml::render($controlName, $folder, [
            'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
        ]); ?>
    <?php } else { ?>
        <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']); ?>
    <?php } ?>
</div>