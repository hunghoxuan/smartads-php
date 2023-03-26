<?php
/**
* Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "MediaFile".
*/

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'MediaFile';
$moduleTitle = 'Media File';
$moduleKey = 'media-file';
$modulePath = 'media-file';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole($moduleName, 'update', $currentRole))
{
    $controlName = '_form';
}
else
{
    $controlName = '_view';
}

/* @var $this yii\web\View */
/* @var $model backend\modules\media\models\MediaFile */
?>
<div class="media-file-update hidden-print">
    <?= $this->render($controlName, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
<div class="visible-print">
    <?= $this->render('_view_print', [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>
