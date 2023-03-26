<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_queue".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $ticket
 * @property string $counter
 * @property string $service
 * @property string $service_id
 * @property string $status
 * @property string $note
 * @property string $device_id
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 * @property string $description
 */
class SmartscreenQueueBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_queue';

    public static function tableName()
    {
        return 'smartscreen_queue';
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
            [['id', 'code', 'name', 'ticket', 'counter', 'service', 'service_id', 'status', 'note', 'device_id', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id', 'description'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['code', 'name', 'ticket', 'counter', 'service', 'description'], 'string', 'max' => 255],
            [['service_id', 'status', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['note', 'device_id'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenQueue', 'ID'),
            'code' => FHtml::t('SmartscreenQueue', 'Code'),
            'name' => FHtml::t('SmartscreenQueue', 'Name'),
            'ticket' => FHtml::t('SmartscreenQueue', 'Ticket'),
            'counter' => FHtml::t('SmartscreenQueue', 'Counter'),
            'service' => FHtml::t('SmartscreenQueue', 'Service'),
            'service_id' => FHtml::t('SmartscreenQueue', 'Service ID'),
            'status' => FHtml::t('SmartscreenQueue', 'Status'),
            'note' => FHtml::t('SmartscreenQueue', 'Note'),
            'device_id' => FHtml::t('SmartscreenQueue', 'Device ID'),
            'is_active' => FHtml::t('SmartscreenQueue', 'Is Active'),
            'sort_order' => FHtml::t('SmartscreenQueue', 'Sort Order'),
            'created_date' => FHtml::t('SmartscreenQueue', 'Created Date'),
            'created_user' => FHtml::t('SmartscreenQueue', 'Created User'),
            'application_id' => FHtml::t('SmartscreenQueue', 'Application ID'),
            'description' => FHtml::t('SmartscreenQueue', 'Description'),
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
        $i18n->translations['SmartscreenQueue*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenQueue' => 'SmartscreenQueue.php',
            ],
        ];
    }
}