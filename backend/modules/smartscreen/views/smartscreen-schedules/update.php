<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenSchedules".
*/

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen-schedules';
$modulePath = 'smartscreen-schedules';
$object_type = 'smartscreen-schedules';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole))
{
    $controlName = FHtml::settingPageView('_form', 'Form');
}
else
{
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenSchedules */
?>
<div class="smartscreen-schedules-update">
    <?= FHtml::render($controlName, $folder, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>
