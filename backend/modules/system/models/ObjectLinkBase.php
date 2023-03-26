<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_link".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $name
 * @property string $link_url
 * @property string $type
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 * @property string $image
 * @property string $description
 * @property string $label
 * @property string $target
 * @property integer $sort_order
 */
class ObjectLinkBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_TAG = 'tag';
    const TYPE_NEWS = 'news';
    const TYPE_PAPER = 'paper';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_link';

    public static function tableName()
    {
        return 'object_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'name', 'link_url', 'type', 'is_active', 'created_date', 'created_user', 'application_id', 'image', 'description', 'label', 'target', 'sort_order'], 'filter', 'filter' => 'trim'],
            [['object_id', 'object_type', 'name'], 'required'],
            [['object_id', 'is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'type', 'created_user', 'application_id', 'target'], 'string', 'max' => 100],
            [['name', 'label'], 'string', 'max' => 255],
            [['link_url'], 'string', 'max' => 1000],
            [['image'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    public function fields()
    {
        return [
        'id', 'object_id', 'object_type', 'name', 'link_url', 'type', 'is_active', 'created_date', 'created_user', 'application_id', 'image', 'description', 'label', 'target', 'sort_order'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectLink', 'ID'),
            'object_id' => FHtml::t('ObjectLink', 'Object ID'),
            'object_type' => FHtml::t('ObjectLink', 'Object Type'),
            'name' => FHtml::t('ObjectLink', 'Name'),
            'link_url' => FHtml::t('ObjectLink', 'Link Url'),
            'type' => FHtml::t('ObjectLink', 'Type'),
            'is_active' => FHtml::t('ObjectLink', 'Is Active'),
            'created_date' => FHtml::t('ObjectLink', 'Created Date'),
            'created_user' => FHtml::t('ObjectLink', 'Created User'),
            'application_id' => FHtml::t('ObjectLink', 'Application ID'),
            'image' => FHtml::t('ObjectLink', 'Image'),
            'description' => FHtml::t('ObjectLink', 'Description'),
            'label' => FHtml::t('ObjectLink', 'Label'),
            'target' => FHtml::t('ObjectLink', 'Target'),
            'sort_order' => FHtml::t('ObjectLink', 'Sort Order'),
        ];
    }


}