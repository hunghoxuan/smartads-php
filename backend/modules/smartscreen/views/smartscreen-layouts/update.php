<?php

/**
 *
 ***
 * This is the customized model class for table "SmartscreenLayouts".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'SmartscreenLayouts';
$moduleTitle = 'Smartscreen Layouts';
$moduleKey = 'smartscreen-layouts';
$modulePath = 'smartscreen-layouts';
$object_type = 'smartscreen-layouts';
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
/* @var $model backend\modules\smartscreen\models\SmartscreenLayouts */
?>
<div class="smartscreen-layouts-update">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'full_frame' => $full_frame, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>