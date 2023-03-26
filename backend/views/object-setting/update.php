<?php

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'ObjectSetting';
$moduleTitle = 'Object Setting';
$moduleKey = 'object-setting';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole))
{
    $controlName = $folder.'_form_3cols';
}
else
{
    $controlName = $folder.'_view';
}

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */
?>
<div class="object-setting-update">
    <?= $this->render($controlName, [
    'model' => $model,
    ]) ?>
</div>
