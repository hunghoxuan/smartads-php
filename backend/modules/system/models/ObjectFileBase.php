<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "object_file".
 *

 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $thumbnail
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $file
 * @property string $file_type
 * @property string $status
 * @property integer $is_active
 * @property string $file_size
 * @property string $file_duration
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class ObjectFileBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_file';

    public static function tableName()
    {
        return 'object_file';
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

            [['id', 'object_id', 'object_type', 'thumbnail', 'name', 'description', 'type', 'file', 'file_type', 'status', 'is_active', 'file_size', 'file_duration', 'sort_order', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type', 'type', 'file'], 'required'],
            [['object_id', 'is_active', 'sort_order'], 'integer'],
            [['object_type', 'type', 'status', 'created_date', 'modified_date'], 'string', 'max' => 20],
            [['thumbnail', 'name', 'file', 'file_size', 'file_duration'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['file_type', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectFile', 'ID'),
            'object_id' => FHtml::t('ObjectFile', 'Object ID'),
            'object_type' => FHtml::t('ObjectFile', 'Object Type'),
            'thumbnail' => FHtml::t('ObjectFile', 'Thumbnail'),
            'name' => FHtml::t('ObjectFile', 'Name'),
            'description' => FHtml::t('ObjectFile', 'Description'),
            'type' => FHtml::t('ObjectFile', 'Type'),
            'file' => FHtml::t('ObjectFile', 'File'),
            'file_type' => FHtml::t('ObjectFile', 'File Type'),
            'status' => FHtml::t('ObjectFile', 'Status'),
            'is_active' => FHtml::t('ObjectFile', 'Is Active'),
            'file_size' => FHtml::t('ObjectFile', 'File Size'),
            'file_duration' => FHtml::t('ObjectFile', 'File Duration'),
            'sort_order' => FHtml::t('ObjectFile', 'Sort Order'),
            'created_date' => FHtml::t('ObjectFile', 'Created Date'),
            'created_user' => FHtml::t('ObjectFile', 'Created User'),
            'modified_date' => FHtml::t('ObjectFile', 'Modified Date'),
            'modified_user' => FHtml::t('ObjectFile', 'Modified User'),
            'application_id' => FHtml::t('ObjectFile', 'Application ID'),
        ];
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['ObjectFile*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'ObjectFile' => 'ObjectFile.php',
            ],
        ];
    }
}
