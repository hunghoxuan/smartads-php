<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_file".
 *
 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $file
 * @property string $title
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'file', 'title', 'description', 'file_type', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'object_type', 'title'], 'required'],
            [['object_id', 'is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'file_type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['file'], 'string', 'max' => 555],
            [['title', 'file_size', 'file_duration'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
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
            'file' => FHtml::t('ObjectFile', 'File'),
            'title' => FHtml::t('ObjectFile', 'Title'),
            'description' => FHtml::t('ObjectFile', 'Description'),
            'file_type' => FHtml::t('ObjectFile', 'File Type'),
            'file_size' => FHtml::t('ObjectFile', 'File Size'),
            'file_duration' => FHtml::t('ObjectFile', 'File Duration'),
            'is_active' => FHtml::t('ObjectFile', 'Is Active'),
            'sort_order' => FHtml::t('ObjectFile', 'Sort Order'),
            'created_date' => FHtml::t('ObjectFile', 'Created Date'),
            'created_user' => FHtml::t('ObjectFile', 'Created User'),
            'application_id' => FHtml::t('ObjectFile', 'Application ID'),
        ];
    }


}