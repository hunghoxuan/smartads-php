<?php

/**
 *
 ***
 * This is the customized model class for table "AppUserFeedback".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'AppUserFeedback';
$moduleTitle = 'App User Feedback';
$moduleKey = 'app-user-feedback';
$modulePath = 'app-user-feedback';
$object_type = 'app-user-feedback';
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
/* @var $model backend\modules\app\models\AppUserFeedback */
?>
<div class="app-user-feedback-update">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>