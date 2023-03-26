<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppNotification".
*/
use common\components\FHtml;
use yii\helpers\Html;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = 'AppNotification';
$moduleTitle = 'App Notification';
$moduleKey = 'app-notification';
$modulePath = 'app-notification';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole('', 'create', $currentRole))
{
    $controlName = FHtml::settingPageView('_form', 'Form');
}
else
{
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = FHtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppNotification */

?>
<div class="app-notification-create">
    <?php if($canCreate === true) { echo  FHtml::render($controlName, $folder, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]);} else { ?>
    <?=  Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);} ?>
</div>
