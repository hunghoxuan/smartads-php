<?php

use yii\helpers\Html;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */

$folder = '';
$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'ObjectSetting';
$moduleTitle = 'Object Setting';
$moduleKey = 'object-setting';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');


if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_view';
} else {
    $controlName = $folder . '_view';
}

?>
<div class="object-setting-view">
    <?= $this->render($controlName, [
        'model' => $model,
    ]) ?>
</div>
