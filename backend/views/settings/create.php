<?php

/**
 *
 ***
 * This is the customized model class for table "Settings".
 */

use yii\helpers\Html;
use common\components\FHtml;


$currentRole = FHtml::getCurrentRole();
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$canCreate = true;

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';
$modulePath = 'settings';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole('', 'create', $currentRole)) {
    $controlName = $folder . '_form';
} else {
    $controlName = $folder . '_view';
}


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

?>
<div class="settings-create">
    <?php if ($canCreate === true) {
        echo  $this->render($controlName, [
            'model' => $model, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
        ]);
    } else { ?>
    <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']);
    } ?>
</div>