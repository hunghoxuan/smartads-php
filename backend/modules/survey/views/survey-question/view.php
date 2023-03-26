<?php

use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $model backend\modules\survey\models\SurveyQuestion */

$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'SurveyQuestion';
$moduleTitle = 'Survey Question';
$moduleKey = 'survey-question';
$modulePath = 'survey-question';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

$controlName = FHtml::settingPageView('_form', 'Form');
$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

?>
<div class="survey-question-view">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'edit_type' => FHtml::EDIT_TYPE_INLINE, 'display_type' => FHtml::DISPLAY_TYPE_TABLE
    ]) ?>
</div>