<?php

namespace backend\models;

use backend\modules\ecommerce\models\Product;
use common\components\FHtml;
use common\components\FActiveDataProvider;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class ObjectCategory extends ObjectCategoryBase
{
	public $action;
	public $control;
	public $params;

	const OBJECT_TYPE_BLOG    = 'blog';
	const OBJECT_TYPE_PRODUCT = 'product';
	const OBJECT_TYPE_BOOK    = 'book';

	const LOOKUP          = [
    ];
	const COLUMNS_UPLOAD  = ['image'];
	const OBJECTS_META    = [];
	const OBJECTS_RELATED = [];

	public $order_by = 'object_type asc, sort_order asc, parent_id asc, name asc';


	public static function getLookupArray($column = '') {
		if (key_exists($column, self::LOOKUP)) {
			return self::LOOKUP[$column];
		}

		return parent::getLookupArray($column);
	}

	public static function ColumnsArray() {
		return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
	}

    public function isDBLanguagesEnabled() {
        return FHtml::settingDBLanguaguesEnabled();
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

	public function getObjectType() {
		return '';
	}

	/**
	 * @return array|\yii\db\ActiveRecord[]
	 * @throws \yii\base\InvalidConfigException
	 */
	public static function parent_idArray() {
		$query = static::find();

		return $query->all();
	}

	/**
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function object_typeArray() {
		return isset(self::LOOKUP['object_type']) ? self::LOOKUP['object_type'] : [];
	}

	public function getImageurl() {
		$url = Yii::$app->request->baseUrl . '/images/category/' . $this->image;

		return str_replace('web/', '', $url);
	}

	public static function itemAlias($type, $code = null) {
		$_items = array(
			'status' => array(
				'0' => 'Inactive',
				'1' => 'Active',
			),
		);
		if (isset($code)) {
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		}
		else {
			return isset($_items[$type]) ? $_items[$type] : false;
		}
	}

	public function getProducts() {
		$list = Product::find()->where("category_id like '%$this->id%'");

		return $list;
	}

	public function getKey() {
		return $this->id;
	}

	public function getValue() {
		return $this->name;
	}

	public function getCode() {
		return $this->id;
	}

	public function getNotTranslatedFields() {
		return array_merge(parent::getNotTranslatedFields(), ['is_top', 'is_hot', 'type', 'parent_id', 'sort_order']); // TODO: Change the autogenerated stub
	}

	public function beforeSave($insert)
    {
        if (empty($this->code) && strpos($this->object_type, '.') !== false)
            $this->code = strtolower(str_replace(' ', '_', $this->name));

        if (!isset($this->is_active) && $this->isNewRecord)
            $this->is_active = true;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
	 * @param $insert
	 * @param $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes) {
		if ($insert) {
			$this->sort_order = $this->id;
			$this->save();
		}
		parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
	}

    public static function findAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = false, $load_activeonly = true)
    {
        $models = parent::findAll($condition, $order_by, $page_size, $page, $isCached, $display_fields, $asArray, $load_activeonly);

        $models = static::makeTreeViewModels($models);

        return $models;
    }

    public function search($params, $andWhere = '')
    {
        $dataProvider = parent::search($params, $andWhere);
        $models = isset($dataProvider) ? $dataProvider->models : [];

        $models = static::makeTreeViewModels($models);

        return $dataProvider = new FActiveDataProvider([
            'models' => $models,
        ]);
    }
}