<?php

/**



 * This is the customized model class for table "Settings".
 */

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';
$modulePath = 'settings';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();


if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_form';
} else {
    $controlName = $folder . '_view';
}

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
?>
<div class="settings-update hidden-print">
    <?= $this->render($controlName, [
        'model' => $model, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>