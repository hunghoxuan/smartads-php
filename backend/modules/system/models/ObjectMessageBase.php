<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_message".
 *
 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $title
 * @property string $message
 * @property string $method
 * @property string $send_date
 * @property integer $sender_id
 * @property string $sender_type
 * @property string $type
 * @property string $status
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectMessageBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const METHOD_PUSH = 'push';
    const METHOD_EMAIL = 'email';
    const METHOD_SMS = 'sms';
    const TYPE_NOTIFY = 'notify';
    const TYPE_WARNING = 'warning';
    const TYPE_BIRTHDAY = 'birthday';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_REMIND = 'remind';
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_message';

    public static function tableName()
    {
        return 'object_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'title', 'message', 'method', 'send_date', 'sender_id', 'sender_type', 'type', 'status', 'is_active', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'message'], 'required'],
            [['send_date', 'created_date'], 'safe'],
            [['sender_id', 'is_active'], 'integer'],
            [['object_id', 'object_type', 'method', 'sender_type', 'type', 'status', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 255],
            [['message'], 'string', 'max' => 4000],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectMessage', 'ID'),
            'object_id' => FHtml::t('ObjectMessage', 'Object ID'),
            'object_type' => FHtml::t('ObjectMessage', 'Object Type'),
            'title' => FHtml::t('ObjectMessage', 'Title'),
            'message' => FHtml::t('ObjectMessage', 'Message'),
            'method' => FHtml::t('ObjectMessage', 'Method'),
            'send_date' => FHtml::t('ObjectMessage', 'Send Date'),
            'sender_id' => FHtml::t('ObjectMessage', 'Sender ID'),
            'sender_type' => FHtml::t('ObjectMessage', 'Sender Type'),
            'type' => FHtml::t('ObjectMessage', 'Type'),
            'status' => FHtml::t('ObjectMessage', 'Status'),
            'is_active' => FHtml::t('ObjectMessage', 'Is Active'),
            'created_date' => FHtml::t('ObjectMessage', 'Created Date'),
            'created_user' => FHtml::t('ObjectMessage', 'Created User'),
            'application_id' => FHtml::t('ObjectMessage', 'Application ID'),
        ];
    }


}