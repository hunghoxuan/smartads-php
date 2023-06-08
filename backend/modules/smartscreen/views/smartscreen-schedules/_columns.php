<?php


use common\components\FHtml;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\Smartscreen;

$currentRole = FHtml::getCurrentRole();
$moduleName  = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey   = 'smartscreen-schedules';
$object_type = 'smartscreen_schedules';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');
$campaign_id = Smartscreen::getCurrentCampaignId();
$device_id = Smartscreen::getCurrentDeviceId();
$show_all = FHtml::getRequestParam('show_all', 1);
//if (!empty(FHtml::getRequestParam('device_id'))  ||  !empty(FHtml::getRequestParam('campaign_id')))
//    $show_all = 1;

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'visible' => empty($campaign_id) && empty($device_id),
        'class' => FHtml::COLUMN_VIEW,

        'attribute' => 'channel_id',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'group' => true,
        'groupedRow' => true,
        'value' => function ($model, $key, $index, $column) {

            if (is_numeric($model->channel_id)) {
                $item = \backend\modules\smartscreen\models\SmartscreenChannels::findOneCached($model->channel_id);
                $name = strtoupper(is_object($item) ? $item->name : "$model->channel_id");
                return "<b><a data-pjax='0' href='" . FHtml::createUrl('smartscreen/smartscreen-schedules', ['channel_id' => $model->channel_id]) . "'>" . FHtml::t('Channels') . ": $name </a></b>";
            } else {
                if ($model->channel_id == FHtml::NULL_VALUE)
                    return "<div style='color:gray'>" . FHtml::t('All') .  "</div>";
                return $model->channel_id;
            }
        },
    ],
    [
        'visible' => true, // empty($device_id),
        'class' => FHtml::COLUMN_VIEW,
        'attribute' => 'device_id',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'group' => true,
        'groupedRow' => !empty($device_id),
        'value' => function ($model, $key, $index, $column) {
            if ($model->device_id == FHtml::NULL_VALUE)
                return "<div style='color:gray'>" . FHtml::t('All') .  "</div>";
            else {
                $devices = FHtml::decode($model->device_id);

                if (!is_array($devices))
                    $devices = explode(',', $devices);
                $arr = [];
                foreach ($devices as $device_id) {
                    $device_id = trim($device_id);
                    if (!is_numeric($device_id)) {
                        continue;
                    }
                    $item = \backend\modules\smartscreen\models\SmartscreenStation::findOneCached($device_id);
                    $title = "<a data-pjax='0' href='" . FHtml::createUrl('smartscreen/smartscreen-schedules', ['device_id' => $device_id]) . "'> $item->name </a>";

                    $arr[] = is_object($item) ? "$title <small style='color:darkgrey'>$item->description</small> <br/>" . Smartscreen::showDeviceLastUpdate($item, $device_id, false) : $item;
                }
                $devices = $arr;
                if (empty($devices))
                    $devices = [$model->device_id];

                return implode(', ', $devices);
            }
        },
    ],
    [
        'class'     => FHtml::COLUMN_VIEW,
        'attribute' => 'start_time',
        'hAlign'    => 'left',
        'label' => FHtml::t('Duration'),

        'value'          => function ($model) {
            $result = !empty($model->start_time) ? (is_numeric($model->start_time) ? date('H:i A', $model->start_time) : $model->start_time) : '';

            $end_time =  Smartscreen::getNextStartTime($model);
            $duration = $model->duration;
            $timeline = '';
            if (is_numeric($duration) && !empty($duration)) {
                $hours = floor($duration / 60);
                $mins = $duration - (60 * $hours);
                $duration = (!empty($hours) ? $hours . '<small>h</small>' : '') . (!empty($mins) ? $mins . '<small>\'</small>' : '');
            }
            for ($i = 0; $i < 24; $i++) {
                if ((int) substr($model->start_time, 0, 2) <= $i && (int) substr($end_time, 0, 2) >= $i) {
                    $color = 'yellow';
                } else {
                    $color = '#ccc';
                }
                $timeline .= "<div style='width:30;height:30;background-color:$color;float:left;border:1px solid grey;float:left; font-size:40%; padding: 2px'> $i </div>";
            }
            $result .= " - <span style='color: darkgrey'> $end_time </span>";

            $params = Smartscreen::getCurrentParams(['create', 'start_time' => $end_time], '', $model);
            $result .= FHtml::a('<i class="fa fa-plus"></i>', $params, ['data-pjax' => 0, 'class' => 'btn btn-xs btn-success pull-right']);

            $result .= "<span style='color: darkgrey; font-size:80%'> <span class=\"glyphicon glyphicon-time\"></span> $duration</span>";
            return "<div style='width:100%;'>$timeline </div> <div style='clear:both; padding-top:10px'>$result </div>";
        },
        'contentOptions' => ['class' => 'col-md-3 nowrap text-center'],
    ],
    [
        'class' => FHtml::COLUMN_VIEW,

        'attribute' => 'Layouts',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model, $key, $index, $column) {
            $layout = null;
            if (is_numeric($model->layout_id)) {
                $layout = \backend\modules\smartscreen\models\SmartscreenLayouts::findOneCached($model->layout_id);
            }

            return Smartscreen::showLayoutPreview($layout);
        },
    ],

    [
        'class'          => FHtml::COLUMN_VIEW,
        'attribute'      => 'content_id',
        'label'          => FHtml::t('common', 'Content'),
        'contentOptions' => ['class' => $show_all == 0 ? 'col-md-3 nowrap text-left' : 'col-md-2 nowrap text-left'],
        'value'          => function ($model, $key, $index, $column) {
            return Smartscreen::showScheduleOverview($model);
        },
    ],
    [
        'class'          => FHtml::COLUMN_VIEW,
        'visible' => $show_all > 0,
        'attribute'      => 'Time',
        'label'          => FHtml::t('common', 'Time'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
        'value'          => function ($model) {
            return $model->showPreview('date');
        },
    ],
    [
        'class' => FHtml::COLUMN_VIEW,
        //'visible' => $show_all > 0,

        'attribute' => '',
        'hAlign'         => 'left',
        'vAlign'         => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'value' => function ($model, $key, $index, $column) {
            if ($model->id == 0)
                return "<div style='color: darkgrey;'>" . FHtml::t('Default') . "</div>";
            $device_id =  Smartscreen::getCurrentDeviceId($model);
            $url = FHtml::createUrl('smartscreen/schedules', ['id' => $model->id, 'device_id' => $device_id, 'layout' => 'no']);
            $result = FHtml::showModalIframeButton('<span class="glyphicon glyphicon-eye-open"></span>&nbsp;Id' . $model->id . '&nbsp;', $url, 'iframe', 'label-xs label label-primary');
            $label = "";
            if (!empty($model->{SmartscreenSchedules::FIELD_CAMPAIGN_ID})) {
                $label = "Campaign: " . $model->{SmartscreenSchedules::FIELD_CAMPAIGN_ID};
                $css = 'label label-primary';
                $url = FHtml::createUrl('smartscreen/smartscreen-schedules/index', ['campaign_id' => $model->{SmartscreenSchedules::FIELD_CAMPAIGN_ID}]);

                $result .= "<a href='$url' data-pjax=0>$label</a>";
            }
            // else if (!empty($model->device_id)) {
            //     $label = "Device: " . $model->device_id;
            //     $css = 'label label-warning';
            // } else if (!empty($model->id)) {
            //     $label = "";
            //     $css = 'label label-default';
            // } else
            //     $label = "";
            // if (!empty($label))
            //     $result .= FHtml::showModalContent("<div class='$css' style='float:left; margin-right:10px;'>$label</div>", $model->showPreview(true));

            return $result;
        },
    ],
    [
        'visible' => $show_all > 0,
        'attribute'      => 'is_active',
        'label'          => FHtml::t('common', 'Status'),
        'contentOptions' => ['class' => 'col-md-1 nowrap text-left'],
    ],
    [
        'class'          => 'kartik\grid\ActionColumn',
        'dropdown'       => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'         => 'center',
        'vAlign'         => 'middle',
        'width'          => '80px',
        'urlCreator'     => function ($action, $model) {
            $params = Smartscreen::getCurrentParams([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id', 'channel_id'])]);
            return FHtml::createBackendActionUrl($params, ['action', 'date', 'show_all', '_pjax']);
        },
        'visibleButtons' => [
            'view'   => false,
            'update' => function ($model, $currentRole) {
                return empty($model->id) ? false : FHtml::isInRole('', 'update', $currentRole);
            },
            'delete' => function ($model, $currentRole) {
                return empty($model->id) ? false : FHtml::isInRole('', 'delete', $currentRole);
            }
        ],
        'viewOptions'    => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'View'), 'data-toggle' => 'tooltip'],
        'updateOptions'  => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'Update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'Delete'),
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'Are you sure to delete')
        ],
    ],
];
