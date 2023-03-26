<?php

use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCollection */

$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'ObjectCollection';
$moduleTitle = 'Object Collection';
$moduleKey = 'object-collection';
$modulePath = 'object-collection';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

$controlName = FHtml::settingPageView('_form', 'Form');
$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

?>
<div class="object-collection-view">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'canEdit' => false, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'edit_type' => FHtml::EDIT_TYPE_INLINE, 'display_type' => FHtml::DISPLAY_TYPE_TABLE
    ]) ?>
</div>