<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_calendar".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $time
 * @property string $device_id
 * @property string $location
 * @property string $type
 * @property string $owner_name
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class SmartscreenCalendarBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_calendar';

    public static function tableName()
    {
        return 'smartscreen_calendar';
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
            [['id', 'code', 'name', 'description', 'content', 'date', 'time', 'device_id', 'location', 'type', 'owner_name', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['date', 'created_date', 'modified_date'], 'safe'],
            [['code', 'type', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['name', 'time', 'location', 'owner_name'], 'string', 'max' => 255],
            [['description', 'content'], 'string', 'max' => 2000],
            [['device_id'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenCalendar', 'ID'),
            'code' => FHtml::t('SmartscreenCalendar', 'Code'),
            'name' => FHtml::t('SmartscreenCalendar', 'Name'),
            'description' => FHtml::t('SmartscreenCalendar', 'Description'),
            'content' => FHtml::t('SmartscreenCalendar', 'Content'),
            'date' => FHtml::t('SmartscreenCalendar', 'Date'),
            'time' => FHtml::t('SmartscreenCalendar', 'Time'),
            'device_id' => FHtml::t('SmartscreenCalendar', 'Device ID'),
            'location' => FHtml::t('SmartscreenCalendar', 'Location'),
            'type' => FHtml::t('SmartscreenCalendar', 'Type'),
            'owner_name' => FHtml::t('SmartscreenCalendar', 'Owner Name'),
            'created_date' => FHtml::t('SmartscreenCalendar', 'Created Date'),
            'created_user' => FHtml::t('SmartscreenCalendar', 'Created User'),
            'modified_date' => FHtml::t('SmartscreenCalendar', 'Modified Date'),
            'modified_user' => FHtml::t('SmartscreenCalendar', 'Modified User'),
            'application_id' => FHtml::t('SmartscreenCalendar', 'Application ID'),
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
        $i18n->translations['SmartscreenCalendar*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenCalendar' => 'SmartscreenCalendar.php',
            ],
        ];
    }
}