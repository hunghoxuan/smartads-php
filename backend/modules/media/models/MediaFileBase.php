<?php

namespace backend\modules\media\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/*
 * This is the model class for table "media_file".
 *

 * @property string $id
 * @property string $name
 * @property string $image
 * @property string $file
 * @property string $file_path
 * @property string $description
 * @property string $file_type
 * @property string $file_size
 * @property string $file_duration
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */

class MediaFileBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'media_file';

    public static function tableName()
    {
        return 'media_file';
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

            [['id', 'name', 'image', 'file', 'file_path', 'description', 'file_type', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['name'], 'required'],
            [['is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['name', 'file_path', 'file_size', 'file_duration'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 300],
            [['file'], 'string', 'max' => 555],
            [['description'], 'string', 'max' => 2000],
            [['file_type', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('MediaFile', 'ID'),
            'name' => FHtml::t('MediaFile', 'Name'),
            'image' => FHtml::t('MediaFile', 'Image'),
            'file' => FHtml::t('MediaFile', 'File'),
            'file_path' => FHtml::t('MediaFile', 'File Path'),
            'description' => FHtml::t('MediaFile', 'Description'),
            'file_type' => FHtml::t('MediaFile', 'File Type'),
            'file_size' => FHtml::t('MediaFile', 'File Size'),
            'file_duration' => FHtml::t('MediaFile', 'File Duration'),
            'is_active' => FHtml::t('MediaFile', 'Is Active'),
            'sort_order' => FHtml::t('MediaFile', 'Sort Order'),
            'created_date' => FHtml::t('MediaFile', 'Created Date'),
            'created_user' => FHtml::t('MediaFile', 'Created User'),
            'application_id' => FHtml::t('MediaFile', 'Application ID'),
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
        $i18n->translations['MediaFile*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/media/messages',
            'fileMap' => [
                'MediaFile' => 'MediaFile.php',
            ],
        ];
    }
}
