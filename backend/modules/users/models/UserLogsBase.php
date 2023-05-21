<?php

namespace backend\modules\users\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "user_logs".
 *

 * @property string $id
 * @property string $log_date
 * @property string $user_id
 * @property string $action
 * @property string $object_type
 * @property integer $object_id
 * @property string $link_url
 * @property string $ip_address
 * @property string $duration
 * @property string $note
 * @property string $status
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class UserLogsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'user_logs';

    public static function tableName()
    {
        return 'user_logs';
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

            [['id', 'log_date', 'user_id', 'action', 'object_type', 'object_id', 'link_url', 'ip_address', 'duration', 'note', 'status', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],

            [['user_id'], 'required'],
            [['object_id'], 'integer'],
            [['note'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['log_date'], 'string', 'max' => 18],
            [['user_id', 'application_id'], 'string', 'max' => 100],
            [['action', 'object_type', 'link_url', 'ip_address', 'duration', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('UserLogs', 'ID'),
            'log_date' => FHtml::t('UserLogs', 'Log Date'),
            'user_id' => FHtml::t('UserLogs', 'User ID'),
            'action' => FHtml::t('UserLogs', 'Action'),
            'object_type' => FHtml::t('UserLogs', 'Object Type'),
            'object_id' => FHtml::t('UserLogs', 'Object ID'),
            'link_url' => FHtml::t('UserLogs', 'Link Url'),
            'ip_address' => FHtml::t('UserLogs', 'Ip Address'),
            'duration' => FHtml::t('UserLogs', 'Duration'),
            'note' => FHtml::t('UserLogs', 'Note'),
            'status' => FHtml::t('UserLogs', 'Status'),
            'created_date' => FHtml::t('UserLogs', 'Created Date'),
            'modified_date' => FHtml::t('UserLogs', 'Modified Date'),
            'application_id' => FHtml::t('UserLogs', 'Application ID'),
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
        $i18n->translations['UserLogs*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/users/messages',
            'fileMap' => [
                'UserLogs' => 'UserLogs.php',
            ],
        ];
    }
}
