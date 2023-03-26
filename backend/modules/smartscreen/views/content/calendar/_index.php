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

$moduleName = 'SmartscreenCalendar';
$moduleTitle = 'Smartscreen Calendar';
$moduleKey = 'smartscreen-calendar';
$object_type = 'smartscreen_calendar';

$this->title = FHtml::t($moduleTitle);


CrudAsset::register($this);
$today = date('Y-m-d');
//getModels($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $load_active_only = true, $selectedFields = [])
$type = FHtml::getRequestParam('type', '');
$models_limit = isset($limit) ? $limit : 5;

$condition = "(date = '$today' or date is null or date = '')";

if (!empty($type))
    $condition .= " and (type = '$type' or type = '')";

$models = isset($models) ? $models : FHtml::getModels($object_type, $condition, 'name asc', $models_limit);

$models_count = count($models);
$form_type = FHtml::getRequestParam('form_type');

$null_value = isset($null_value) ? $null_value : '.......';


?>

<table class="table">
    <thead>
        <tr>
            <th class="col-xs-2"><?= ($type == 'doctor') ? FHtml::t('common', 'Employee') : FHtml::t('common', 'Customer') ?></th>
            <th class="col-xs-2"><?= FHtml::t('common', 'Time') ?></th>
            <th class="col-xs-4"><?= FHtml::t('common', 'Task') ?></th>
            <th class="col-xs-2"><?= FHtml::t('common', 'Location') ?></th>
            <th class="col-xs-2"><?= ($type == 'doctor') ? FHtml::t('common', 'Customer') : FHtml::t('common', 'Employee') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 0;
    $last_name = '';
    $name = '';
    for ($i = 0; $i < $models_limit; $i++) {

        if ($i < $models_count)
            $model = $models[$i];
        else
            $model = null;

        if (isset($model)) {
            $name = FHtml::getFieldValue($model, 'name');
            if ($name != $last_name) {
                $last_name = $name;
            } else {
                $name = '';
            }
        }

        ?>
        <tr>
            <td><?= !isset($model) ? $null_value : $name ?></td>
            <td><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'time') ?></td>
            <td><?= !isset($model) ? $null_value : FHtml::showHtml(FHtml::getFieldValue($model, 'description')) ?></td>
            <td><?= !isset($model) ? $null_value : FHtml::getFieldValue($model, 'location') ?></td>
            <td><?= !isset($model) ? $null_value : FHtml::showHtml(FHtml::getFieldValue($model, 'owner_name')) ?></td>
        </tr>
    <?php }
        ?>

    </tbody>
</table>



