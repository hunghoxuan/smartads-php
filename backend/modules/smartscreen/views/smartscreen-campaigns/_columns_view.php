<?php


use common\components\FHtml;
use kartik\date\DatePicker;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName  = 'SmartscreenSchedules';
$moduleTitle = 'Smartscreen Schedules';
$moduleKey   = 'smartscreen-schedules';
$object_type = 'smartscreen_schedules';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
	//	[
	//		'class' => 'common\widgets\grid\CheckboxColumn',
	//		'width' => '20px',
	//	],
	[
		'class'     => FHtml::COLUMN_VIEW,
		'attribute' => 'start_time',
		'hAlign'    => 'left',
		'label' => FHtml::t('Duration'),

		'value'          => function ($model) {
			$result = !empty($model->start_time) ? (is_numeric($model->start_time) ? date('H:i A', $model->start_time) : $model->start_time) : '';

			$start_time = \backend\modules\smartscreen\Smartscreen::getNextStartTime($model);
			$duration = $model->duration;
			if (is_numeric($duration) && !empty($duration)) {
				$hours = floor($duration / 60);
				$mins = $duration - (60 * $hours);
				$duration = (!empty($hours) ? $hours . '<small>h</small>' : '') . (!empty($mins) ? $mins . '<small>\'</small>' : '');
			}

			$result .= " - <span style='color: darkgrey'> $start_time </span>";
			$result .= "<br/> <small> $duration </small>";
			return $result;
		},
		'contentOptions' => ['class' => 'col-md-1 nowrap text-center'],
	],
	[
		'class' => FHtml::COLUMN_VIEW,
		'attribute' => 'Layouts',
		'contentOptions' => ['class' => 'col-md-1 nowrap'],
		'value' => function ($model, $key, $index, $column) {
			$layout = null;
			if (is_numeric($model->layout_id)) {
				$layout = \backend\modules\smartscreen\models\SmartscreenLayouts::findOne($model->layout_id);
			}
			return \backend\modules\smartscreen\Smartscreen::showLayoutPreview($layout, '../../modules/smartscreen/views/demo_html');
		},
	],
	[
		'class'          => FHtml::COLUMN_VIEW,
		'attribute'      => 'content_id',
		'label'          => FHtml::t('common', 'Content'),
		'contentOptions' => ['class' => 'col-md-4 nowrap text-left'],
		'value'          => function ($model) {

			$result = '';

			if (empty($model->id)) {
				$result = '<span class="label label-sm label-primary">' . $model->data[0]['contentLayout'] . '</span> ' . $model->data[0]['data'][0]['title'];
				return "<span style='color:grey'>" . $result . "</span>";
			}
			if (!empty($model->content_id)) {
				//FHtml::var_dump($model->data);
				$items  = FHtml::decode($model->content_id);
				if (is_array($items)) {
					foreach ($items as $i => $item) {
						if (empty($item)) {
							continue;
						}
						$content_model = \backend\modules\smartscreen\models\SmartscreenContent::findOne($item);
						if (!isset($content_model) || in_array($content_model->type, ['text'])) {
							continue;
						}
						$frame = isset($model->data[$i]) ? $model->data[$i]['name'] : '';

						$result = $result . "<b>$frame</b>: " . '<span class="label label-sm label-warning">' . $content_model->type . '</span> ' . $content_model->title  . '<br/>';
					}
				}

				$date_start = !empty($model->date2) ? "Date: $model->date2" : '';
				$date_end   = !empty($model->date_end) ? " -> $model->date_end" : '';
				$result     .= "<small style=\"color:grey\"> $date_start $date_end </small>";
				$file_kind = $model->file_kind === 'time' ? 'mins' : 'times';
				$file_duration = ($model->file_kind === \backend\modules\smartscreen\models\SmartscreenSchedules::DURATION_KIND_MINUTES || $model->file_kind === \backend\modules\smartscreen\models\SmartscreenSchedules::DURATION_KIND_SECOND) ? $model->duration : 0;
				$result .= "<div class='col-md-2 pull-right' style=\"color:grey; text-align: right\"> "  . $file_duration . ' ' . $file_kind . " </div>";
			} else {
				$files = \backend\modules\smartscreen\models\SmartscreenFile::findAll(['object_id' => $model->id, 'object_type' => 'smartscreen_schedules']);
				if (is_array($files)) {
					foreach ($files as $item) {
						if (empty($item)) {
							continue;
						}
						//$result = $result . $item->description . '<span style="color:grey"> [' . $item->file . '] </span>' . '<br/>';
						$file_kind = $item->file_kind === 'time' ? 'mins' : 'times';
						$image = !empty($item->file) ? FHtml::showImage($item->file, 'smartscreen-file', '', '50px') : '<span class="label label-sm label-default">' . $item->command . '</span>';
						$result .= "<div class='row'> <div class='col-md-10' style='padding-bottom: 5px; margin-bottom: 5px;'>" . $image .  "<span style=\"color:grey\"> " . $item->description . "</span></div>" . "<div class='col-md-2 pull-right' style=\"color:grey; text-align: right\"> "  . $item->file_duration . ' ' . $file_kind . " </div>" . "</div>";
					}
				}
				//$result .= "<br/><small style=\"color:grey\"> " . count($files) . " items </small>";

			}

			return $result;
		},

	],
	[
		'class' => FHtml::COLUMN_VIEW,

		'attribute' => '',
		//'width' => '80px',
		'hAlign'         => 'center',
		'vAlign'         => 'middle',
		'contentOptions' => ['class' => 'col-md-1 nowrap text-center'],
		'value' => function ($model, $key, $index, $column) {
			$url = FHtml::createUrl('smartscreen/schedules', ['id' => $model->id, 'layout' => 'no']);
			return FHtml::buttonModal('<span class="glyphicon glyphicon-eye-open"></span>&nbsp;' . FHtml::t('View') . '&nbsp;', $url, 'iframe', 'btn btn-default btn-xs');
		},
	],

];
