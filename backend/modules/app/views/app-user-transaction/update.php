<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppUserTransaction".
*/

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'AppUserTransaction';
$moduleTitle = 'App User Transaction';
$moduleKey = 'app-user-transaction';
$modulePath = 'app-user-transaction';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();

$form_layout = FHtml::config(FHtml::SETTINGS_FORM_LAYOUT, '_3cols');

if (FHtml::isInRole('', 'update', $currentRole))
{
    $controlName = FHtml::settingPageView('Form', '_form_3cols');
}
else
{
    $controlName = FHtml::settingPageView('View', '_view_3cols');
}

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUserTransaction */
?>
<div class="app-user-transaction-update hidden-print">
    <?= $this->render($controlName, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
<div class="visible-print">
    <?= $this->render(FHtml::settingPageView('View Print', '_view_print'), [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
