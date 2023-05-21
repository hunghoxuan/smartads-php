<?php

/*
 * This is the customized model class for table "SmartscreenStation".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';
$modulePath = 'smartscreen-station';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole($moduleName, 'update', $currentRole)) {
    $controlName = '_form';
} else {
    $controlName = '_view';
}

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenStation */
?>
<div class="smartscreen-station-update hidden-print">
    <?= $this->render($controlName, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>