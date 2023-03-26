<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_calendar".
 *
 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $color
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string $all_day
 * @property string $status
 * @property string $link_url
 * @property string $type
 * @property string $created_user
 * @property string $created_date
 * @property string $application_id
 */
class ObjectCalendarBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_calendar';

    public static function tableName()
    {
        return 'object_calendar';
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
            [['id', 'object_id', 'object_type', 'color', 'title', 'start_date', 'end_date', 'all_day', 'status', 'link_url', 'type', 'created_user', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'color', 'all_day', 'status', 'type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['title', 'link_url'], 'string', 'max' => 255],
            [['start_date', 'end_date'], 'string', 'max' => 48],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectCalendar', 'ID'),
            'object_id' => FHtml::t('ObjectCalendar', 'Object ID'),
            'object_type' => FHtml::t('ObjectCalendar', 'Object Type'),
            'color' => FHtml::t('ObjectCalendar', 'Color'),
            'title' => FHtml::t('ObjectCalendar', 'Title'),
            'start_date' => FHtml::t('ObjectCalendar', 'Start Date'),
            'end_date' => FHtml::t('ObjectCalendar', 'End Date'),
            'all_day' => FHtml::t('ObjectCalendar', 'All Day'),
            'status' => FHtml::t('ObjectCalendar', 'Status'),
            'link_url' => FHtml::t('ObjectCalendar', 'Link Url'),
            'type' => FHtml::t('ObjectCalendar', 'Type'),
            'created_user' => FHtml::t('ObjectCalendar', 'Created User'),
            'created_date' => FHtml::t('ObjectCalendar', 'Created Date'),
            'application_id' => FHtml::t('ObjectCalendar', 'Application ID'),
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
        $i18n->translations['ObjectCalendar*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'ObjectCalendar' => 'ObjectCalendar.php',
            ],
        ];
    }
}