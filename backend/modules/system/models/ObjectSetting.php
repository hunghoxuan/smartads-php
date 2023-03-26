<?php

namespace backend\modules\system\models;

use common\components\FConstant;
use common\models\BaseDataObject;
use Faker\Provider\Base;
use Yii;
use common\components\FHtml;

/**
 * This is the model class for table "object_setting".
 *
 * @property integer $id
 * @property string $object_type
 * @property string $meta_key
 * @property string $key
 * @property string $value
 * @property string $description
 * @property string $icon
 * @property string $color
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $application_id
 */
class ObjectSetting extends \common\models\BaseModel
{
    public $Items;
    public $file;
    public $values;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['description'], 'string'],
            [['sort_order'], 'integer'],
            [['file'], 'file', 'extensions'=> 'png, jpg, jpeg'],
            [['object_type', 'meta_key', 'key', 'value', 'icon', 'color', 'application_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('app', 'ID'),
            'object_type' => FHtml::t('app', 'Object Type'),
            'meta_key' => FHtml::t('app', 'Meta Key'),
            'key' => FHtml::t('app', 'Key'),
            'value' => FHtml::t('app', 'Value'),
            'description' => FHtml::t('app', 'Description'),
            'icon' => FHtml::t('app', 'Icon'),
            'color' => FHtml::t('app', 'Color'),
            'is_active' => FHtml::t('app', 'Is Active'),
            'sort_order' => FHtml::t('app', 'Sort Order'),
            'application_id' => FHtml::t('app', 'Application ID'),
        ];
    }

    public static function getDb()
    {
        return FHtml::currentDb();
    }

    public function beforeValidate()
    {
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function beforeSave($insert)
    {
        if (empty($this->object_type))
            $this->object_type = FConstant::TABLES_COMMON;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
