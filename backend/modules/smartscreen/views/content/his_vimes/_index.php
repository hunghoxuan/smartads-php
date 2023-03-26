<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\Smartscreen;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenContent';
$moduleTitle = 'Smartscreen Content';
$moduleKey = 'smartscreen-queue';
$object_type = 'smartscreen_queue';

$this->title = FHtml::t($moduleTitle);


CrudAsset::register($this);

$models = isset($models) ? $models : Smartscreen::getQueueModels('smartscreen_queue');
$api = FHtml::getRequestParam('api', false);
if ($api)
{
    ob_flush();
    header_remove();
    header("Content-Type: application/json");
    echo json_encode($models);
    exit();
}

if (!is_array($models)) {
    var_dump($models); die;
}
$models_limit = isset($limit) ? $limit : 5;
$models_count = count($models);
$form_type = FHtml::getRequestParam('form_type');
$null_value = isset($null_value) ? $null_value : Smartscreen::EMPTY_TEXT;

$footer_title = isset($footer_title) ? $footer_title : FHtml::getRequestParam('footer', 'description');
$header_title = isset($header_title) ? $header_title : FHtml::getRequestParam('header', 'title');

if (key_exists('title', $models)) {
    $header_title .= ' - ' . (is_numeric($models['title']) ? (FHtml::t('common', 'Room') . ' ' . $models['title']) : $models['title']);
}

$calling_models = [];
$pending_models = [];

if (key_exists('callinglist', $models))
    $calling_models = $models['callinglist'];


if (key_exists('pendinglist', $models))
    $pending_models = $models['pendinglist'];

$models = array_merge($calling_models, $pending_models);

if (empty($models)) {
    $models = [];
    $models[] = ['receptno' => '...', 'patientname' => '...', 'status_desc' => '...', 'status' => '0'];
}

$models_count = count($models);
$models_limit = $models_count;

?>
<style>
    body {
        background-color: white !important;
    }
</style>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-xs-2 text-center"><?= FHtml::t('common', 'Ticket') ?></th>
            <th class="col-xs-7 text-center"><?= FHtml::t('common', 'Patient Name') ?></th>
            <th class="col-xs-3 text-center"><?= FHtml::t('common', 'Status') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 0;

    for ($i = 0; $i < $models_limit; $i++) {

        if ($i < $models_count)
            $model = $models[$i];
        else
            $model = null;

        $status = FHtml::getFieldValue($model, 'status');
        $ticket = FHtml::getFieldValue($model, 'receptno');
        $name = FHtml::getFieldValue($model, 'patientname');
        $status_desc = FHtml::getFieldValue($model, 'status_desc');

        if (strtolower($status) == 'c') {
            $style = 'background-color:#274e13 !important; color: white;';
            $class = 'blink';
        } else {
            $style = '';
            $class = '';
        }
        ?>
        <tr>
            <td class="text-center  <?= $class ?>" style="<?= $style ?>"><?= !isset($model) ? $null_value : $ticket ?></td>
            <td class=" <?= $class ?>" style="<?= $style?>">
                <?= !isset($model) ? $null_value : $name ?>
            </td>
            <td class="text-center <?= $class ?>" style="<?= $style?>"><?= !isset($model) ? $null_value : $status_desc ?> </td>
        </tr>
    <?php }
        ?>
    </tbody>
</table>


