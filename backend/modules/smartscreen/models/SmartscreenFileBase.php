<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "smartscreen_file".
 *

 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $command
 * @property string $file
 * @property string $title
 * @property string $description
 * @property string $file_kind
 * @property string $file_size
 * @property string $file_duration
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class SmartscreenFileBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_file';

    public static function tableName()
    {
        return 'smartscreen_file';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'command', 'title', 'file', 'description', 'file_kind', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id'], 'required'],
            [['object_id', 'is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['file'], 'string', 'max' => 555],
            [['description'], 'string', 'max' => 2000],
            [['file_kind', 'file_duration', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['file_size', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenFile', 'ID'),
            'object_id' => FHtml::t('SmartscreenFile', 'Object ID'),
            'object_type' => FHtml::t('SmartscreenFile', 'Object Type'),
            'command' => FHtml::t('SmartscreenFile', 'Command'),
            'file' => FHtml::t('SmartscreenFile', 'File'),
            'description' => FHtml::t('SmartscreenFile', 'Description'),
            'file_kind' => FHtml::t('SmartscreenFile', 'File Kind'),
            'file_size' => FHtml::t('SmartscreenFile', 'File Size'),
            'file_duration' => FHtml::t('SmartscreenFile', 'File Duration'),
            'is_active' => FHtml::t('SmartscreenFile', 'Is Active'),
            'sort_order' => FHtml::t('SmartscreenFile', 'Sort Order'),
            'created_date' => FHtml::t('SmartscreenFile', 'Created Date'),
            'created_user' => FHtml::t('SmartscreenFile', 'Created User'),
            'application_id' => FHtml::t('SmartscreenFile', 'Application ID'),
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
        $i18n->translations['SmartscreenFile*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenFile' => 'SmartscreenFile.php',
            ],
        ];
    }
}
