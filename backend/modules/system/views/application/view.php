<?php

/**
 *
 ***
 * This is the customized model class for table "Application".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\Application */

$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'Application';
$moduleTitle = 'Application';
$moduleKey = 'application';
$modulePath = 'application';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

$controlName = FHtml::settingPageView('_form', 'Form');
$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

?>
<div class="application-view">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'edit_type' => FHtml::EDIT_TYPE_INLINE, 'display_type' => FHtml::DISPLAY_TYPE_TABLE
    ]) ?>
</div>