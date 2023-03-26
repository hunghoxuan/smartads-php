<?php

namespace backend\modules\app\models;

use common\components\FHtml;
use common\components\FActiveDataProvider;
use yii\data\ActiveDataProvider;


class AppMembership extends AppMembershipBase
{
	const LOOKUP = [
		'service' => [
			['id' => AppMembership::SERVICE_BUSINESS, 'name' => 'business'],
			['id' => AppMembership::SERVICE_LIBRARY, 'name' => 'library'],
			['id' => AppMembership::SERVICE_ECOMMERCE, 'name' => 'ecommerce'],
			['id' => AppMembership::SERVICE_CONTENT, 'name' => 'content'],
		],
		'type'    => [
			['id' => AppMembership::TYPE_VIP, 'name' => 'vip'],
			['id' => AppMembership::TYPE_PREMIUM, 'name' => 'premium'],
			['id' => AppMembership::TYPE_PRO, 'name' => 'pro'],
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


	public function prepareCustomFields() {
		parent::prepareCustomFields();

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
		$query = AppMembership::find();

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
				'service'        => $this->service,
				'type'           => $this->type,
				'expiry'         => $this->expiry,
				'is_active'      => $this->is_active,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);
		}
		else {
			$query->andFilterWhere([
				'id'             => $this->id,
				'user_id'        => $this->user_id,
				'service'        => $this->service,
				'expiry'         => $this->expiry,
				'is_active'      => $this->is_active,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);

			$query->andFilterWhere(['like', 'type', $this->type]);
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

	// Lookup Object: user
	protected $user;

	public function getUser() {
		if (!isset($this->user)) {
			$this->user = FHtml::getModel('app_user', '', $this->user_id, '', false);
		}

		return $this->user;
	}

	public function setUser($value) {
		$this->user = $value;
	}
}