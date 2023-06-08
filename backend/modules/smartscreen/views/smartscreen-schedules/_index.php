<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\widgets\FGridView;

use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\Smartscreen;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenChannels;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\smartscreen\models\SmartscreenSchedulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey = 'smartscreen-schedules';
$object_type = 'smartscreen_schedules';

$this->title = FHtml::t($moduleTitle);

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$viewType = isset($viewType) ? $viewType : FHtml::getRequestParam('view');
$gridControl = $folder . '_columns.php';

$model = $searchModel;

$date = isset($date) ? $date : FHtml::getRequestParam('date');
$channel_id = isset($channel_id) ? $channel_id : Smartscreen::getCurrentChannelId();
$device_id = isset($device_id) ? $device_id : Smartscreen::getCurrentDeviceId();
$campaign_id = isset($campaign_id) ? $campaign_id : Smartscreen::getCurrentCampaignId();

//$list_device = SmartscreenStation::findAllForCombo();
$list_layout = SmartscreenLayouts::findAllForCombo();

$list_channels = SmartscreenChannels::findAllForCombo();
$list_campaigns = \backend\modules\smartscreen\models\SmartscreenCampaigns::findAllForCombo();

$_null_value_array = [FHtml::NULL_VALUE => FHtml::NULL_VALUE];

$refresh = 0;
if (empty(FHtml::getRequestParam('device_id')))
    $refresh = 30 * 1000;

$disabled = false;
$updateButton = '';
$createButton = '';

if ($model->channel_id == FHtml::NULL_VALUE)
    $model->channel_id = null;

if ($model->device_id == FHtml::NULL_VALUE)
    $model->device_id = null;

if ($model->campaign_id == FHtml::NULL_VALUE)
    $model->campaign_id = null;

if (FHtml::isInRole('', 'create', $currentRole)) {
    $url = FHtml::createUrl('/smartscreen/smartscreen-schedules/create', ['channel_id' => $channel_id, 'device_id' => $device_id, 'campaign_id' => $campaign_id, 'search' => FHtml::getRequestParam('search'), 'layout' => FHtml::getRequestParam('layout')]);
    $createButton = "<a class='btn btn-success' url='$url'  href='$url' id='btnCreate' name='btnCreate' >" . FHtml::t('Create') . "</a>"; //FHtml::buttonCreate();
}

$view = '';
if (!empty($model->device_id) && $model->device_id != FHtml::NULL_VALUE) {
    $url = FHtml::createUrl('/smartscreen/schedules', ['layout' => 'no', 'device_id' => ($model->device_id == FHtml::NULL_VALUE ? null : $model->device_id)]);
    $view = FHtml::showModalIframeButton('Xem', $url); // "<a data-pjax=0 style='float:right' href='$url' target='_blank' class='btn btn-primary'>" . FHtml::t('common', 'View') . "</a>";
    $url = FHtml::createUrl('/smartscreen/api/schedules', ['device_id' => $model->device_id, 'debug' => true]);
    $view .= "<a href='$url' target='_blank' data-pjax='0' class='btn btn-default'> API </a>";
}

if (FHtml::isInRole('', 'edit', $currentRole) && !empty($dataProvider->models)) {
    $updateButton = FHtml::a('<i class="fa fa-edit"></i> ' . FHtml::t('button', 'Update'), ['update', 'channel_id' => $channel_id, 'date' => $date, 'device_id' => $device_id], ['class' => 'btn btn-warning', 'data-pjax' => 0]);
}
$deleteButton = FHtml::buttonDeleteBulk();
//if (!empty($model->device_id)  || !empty($model->campaign_id) || !empty($model->channel_id)) {
$gridControl = $folder . '_columns.php';
// } else {
//     $gridControl = $folder . '_columns_simple.php';
// }
$showGrid = true;
$showSearch = !in_array(FHtml::getRequestParam('search', 'true'), ['no', 'false']);

?>

