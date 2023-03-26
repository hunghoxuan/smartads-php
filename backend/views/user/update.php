<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "User".
*/

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'User';
$moduleTitle = 'User';
$moduleKey = 'user';
$modulePath = 'user';
$object_type = 'user';
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
/* @var $model backend\models\User */
?>
<div class="user-update">
    <?= FHtml::render($controlName, $folder, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'object_type' => $object_type
    ]) ?>
</div>

