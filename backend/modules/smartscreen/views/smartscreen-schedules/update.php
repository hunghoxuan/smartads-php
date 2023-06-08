<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenSchedulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\models\SmartScreenSchedulesSearch;

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen-schedules';
$modulePath = 'smartscreen-schedules';
$object_type = 'smartscreen-schedules';
$modelMeta = isset($modelMeta) ? $modelMeta : null;

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = FHtml::settingPageView('_form', 'Form');
} else {
    $controlName = FHtml::settingPageView('_view', 'Detail');
}

$folder = Fhtml::getRequestParam(['form_type', 'type', 'status']);
$searchModel = SmartscreenSchedulesSearch::createNew();
if ($model->campaign_id > 0) {
    $dataProvider = $searchModel->search(['campaign_id' => $model->campaign_id]);
} else {
    $dataProvider = null;
}
?>
<div class="smartscreen-schedules-update">
    <?php echo FHtml::render($controlName, $folder, [
        'dataProvider' => $dataProvider,
        'model' => $model, 'modelMeta' => $modelMeta,
        'moduleKey' => $moduleKey, 'modulePath' => $modulePath,
        'object_type' => $object_type
    ]) ?>
</div>