<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FActiveDataProvider;
use common\components\FHtml;

use backend\models\ObjectCategory;

/**
 * ObjectCategory represents the model behind the search form about `backend\models\ObjectCategory`.
 */
class ObjectCategorySearch extends ObjectCategory
{
	// add custom (default) search params here
	public function getDefaultFindParams() {
		$arr = [];
		return $arr;
	}

	public static function createNew($values = [], $isSave = false) {
		return ObjectCategory::createNew();
	}
}