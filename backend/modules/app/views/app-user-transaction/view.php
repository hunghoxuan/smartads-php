<?php

/**
 *
 ***
 * This is the customized model class for table "AppUserTransaction".
 */

use yii\helpers\Html;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserTransaction */

$folder = '';
$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';
$modulePath = 'app-user-transaction';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');
$form_layout = FHtml::config(FHtml::SETTINGS_FORM_LAYOUT, '_3cols');

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = FHtml::settingPageView('View', '_view_3cols');
} else {
    $controlName = FHtml::settingPageView('View', '_view_3cols');
}

?>
<div class="app-user-transaction-view hidden-print">
    <?= $this->render($controlName, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
<div class="visible-print">
    <?= $this->render(FHtml::settingPageView('View Print', '_view_print'), [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>