<?php

/**
 *
 ***
 * This is the customized model class for table "SmartscreenSchedules".
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenSchedulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen-schedules';
$object_type = 'smartscreen-schedules';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$viewType = FHtml::getRequestParam('view');
$gridControl = FHtml::settingPageView('_index');

?>
<?= FHtml::render($gridControl, $viewType, [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'date' => !empty($searchModel->date) ? $searchModel->date  : FHtml::getRequestParam('date'),
    'channel_id' => !empty($searchModel->channel_id) ? $searchModel->channel_id : FHtml::getRequestParam('channel_id'),
    'device_id' => !empty($searchModel->device_id) ? $searchModel->device_id  : FHtml::getRequestParam('device_id'),
    'viewType' => $viewType
]) ?>
