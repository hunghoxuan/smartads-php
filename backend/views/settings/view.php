<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Settings".
 */
use yii\helpers\Html;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$folder = '';
$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';
$modulePath = 'settings';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_view';
} else {
    $controlName = $folder . '_view';
}

?>
<div class="settings-view hidden-print">
    <?= $this->render($controlName, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
<div class="visible-print">
    <?= $this->render($folder . '_view_print', [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>