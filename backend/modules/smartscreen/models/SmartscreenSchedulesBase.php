<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_schedules".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $layout_id
 * @property string $content_id
 * @property string $frame_id
 * @property string $start_time
 * @property string $date
 * @property string $date_end
 * @property string $days
 * @property string $type
 * @property string $channel_id
 * @property integer $loop
 * @property integer $duration
 * @property string $application_id
 */
class SmartscreenSchedulesBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_schedules';

    public static function tableName()
    {
        return 'smartscreen_schedules';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id', 'layout_id', 'content_id', 'frame_id', 'start_time', 'date', 'date_end', 'days', 'type', 'channel_id', 'loop', 'duration', 'application_id'], 'filter', 'filter' => 'trim'],
            [['date', 'date_end'], 'safe'],
            [['loop', 'duration'], 'integer'],
            [['device_id', 'layout_id', 'content_id', 'frame_id', 'start_time', 'type', 'channel_id', 'application_id'], 'string', 'max' => 100],
            [['days'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenSchedules', 'ID'),
            'device_id' => FHtml::t('SmartscreenSchedules', 'Device ID'),
            'layout_id' => FHtml::t('SmartscreenSchedules', 'Layout ID'),
            'content_id' => FHtml::t('SmartscreenSchedules', 'Content ID'),
            'frame_id' => FHtml::t('SmartscreenSchedules', 'Frame ID'),
            'start_time' => FHtml::t('SmartscreenSchedules', 'Start Time'),
            'date' => FHtml::t('SmartscreenSchedules', 'Date'),
            'date_end' => FHtml::t('SmartscreenSchedules', 'Date End'),
            'days' => FHtml::t('SmartscreenSchedules', 'Days'),
            'type' => FHtml::t('SmartscreenSchedules', 'Type'),
            'channel_id' => FHtml::t('SmartscreenSchedules', 'Channel ID'),
            'loop' => FHtml::t('SmartscreenSchedules', 'Loop'),
            'duration' => FHtml::t('SmartscreenSchedules', 'Duration'),
            'application_id' => FHtml::t('SmartscreenSchedules', 'Application ID'),
        ];
    }

    public function getOnwerIdField()
    {
        return 'owner_id';
    }

    public function getIsActiveField()
    {
        return 'loop';
    }
}
