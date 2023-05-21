<?php

/**



 * This is the customized model class for table "Application".
 */

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'Application';
$moduleTitle = 'Application';
$moduleKey = 'application';
$modulePath = 'application';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();

$form_layout = FHtml::config(FHtml::SETTINGS_FORM_LAYOUT, '');

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_form' . $form_layout;
} else {
    $controlName = $folder . '_view' . $form_layout;
}

/* @var $this yii\web\View */
/* @var $model backend\models\Application */
?>
<div class="application-update hidden-print">
    <?= $this->render($controlName, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
    ]) ?>
</div>