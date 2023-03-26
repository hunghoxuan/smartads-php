<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsLookup".
*/
use yii\helpers\Html;
use common\components\FHtml;


$currentRole = FHtml::getCurrentRole();
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$canCreate = true;

$moduleName = 'SettingsLookup';
$moduleTitle = 'Settings Lookup';
$moduleKey = 'settings-lookup';
$modulePath = 'settings-lookup';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');

$form_layout = FHtml::config(FHtml::SETTINGS_FORM_LAYOUT, '_3cols');

if (FHtml::isInRole('', 'create', $currentRole))
{
    $controlName = FHtml::settingPageView('Form', '_form_3cols');
}
else
{
    $controlName = FHtml::settingPageView('View', '_view_3cols');
}


/* @var $this yii\web\View */
/* @var $model backend\models\SettingsLookup */

?>
<div class="settings-lookup-create">
    <?php if($canCreate === true) { echo  $this->render($controlName, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]);} else { ?>
    <?=  Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);} ?>
</div>
