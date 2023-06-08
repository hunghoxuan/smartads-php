<?php

namespace backend\modules\smartscreen\controllers;


use backend\controllers\AdminController;
use backend\controllers\BackendController;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenFrame;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\Smartscreen;
use common\components\FHtml;
use Imagine\Filter\Basic\Show;

class SmartscreenController extends AdminController
{
	/**
	 * @param $device_id
	 * @param $action
	 * @return bool|string
	 */
	public static function callSocket($device_id, $action)
	{
		//hung: no redis setup -> remove
		return false;
		return Smartscreen::refreshSchedulesAndPushToDevices($device_id, $action);
	}

	/**
	 * @param $frame_id
	 * @param $number
	 * @param $selected
	 * @param $content_models
	 * @param $content_json
	 * @param $i
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public static function listContent($frame_id, $number, $selected, $content_models, $content_json, $i)
	{
		if (is_object($frame_id)) {
			$frame_model = $frame_id;
			$frame_id    = $frame_model->id;
		} else {
			$frame_model = SmartscreenFrame::findOne($frame_id);
		}
		$frame_content_id = '';
		if (isset($frame_model)) {
			$frame_content_id = FHtml::getFieldValue($frame_model, 'content_id');
		}

		$result = "<select id='$frame_id' style='background-color: #eee' class='form-control contentSelect2'
                    name='SmartscreenSchedules[layout_id][$number][content][]'>";

		$result .= "<option value='' $selected >" . FHtml::t('common', '...') . "</option>";

		//$content_ids = json_encode($content_json->content_id);
		$content_ids = json_decode($content_json->content_id, true);
		$content_id = empty($content_ids[$i]) ? $frame_content_id : $content_ids[$i];

		if (empty($content_models)) {
			$content_models = SmartscreenContent::find()->orderBy('type asc, title asc')->all();
		}

		foreach ($content_models as $content_model) {
			$key   = $content_model->id;
			$value = "[<small style='color:grey'> $content_model->type </small>] $content_model->title (id: $content_model->id)";

			$result .= "<option value='$key'";
			if ($content_id == $key) {
				$result .= 'selected';
			}

			$result .= ">"; //end option

			$result .= $value;
			$result .= "</option>";
		}

		$result .= "</select>";

		return $result;
	}

	/**
	 * @param $content_id
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public static function updateContent($content_id)
	{
		$baseUrl = \Yii::$app->getUrlManager()->getBaseUrl();
		if (isset($content_id)) {
			$result = '<a href="javascript:void(0)" class="data-link" data-link="' . $baseUrl . '/index.php/smartscreen/smartscreen-content/update?id=' . $content_id . '">
                        <span class="glyphicon glyphicon-eye-open"></span>
                       </a>';
		} else {
			$result = '<a href="javascript:void(0)" class="data-link" data-link="">
                        <span class="glyphicon glyphicon-eye-open"></span>
                       </a>';
		}

		return $result;
	}

	/**
	 * @return bool|string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionGetContent()
	{

		$this->layout = false;
		$layout_id    = $_REQUEST['layout_id'];
		$selectId     = $_REQUEST['selectId'];
		$scheduleId   = isset($_REQUEST['scheduleId']) ? $_REQUEST['scheduleId'] : '';

		if ($layout_id) {

			$layout       = SmartscreenLayouts::findOne($layout_id);
			$content_json = [];

			if (!empty($scheduleId)) {
				$content_json = SmartscreenSchedules::find()->select(['content_id'])->where(['id' => $scheduleId])->one();
			} else {
				$content_json = new SmartscreenSchedules();
			}

			if ($layout) {
				return $this->render('get-content', ['layout' => $layout, 'selectId' => $selectId, 'content_json' => $content_json]);
			}
		}

		return false;
	}
}
