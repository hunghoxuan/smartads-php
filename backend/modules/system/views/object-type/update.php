<?php

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'ObjectType';
$moduleTitle = 'Object Type';
$moduleKey = 'object-type';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_form_3cols';
} else {
    $controlName = $folder . '_view';
}

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectType */
?>
<div class="object-type-update">
    <?= $this->render($controlName, [
        'model' => $model,
    ]) ?>
</div>
