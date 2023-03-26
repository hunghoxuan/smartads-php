<?php

namespace backend\modules\system\models;

use backend\modules\app\models\AppUser;
use common\components\FConstant;
use common\components\FHtml;
use common\components\FActiveDataProvider;
use yii\data\ActiveDataProvider;

/**
 * Class ObjectRequest
 * @package backend\modules\system\models
 * @property AppUser user
 */
class ObjectRequest extends ObjectRequestBase
{
	const LOOKUP = [
		'type'      => [
			['id' => ObjectRequest::TYPE_VIP, 'name' => 'vip'],
			['id' => ObjectRequest::TYPE_MODERATOR, 'name' => 'moderator'],
			['id' => ObjectRequest::TYPE_UNLOCK, 'name' => 'unlock'],
		],
		'user_type' => [
			['id' => ObjectRequest::USER_TYPE_APP_USER, 'name' => 'app_user'],
			['id' => ObjectRequest::USER_TYPE_USER, 'name' => 'user'],
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
		$query = ObjectRequest::find();

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
				'object_id'      => $this->object_id,
				'object_type'    => $this->object_type,
				'name'           => $this->name,
				'email'          => $this->email,
				'type'           => $this->type,
				'is_active'      => $this->is_active,
				'user_id'        => $this->user_id,
				'user_type'      => $this->user_type,
				'user_role'      => $this->user_role,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);
		}
		else {
			$query->andFilterWhere([
				'id'             => $this->id,
				'object_id'      => $this->object_id,
				'object_type'    => $this->object_type,
				'type'           => $this->type,
				'is_active'      => $this->is_active,
				'user_id'        => $this->user_id,
				'user_type'      => $this->user_type,
				'user_role'      => $this->user_role,
				'created_date'   => $this->created_date,
				'modified_date'  => $this->modified_date,
				'application_id' => $this->application_id,
			]);

			$query->andFilterWhere(['like', 'name', $this->name])->andFilterWhere(['like', 'email', $this->email]);
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

	public function showTextUserRole() {
		switch ($this->user_role) {
			case FConstant::ROLE_MODERATOR:
				$text = self::TYPE_MODERATOR;
				$text ="<span class=\"badge badge-primary\">  $text </span>";
				break;
			case FConstant::ROLE_USER:
				$text = self::USER_TYPE_USER;
				$text ="<span class=\"badge badge-default\">  $text </span>";
				break;
			default:
				$text = $this->user_role;
				$text ="<span class=\"badge badge-warning\">  $text </span>";
				break;
		}
		return $text;
	}
}