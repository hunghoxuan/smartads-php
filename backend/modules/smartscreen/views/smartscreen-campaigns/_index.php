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

$moduleName = 'SmartscreenCampaigns';
$moduleTitle = 'Smartscreen Campaigns';
$moduleKey = 'smartscreen-campaigns';
$object_type = 'smartscreen_campaigns';

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

$channel_id = isset($channel_id) ? $channel_id : Smartscreen::getCurrentChannelId();
$date = isset($date) ? $date : FHtml::getRequestParam('date');
$device_id = isset($device_id) ? $device_id : Smartscreen::getCurrentDeviceId();
$model = $searchModel;

$list_device = SmartscreenStation::findAllForCombo();
$list_layout = SmartscreenLayouts::findAllForCombo();

$list_channels = SmartscreenChannels::findAllForCombo();

$_null_value_array = [FHtml::NULL_VALUE => FHtml::NULL_VALUE];
//$list_device = array_merge($_a, $list_device);
$list_device = $_null_value_array + $list_device;
if (isset($list_device['']) && $list_device == FHtml::NULL_VALUE) {
    unset($list_device['']);
}
//FHtml::var_dump($list_device);die;

$disabled = false;
$updateButton = '';
$createButton = '';

if (FHtml::isInRole('', 'create', $currentRole)) {
    $url = FHtml::createUrl('/smartscreen/smartscreen-campaigns/create', ['channel_id' => $channel_id, 'device_id' => $device_id]);
    $createButton = "<a class='btn btn-success' url='$url'  href='$url' id='btnCreate' name='btnCreate' >" . FHtml::t('Create') . "</a>"; //FHtml::buttonCreate();
}
$url = FHtml::createUrl('/smartscreen/schedules', ['layout' => 'no', 'device_id' => ($model->device_id == FHtml::NULL_VALUE ? null : $model->device_id)]);
$view = '';
//$view = 'fadsf';
if (FHtml::isInRole('', 'edit', $currentRole) && !empty($dataProvider->models)) {
    $updateButton = FHtml::a('<i class="fa fa-edit"></i> ' . FHtml::t('button', 'Update'), ['update', 'channel_id' => $channel_id, 'date' => $date, 'device_id' => $device_id], ['class' => 'btn btn-warning', 'data-pjax' => 0]);
}
$deleteButton = FHtml::buttonDeleteBulk();
?>

<div class="smartscreen-campaigns-index">
    <?php if ($this->params['displayPortlet']) : ?>
        <div class="<?= $this->params['portletStyle'] ?>">
            <div class="portlet-title">
                <?php $form = \common\widgets\FActiveForm::begin([
                    'id' => 'smartscreen-campaigns-form',
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
            <div class="col-md-12">
                <?php echo \common\widgets\FFormTable::widget([
                    'hide_field' => false,
                    'type'                   => \kartik\form\ActiveForm::TYPE_VERTICAL,
                    'model' => $model, 'form' => $form, 'columns' => 6, 'attributes' => [
                        'channel_id' => ['value' => $form->fieldNoLabel($model, 'channel_id')->select($list_channels)],
                        'device_id' => ['value' => $form->fieldNoLabel($model, 'device_id')->selectCustomRenderer(SmartscreenStation::findAll(), function ($item, $id) {
                            $selected = $item->id == $id ? 'selected' : '';
                            return "<option parent='$item->channel_id' value='$item->id' $selected>$item->name</option>";
                        })],
                        'date' => ['value' => $form->fieldNoLabel($model, 'date')->date()],
                        'date_end' => ['value' => $form->fieldNoLabel($model, 'date_end')->date()],
                        'show_all' => ['value' => $form->fieldNoLabel($model, 'status')->select([['id' => 0, 'name' => FHtml::t('Active')], ['id' => 1, 'name' => FHtml::t('All')]])],
                    ]
                ]);
                ?>
                <?= FHtml::buttonSearch() . "<div class='pull-right'> $deleteButton $createButton</div>" ?>
                <?php \common\widgets\FActiveForm::end(); ?>
            </div>
            <div class="col-md-12 no-padding">

                <div id="ajaxCrudDatatable" class="<?php if (!$this->params['displayPortlet']) echo 'portlet light ' . ($viewType != 'print' ? 'bordered' : '');  ?>">
                    <?= FGridView::widget([
                        'id' => 'crud-datatable',
                        'dataProvider' => $dataProvider,
                        'filterModel' => null,
                        'filter' => null,
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
            </div>

            </div>

            <?php if ($this->params['displayPortlet']) : ?>
        </div>
</div>
<?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        $('select[name="SmartscreenCampaigns[channel_id]"]').change(function() {
            var value = this.value;
            $('select[name="SmartscreenCampaigns[device_id]"] option').prop('disabled', false).show().filter(function() {
                var parent = $(this).attr('parent');

                if (value == undefined || value == '')
                    return false;
                return parent && parent !== value;
            }).prop('disabled', true).prop('selected', false).hide();
        });

        $('select[name="SmartscreenCampaigns[device_id]"]').change(function() {
            var value = this.value;
            $('a[name="btnCreate"]').attr('href', $('a[name="btnCreate"]').attr('url') + '?device_id=' + value);
        });

        var channel_id = $('select[name="SmartscreenCampaigns[channel_id]"]').val();
        if (channel_id) {
            $('select[name="SmartscreenCampaigns[channel_id]"]').trigger('change');
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