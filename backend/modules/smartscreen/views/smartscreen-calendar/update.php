<?php

use common\components\FHtml;

$moduleName = 'SmartscreenCalendar';
$moduleTitle = 'Smartscreen Calendar';
$moduleKey = 'smartscreen-calendar';
$modulePath = 'smartscreen-calendar';
$object_type = 'smartscreen-calendar';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = FHtml::settingPageView('_form', 'Form');
} else {
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenCalendar */
?>
<div class="smartscreen-calendar-update">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>