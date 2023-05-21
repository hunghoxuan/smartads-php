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
 * This is the model class for table "user_feedback".
 *

 * @property integer $id
 * @property string $user_id
 * @property string $object_id
 * @property string $object_type
 * @property string $name
 * @property string $email
 * @property string $comment
 * @property integer $is_active
 * @property string $response
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class UserFeedbackBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_QUESTION = 'Question';
    const TYPE_FEEDBACK = 'Feedback';
    const TYPE_REPORT = 'Report';
    const STATUS_NEW = 'New';
    const STATUS_RECEIVED = 'Received';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_PENDING = 'Pending';
    const STATUS_CLOSED = 'Closed';

    /**
     * @inheritdoc
     */
    public $tableName = 'user_feedback';

    public static function tableName()
    {
        return 'user_feedback';
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

            [['id', 'user_id', 'object_id', 'object_type', 'name', 'email', 'comment', 'is_active', 'response', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['user_id', 'name', 'email', 'comment'], 'required'],
            [['is_active'], 'integer'],
            [['response'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['user_id', 'object_id', 'object_type', 'type', 'status', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['name', 'email'], 'string', 'max' => 200],
            [['comment'], 'string', 'max' => 4000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('UserFeedback', 'ID'),
            'user_id' => FHtml::t('UserFeedback', 'User ID'),
            'object_id' => FHtml::t('UserFeedback', 'Object ID'),
            'object_type' => FHtml::t('UserFeedback', 'Object Type'),
            'name' => FHtml::t('UserFeedback', 'Name'),
            'email' => FHtml::t('UserFeedback', 'Email'),
            'comment' => FHtml::t('UserFeedback', 'Comment'),
            'is_active' => FHtml::t('UserFeedback', 'Is Active'),
            'response' => FHtml::t('UserFeedback', 'Response'),
            'type' => FHtml::t('UserFeedback', 'Type'),
            'status' => FHtml::t('UserFeedback', 'Status'),
            'created_date' => FHtml::t('UserFeedback', 'Created Date'),
            'created_user' => FHtml::t('UserFeedback', 'Created User'),
            'modified_date' => FHtml::t('UserFeedback', 'Modified Date'),
            'modified_user' => FHtml::t('UserFeedback', 'Modified User'),
            'application_id' => FHtml::t('UserFeedback', 'Application ID'),
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
        $i18n->translations['UserFeedback*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/users/messages',
            'fileMap' => [
                'UserFeedback' => 'UserFeedback.php',
            ],
        ];
    }
}
