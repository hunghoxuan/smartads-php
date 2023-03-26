<?php

use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenScripts */

$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'SmartscreenScripts';
$moduleTitle = 'Smartscreen Scripts';
$moduleKey = 'smartscreen-scripts';
$modulePath = 'smartscreen-scripts';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

$controlName = FHtml::settingPageView('_form', 'Form');
$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

?>
<div class="smartscreen-scripts-view">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'edit_type' => FHtml::EDIT_TYPE_INLINE, 'display_type' => FHtml::DISPLAY_TYPE_TABLE
    ]) ?>
</div>