<?php

namespace backend\modules\app\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;
use yii\data\ActiveDataProvider;


class AppLog extends AppLogBase
{
	const LOOKUP = [
		'action' => [
			['id' => AppLog::ACTION_REGISTER, 'name' => 'register'],
			['id' => AppLog::ACTION_LOGIN, 'name' => 'login'],
			['id' => AppLog::ACTION_PURCHASE, 'name' => 'purchase'],
			['id' => AppLog::ACTION_FEEDBACK, 'name' => 'feedback'],
		],
		'status' => [
			['id' => AppLog::STATUS_SUCCESS, 'name' => 'success'],
			['id' => AppLog::STATUS_FAIL, 'name' => 'fail'],
			['id' => AppLog::STATUS_BLOCK, 'name' => 'block'],
		],
	];

	const COLUMNS_UPLOAD = [];


	const OBJECTS_META    = [];
	const OBJECTS_RELATED = [];

	public static function getLookupArray($column = '') {
		if (key_exists($column, self::LOOKUP)) {
			return self::LOOKUP[$column];
		}

		return [];
	}


	// Lookup Object: user
	public $user;

	public function getUser() {
		if (!isset($this->user)) {
			$this->user = FHtml::getModel('app_user', '', $this->user_id, '', false);
		}

		return $this->user;
	}

	public function setUser($value) {
		$this->user = $value;
	}


	public function prepareCustomFields() {
		parent::prepareCustomFields();

		$this->user = self::getUser();
	}

	public function getPreviewFields() {
		return ['name'];
	}

	public static function getRelatedObjects() {
		return self::OBJECTS_RELATED;
	}

	public static function getMetaObjects() {
		return self::OBJECTS_META;
	}

	public function getDefaultFindParams() {
		return [];
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param array  $params
	 * @param string $andWhere
	 * @return ActiveDataProvider
	 */
	public function search($params, $andWhere = '') {
		$query = AppLog::find();

		$dataProvider = new FActiveDataProvider([
			'query' => $query,
		]);

		$searchExact = FHtml::getRequestParam('SearchExact', false);

		//load Params and $_REQUEST
		FHtml::loadParams($this, $params);

		//if (!$this->validate()) {
		// uncomment the following line if you do not want to return any records when validation fails
		// $query->where('0=1');
		//return $dataProvider;
		//}

		if ($searchExact) {
			$query->andFilterWhere([
				'id'             => $this->id,
				'user_id'        => $this->user_id,
				'action'         => $this->action,
				'note'           => $this->note,
				'tracking_time'  => $this->tracking_time,
				'status'         => $this->status,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);
		}
		else {
			$query->andFilterWhere([
				'id'             => $this->id,
				'user_id'        => $this->user_id,
				'action'         => $this->action,
				'note'           => $this->note,
				'status'         => $this->status,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);

			$query->andFilterWhere(['like', 'tracking_time', $this->tracking_time]);
		}

		if (!empty($andWhere)) {
			$query->andWhere($andWhere);
		}

		$params = $this->getDefaultFindParams();
		if (!empty($params)) {
			$query->andWhere($params);
		}

		if (empty(FHtml::getRequestParam('sort'))) {
			$query->orderby(FHtml::getOrderBy($this));
		}

		return $dataProvider;
	}
}