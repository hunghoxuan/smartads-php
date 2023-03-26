<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "app_notification".
 *

 * @property string $id
 * @property string $message
 * @property string $action
 * @property string $params
 * @property string $sent_type
 * @property string $sent_date
 * @property string $receiver_count
 * @property string $receiver_users
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class AppNotificationBase extends BaseModel //\yii\db\ActiveRecord
{
    const SENT_TYPE_SMS = 'sms';
    const SENT_TYPE_APP = 'app';
    const SENT_TYPE_EMAIL = 'email';
    const SENT_TYPE_MESSAGE = 'message';
    const SENT_TYPE_ALL = 'all';

    /**
    * @inheritdoc
    */
    public $tableName = 'app_notification';
    public $sent_type_array;

    public static function tableName()
    {
        return 'app_notification';
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
        
            [['id', 'message', 'action', 'params', 'sent_date', 'receiver_count', 'receiver_users', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['message'], 'required'],
            [['sent_date', 'created_date','sent_type'], 'safe'],
            [['receiver_count'], 'integer'],
            [['receiver_users'], 'string'],
            [['message', 'params'], 'string', 'max' => 2000],
            [['action'], 'string', 'max' => 255],
            [[ 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('AppNotification', 'ID'),
                    'message' => FHtml::t('AppNotification', 'Message'),
                    'action' => FHtml::t('AppNotification', 'Action'),
                    'params' => FHtml::t('AppNotification', 'Params'),
                    'sent_type' => FHtml::t('AppNotification', 'Sent Type'),
                    'sent_date' => FHtml::t('AppNotification', 'Sent Date'),
                    'receiver_count' => FHtml::t('AppNotification', 'Receiver Count'),
                    'receiver_users' => FHtml::t('AppNotification', 'Receiver Users'),
                    'created_date' => FHtml::t('AppNotification', 'Created Date'),
                    'created_user' => FHtml::t('AppNotification', 'Created User'),
                    'application_id' => FHtml::t('AppNotification', 'Application ID'),
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
        $i18n->translations['AppNotification*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/app/messages',
            'fileMap' => [
                'AppNotification' => 'AppNotification.php',
            ],
        ];
    }




}
