<?php

/**
 *
 ***
 * This is the customized model class for table "ObjectActions".
 */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectActions */

$controlName = '';
$currentRole = FHtml::getCurrentRole();

$moduleName = 'ObjectActions';
$moduleTitle = 'Object Actions';
$moduleKey = 'object-actions';
$modulePath = 'object-actions';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Detail');

$controlName = '_form';
$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);

?>
<div class="object-actions-view">
    <?= FHtml::render($controlName, $folder, [
        'model' => $model, 'modelMeta' => $modelMeta, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath, 'edit_type' => FHtml::EDIT_TYPE_INLINE, 'display_type' => FHtml::DISPLAY_TYPE_TABLE
    ]) ?>
</div>