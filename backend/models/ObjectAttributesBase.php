<?php

namespace backend\models;

use common\components\FHtml;
use common\models\BaseDataObject;
use Yii;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "object_attributes".
 *

 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $meta_key
 * @property string $meta_value
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_by
 * @property string $application_id
 */
class ObjectAttributesBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_attributes';

    public static function tableName()
    {
        return 'object_attributes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'meta_key', 'meta_value', 'is_active', 'created_date', 'created_by', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type', 'meta_key', 'is_active'], 'required'],
            [['object_id', 'is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'meta_key', 'meta_value', 'created_by', 'application_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectAttributes', 'ID'),
            'object_id' => FHtml::t('ObjectAttributes', 'Object ID'),
            'object_type' => FHtml::t('ObjectAttributes', 'Object Type'),
            'meta_key' => FHtml::t('ObjectAttributes', 'Meta Key'),
            'meta_value' => FHtml::t('ObjectAttributes', 'Meta Value'),
            'is_active' => FHtml::t('ObjectAttributes', 'Is Active'),
            'created_date' => FHtml::t('ObjectAttributes', 'Created Date'),
            'created_by' => FHtml::t('ObjectAttributes', 'Created By'),
            'application_id' => FHtml::t('ObjectAttributes', 'Application ID'),
        ];
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['ObjectAttributes*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectAttributes' => 'ObjectAttributes.php',
            ],
        ];
    }
}
