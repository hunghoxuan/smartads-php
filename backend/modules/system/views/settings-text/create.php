<?php

/**
 *
 ***
 * This is the customized model class for table "SettingsText".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

$currentRole = FHtml::getCurrentRole();
$controlName = '';
$canCreate = true;

$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';
$modulePath = 'settings-text';
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
/* @var $model backend\modules\system\models\SettingsText */

?>
<div class="settings-text-create">
    <?php if ($canCreate === true) {
        echo  FHtml::render($controlName, $folder, [
            'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
        ]);
    } else { ?>
    <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);
    } ?>
</div>