<?php
/**
* Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenStation".
*/
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';
$modulePath = 'smartscreen-station';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole($moduleName, 'create', $currentRole))
{
    $controlName = '_form';
}
else
{
    $controlName = '_view';
}


/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenStation */

?>
<div class="smartscreen-station-create">
    <?php if($canCreate === true) { echo  $this->render($controlName, [
    'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]);} else { ?>
    <?=  Html::a(FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default']);} ?>
</div>