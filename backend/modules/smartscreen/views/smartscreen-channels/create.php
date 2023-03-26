<?php

use yii\helpers\Html;
use common\components\FHtml;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = 'SmartscreenChannels';
$moduleTitle = 'Smartscreen Channels';
$moduleKey = 'smartscreen-channels';
$modulePath = 'smartscreen-channels';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole('', 'create', $currentRole)) {
    $controlName = FHtml::settingPageView('_form', 'Form');
} else {
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = FHtml::getRequestParam(['form_type', 'type', 'status']);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenChannels */

?>
<div class="smartscreen-channels-create">
    <?php if ($canCreate === true) { ?>
        <?= FHtml::render($controlName, $folder, [
            'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
        ]); ?>
    <?php } else { ?>
        <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']); ?>
    <?php } ?>
</div>