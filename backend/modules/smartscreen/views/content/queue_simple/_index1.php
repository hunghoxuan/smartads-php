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
$models_limit = isset($limit) ? $limit : 5;
$models_count = count($models);
$form_type = FHtml::getRequestParam('form_type');
$null_value = isset($null_value) ? $null_value : Smartscreen::EMPTY_TEXT;

$footer_title = isset($footer_title) ? $footer_title : FHtml::getRequestParam('footer', 'description');
$header_title = isset($header_title) ? $header_title : FHtml::getRequestParam('header', 'title');

?>
        <table class="table">
            <thead>
                <tr>
                    <th class="col-xs-4 text-center"><?= FHtml::t('common', 'Ticket') ?></th>
                    <th class="col-xs-2"></th>
                    <th class="col-xs-4 text-center"><?= FHtml::t('common', 'Counter') ?></th>
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
                    <td class="text-center"><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'code') ?></td>
                    <td><p class="arrow"><i class="fa fa-caret-right"></i></p>
                    </td>
                    <td class="text-center"><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'counter') ?></td>
                </tr>
            <?php }
                ?>

            </tbody>
        </table>



