<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $thumbnail
 * @property string $image
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $translations
 * @property string $properties
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $is_top
 * @property integer $is_hot
 * @property string $object_type
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class ObjectCategoryBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_category';

    public static function tableName()
    {
        return 'object_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'thumbnail', 'image', 'name', 'description', 'content', 'translations', 'properties', 'sort_order', 'is_active', 'is_top', 'is_hot', 'object_type', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['parent_id', 'sort_order', 'is_active', 'is_top', 'is_hot'], 'integer'],
            [['name'], 'required'],
            [['description', 'content', 'translations', 'properties'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['thumbnail'], 'string', 'max' => 300],
            [['image', 'name'], 'string', 'max' => 255],
            [['object_type'], 'string', 'max' => 50],
            [['application_id'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectCategory', 'ID'),
            'parent_id' => FHtml::t('ObjectCategory', 'Parent ID'),
            'thumbnail' => FHtml::t('ObjectCategory', 'Thumbnail'),
            'image' => FHtml::t('ObjectCategory', 'Image'),
            'name' => FHtml::t('ObjectCategory', 'Name'),
            'description' => FHtml::t('ObjectCategory', 'Description'),
            'content' => FHtml::t('ObjectCategory', 'Content'),
            'translations' => FHtml::t('ObjectCategory', 'Translations'),
            'properties' => FHtml::t('ObjectCategory', 'Properties'),
            'sort_order' => FHtml::t('ObjectCategory', 'Sort Order'),
            'is_active' => FHtml::t('ObjectCategory', 'Is Active'),
            'is_top' => FHtml::t('ObjectCategory', 'Is Top'),
            'is_hot' => FHtml::t('ObjectCategory', 'Is Hot'),
            'object_type' => FHtml::t('ObjectCategory', 'Object Type'),
            'created_date' => FHtml::t('ObjectCategory', 'Created Date'),
            'modified_date' => FHtml::t('ObjectCategory', 'Modified Date'),
            'application_id' => FHtml::t('ObjectCategory', 'Application ID'),
        ];
    }


}