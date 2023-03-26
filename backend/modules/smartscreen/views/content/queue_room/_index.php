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

$models = isset($models) ? $models :  Smartscreen::getQueueModels($object_type, ['status' => Smartscreen::STATUS_WAITING]);
$model = isset($model) ? $model : Smartscreen::getQueueModels($object_type, ['status' => Smartscreen::STATUS_NEXT]);

$models_limit = isset($limit) ? $limit : 5;
$models_count = count($models);
$form_type = FHtml::getRequestParam('form_type');
$null_value = isset($null_value) ? $null_value : Smartscreen::EMPTY_TEXT;

?>
<div class="row">
    <div class="col-xs-7 col-md-7">
        <div class="main-panel">
            <h1><?= FHtml::t('common', 'Ticket') ?></h1>
            <div class="panel-title"><?= FHtml::getFieldValue($model, 'ticket') ?></div>
            <h1><?= FHtml::t('common', 'Customer') ?></h1>
            <div class="panel-title"><?= FHtml::getFieldValue($model, 'name') ?></div>
            <h1><?= FHtml::getFieldValue($model, 'description') ?></h1>
        </div>
    </div>
    <div class="col-xs-5 col-md-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background-color: darkblue; color: white"><?= FHtml::t('common', 'Queue List') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            for ($i = 0; $i < $models_limit; $i++) {
                if ($i < $models_count)
                    $model = $models[$i];
                else
                    $model = null;
                ?>
                <tr>
                    <td class="col-xs-2 text-center"><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'ticket') ?></td>
                    <td><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'name') ?></td>
                </tr>
            <?php }
            ?>
            </tbody>
        </table>
    </div>
</div>