<div class="smartscreen-schedules-index">
    <?php if ($this->params['displayPortlet']) : ?>
        <div class="<?= $this->params['portletStyle'] ?>">
            <div class="portlet-title">
                <?php $form = \common\widgets\FActiveForm::begin([
                    'id' => 'smartscreen-schedules-form',
                    'type' => \kartik\form\ActiveForm::TYPE_HORIZONTAL, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                    'staticOnly' => false, // check the Role here
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'options' => [
                        //'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                    ]
                ]);
                ?>
            </div>
            <div class="portlet-body">
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($showSearch) {
                        echo \common\widgets\FFormTable::widget([
                            'hide_field' => false,
                            'type' => \kartik\form\ActiveForm::TYPE_VERTICAL,
                            'model' => $model, 'form' => $form, 'columns' => 6, 'attributes' => [
                                'campaign_id' => ['value' => $form->fieldNoLabel($model, 'campaign_id')->select($list_campaigns)],
                                'channel_id' => ['value' => $form->fieldNoLabel($model, 'channel_id')->select($list_channels)],
                                'device_id' => ['value' => $form->fieldNoLabel($model, 'device_id')->selectCustomRenderer(SmartscreenStation::findAll(), function ($item, $id) {
                                    $selected = $item->id == $id ? 'selected' : '';
                                    return "<option parent='$item->channel_id' value='$item->id' $selected>$item->name ($item->description)</option>";
                                })],
                                'date' => ['value' => $form->fieldNoLabel($model, 'date')->date()],
                                'date_end' => ['value' => $form->fieldNoLabel($model, 'date_end')->date()],
                                // 'show_all' => ['label' => FHtml::t('Type') . ' ' . FHtml::t('Filter'), 'value' => $form->fieldNoLabel($model, 'show_all')->select([['id' => 0, 'name' => FHtml::t('Active')], ['id' => 1, 'name' => FHtml::t('All')]])],

                            ]
                        ]);
                        echo FHtml::buttonSearch() . '&nbsp;' . $createButton;
                    }
                    ?>
                    <?= "<div class='pull-right'>$deleteButton $view </div>" ?>
                    <?php \common\widgets\FActiveForm::end(); ?>
                </div>
                <?php if ($showGrid) {
                ?>

                    <div class="col-md-12 no-padding">

                        <?php \yii\widgets\Pjax::begin(['id' => "content"]);

                        ?>
                        <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light ' . ($viewType != 'print' ? 'bordered' : '');  ?>">
                            <?= FGridView::widget([
                                'id' => 'crud-datatable',
                                'dataProvider' => $dataProvider,
                                'filterModel' => null,
                                'filter' => null,
                                'pager' => false,
                                'object_type' => $object_type,
                                'edit_type' => FHtml::EDIT_TYPE_INLINE,
                                'render_type' => FHtml::RENDER_TYPE_AUTO,
                                'readonly' => !FHtml::isInRole('', 'update', $currentRole),
                                'field_name' => ['name', 'title'],
                                'field_description' => ['overview', 'description'],
                                'field_group' => ['category_id', 'type', 'status', 'lang', 'is_hot', 'is_top', 'is_active'],
                                'field_business' => ['', ''],
                                'toolbar' => '',
                                'columns' => require(__DIR__ . '/' . $gridControl),
                            ]) ?>
                        </div>
                        <div class="hidden">
                            <?= Html::a("<i class=\"fa fa-refresh\" aria-hidden=\"true\"></i>", FHtml::currentUrl(), ['class' => 'btn btn-lg btn-default', 'id' => 'refreshButton']) ?>
                        </div>

                        <?php \yii\widgets\Pjax::end(); ?>
                    </div>
                <?php  }  ?>
            </div>

            <?php if ($this->params['displayPortlet']) : ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
if ($refresh > 0) {
    $script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, $refresh);
});
JS;
    $this->registerJs($script);
}
?>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        $('select[name="SmartscreenSchedulesSearch[campaign_id]"]').change(function() {
            var value = this.value;
            $('select[name="SmartscreenSchedulesSearch[channel_id]"] option').prop('disabled', false).show().filter(function() {
                if (value != '' && value != undefined)
                    return true;
                return false;
            }).prop('disabled', true).prop('selected', false).hide();
            $('select[name="SmartscreenSchedulesSearch[device_id]"] option').prop('disabled', false).show().filter(function() {
                if (value != '' && value != undefined)
                    return true;
                return false;
            }).prop('disabled', true).prop('selected', false).hide();
        });


        $('select[name="SmartscreenSchedulesSearch[channel_id]"]').change(function() {
            var value = this.value;
            $('select[name="SmartscreenSchedulesSearch[device_id]"] option').prop('disabled', false).show().filter(function() {
                var parent = $(this).attr('parent');

                if (value == undefined || value == '')
                    return false;
                return parent && parent !== value;
            }).prop('disabled', true).prop('selected', false).hide();
        });

        $('select[name="SmartscreenSchedulesSearch[device_id]"]').change(function() {
            var value = this.value;
            $('a[name="btnCreate"]').attr('href', $('a[name="btnCreate"]').attr('url') + '?device_id=' + value);
        });

        var campaign_id = $('select[name="SmartscreenSchedulesSearch[campaign_id]"]').val();
        var channel_id = $('select[name="SmartscreenSchedulesSearch[channel_id]"]').val();
        if (campaign_id) {
            $('select[name="SmartscreenSchedulesSearch[campaign_id]"]').trigger('change');
        } else if (channel_id) {
            $('select[name="SmartscreenSchedulesSearch[channel_id]"]').trigger('change');
        }

    });
</script>
<style>
    .demo-layout {
        background-color: #666;
        width: 100%;
        height: 80px;
    }

    .div-layout {
        width: 100%;
        height: 100%;
        position: relative;
    }
</style>