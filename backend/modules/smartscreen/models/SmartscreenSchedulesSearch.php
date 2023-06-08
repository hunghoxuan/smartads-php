<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\smartscreen\models\SmartscreenSchedules;

/**
 * SmartscreenSchedules represents the model behind the search form about `backend\modules\smartscreen\models\SmartscreenSchedules`.
 */
class SmartscreenSchedulesSearch extends SmartscreenSchedulesBase
{
    public $_start_time;
    public $campaign_id;
    const DURATION_KIND_LOOP = 'number';
    const DURATION_KIND_MINUTES = 'time';
    const DURATION_KIND_SECOND = 'second';

    const FIELD_CAMPAIGN_ID = 'frame_id';
    const FIELD_STATUS = 'loop';
    const FIELD_NAME = 'layout_id';

    public $kind;
    public $name;
    public $is_active;
    public $_times;
    public $end_time;
    public $list_content;
    public $_content_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'content_id', 'channel_id', 'frame_id', 'date', 'type', 'application_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getDefaultFindParams()
    {
        $arr = [];
        if (FHtml::isRoleUser()) {
            $arr = [$this->getFieldCreatedUserId() => FHtml::getCurrentUserId()];
        }

        return $arr;
    }

    public function search($params, $andWhere = '')
    {
        Smartscreen::setDefaultTimezone();

        $query = SmartscreenSchedules::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        FHtml::loadParams($this, $params);

        $date = $this->date;
        $date_end = $this->date_end;

        $start_time = null;
        $finished_schedule_id = null;
        $schedule_id = null;
        $limit = -1;

        $device_id = !empty($this->device_id) ? $this->device_id : null;
        $channel_id = !empty($this->channel_id) ? $this->channel_id : null;
        $campaign_id = !empty($this->campaign_id) ? $this->campaign_id : null;

        $null_value = FHtml::NULL_VALUE;
        if ($channel_id == $null_value)
            $channel_id = null;

        if ($campaign_id == $null_value)
            $campaign_id = null;

        if ($device_id == $null_value)
            $device_id = null;

        if (!empty($this->_start_time))
            $start_time = $this->_start_time;

        $schedules = [];
        $show_all = 1; //FHtml::getRequestParam('show_all', 1);
        $forAPI = ($show_all == 0 || $show_all == 2) ? true : false;

        if (!empty($campaign_id)) { // campaign
            $schedules = Smartscreen::findSchedulesForCampaign($campaign_id, $date, $date_end, $start_time, $limit, $forAPI);
        } else if (empty($channel_id) && empty($device_id) && empty($campaign_id)) { // select all ?
            $schedules = [];
            $channels = SmartscreenChannels::findAll(['is_active' => 1]);
            foreach ($channels as $channel) {
                $listSchedule = Smartscreen::findSchedulesForChannel($channel->id, $date, $date_end, $start_time, $limit, $forAPI, $show_all == 0);
                $schedules = array_merge($schedules, $listSchedule);
            }
        } else if (!empty($channel_id) && empty($device_id) && empty($campaign_id)) { //channel
            $schedules = Smartscreen::findSchedulesForChannel($channel_id, $date, $date_end, $start_time, $limit, $forAPI, $show_all == 0);
        } else { // device
            $autoCalculateStarttime = !empty($device_id);
            $device = SmartscreenStation::findOneCached($device_id);
            if (isset($device) && (!empty($channel_id) && $channel_id != $device->channel_id)) {
                $schedules = [];
            } else {
                $listSchedule = Smartscreen::findSchedules($device_id, $channel_id, $campaign_id, $schedule_id, $limit, $forAPI, $date, $date_end);
                $listSchedule = Smartscreen::fixSchedules($listSchedule, $date, $start_time, $forAPI, $autoCalculateStarttime);

                if (!empty($campaign_id) && $show_all == 2) {
                    $campaign = SmartscreenCampaigns::findOne($campaign_id);
                    $listSchedule = Smartscreen::generateSchedules($listSchedule, $campaign->start_time, $campaign->duration, null, 150, false);
                }
                $schedules = $listSchedule;
            }
        }

        if (isset($schedules[0]) && isset($schedules[0]['schedules'])) {
            $schedules = $schedules[0]['schedules'];
        }

        $dataProvider->models = $schedules;
        return $dataProvider;
    }

    public function getCustomFields()
    {
        $result = ['start_time2', 'date2'];
        if (FHtml::field_exists($this, 'COLUMNS_API'))
            $result = array_merge($result, $this::COLUMNS_API);

        if (FHtml::field_exists($this, 'COLUMNS_CUSTOM'))
            $result = array_merge($result, $this::COLUMNS_CUSTOM);

        return $result;
    }

    public static function findAllCached()
    {
        $result = Smartscreen::Cache(self::tableName());
        if (isset($result) && !empty($result))
            return $result;

        $result = static::findAll();
        Smartscreen::Cache(self::tableName(), $result);
        return $result;
    }

    public static function findOneCached($id)
    {
        if (empty($id) || $id == FHtml::NULL_VALUE)
            return null;
        if (!Smartscreen::isObjectCachable(static::tableName()))
            return static::findOne($id);

        $models = static::findAllCached();
        foreach ($models as $model) {
            if ($model->id == $id)
                return $model;
        }
        return static::findOne($id);
    }

    public function getSmartLayout()
    {
        return $this->hasOne(SmartscreenLayoutsAPI::className(), ['id' => 'layout_id']);
    }

    public function getDevice()
    {
        return $this->hasOne(SmartscreenStationAPI::className(), ['id' => 'device_id']);
    }

    public function getLayout()
    {
        return $this->hasOne(SmartscreenLayoutsAPI::className(), ['id' => 'layout_id']);
    }

    public function getContent()
    {
        return $this->hasOne(SmartscreenContent::className(), ['id' => 'content_id']);
    }

    public function showPreview($showTable = false)
    {
        if (empty($this->date) && empty($this->date_end))
            $date = "<span style='color: grey'>" . FHtml::t('All') . "</span>";
        else if ($this->date == $this->date_end)
            $date = "$this->date <span style='color: grey'><br/>01 day campaign</span>";
        else {
            if (empty($this->date_end))
                $date = "$this->date <span style='color: grey'><br/>until forever</span>";
            else if (empty($this->date))
                if ($this->date_end >= date("Y-m-d"))
                    $date = "<span style='color: grey'>Ends at <br/></span> $this->date_end";
                else
                    $date = "<span style='color: grey'>Ends at <br/><b>$this->date_end</b> </span>";
            else
                $date = "<span style='color: grey'></span>$this->date  <br/> <span style='color: grey'>-</span> $this->date_end";
        }
        if ($this->date !== $this->date_end) {
            $days = !empty($this->days) ? FHtml::decode($this->days) : [];
            $days1 = [];
            foreach ($days as $day) {
                $days1[] = $day == 0 ? "CN" : "T" . ($day + 1);
            }
        } else
            $days1 = [];

        $time = implode(',', $days1);
        if (!empty($time))
            $time .= "<br/>";
        if (!empty($this->start_time))
            $time .= "<span class=\"glyphicon glyphicon-time\"></span> <b>$this->start_time";
        if (!empty($this->duration))
            $time .= " - " . Smartscreen::getNextStartTime($this->start_time, $this->duration, 1, null, true) . '</b>';

        if ($showTable === 'date')
            return $date;
        else if ($showTable === 'time')
            return $time;
        else if ($showTable === 'datetime')
            return "$date <br/> $time";

        $result = [];
        $stations = SmartscreenStation::findAllCached();
        $deviceIds = FHtml::decode($this->device_id);
        if (!is_array($deviceIds))
            $deviceIds = [];
        if (!empty($deviceIds)) {
            foreach ($stations as $i => $station) {
                if (!in_array($station->id, $deviceIds)) {
                    unset($stations[$i]);
                    continue;
                }
            }
        }
        $channel = (!empty($this->channel_id) && $this->channel_id != FHtml::NULL_VALUE) ? SmartscreenChannels::findOneCached($this->channel_id) : null;
        $devices = (!empty($this->device_id) && $this->device_id != FHtml::NULL_VALUE) ? array_values($stations) : [];
        $devicesStr = [];
        foreach ($devices as $device) {
            $devicesStr[] = $device->name;
        }
        if (!empty($channel->campaign_id))
            $result[FHtml::t('SmartscreenSchedules', 'Campaign')] = $channel->campaign_id;

        $result[FHtml::t('SmartscreenSchedules', 'Channel ID')] = isset($channel) ? $channel->name : FHtml::t('All');
        $result[FHtml::t('SmartscreenSchedules', 'Devices')] = !empty($devicesStr) ? implode(', ', $devicesStr) : FHtml::t('All');

        if ($showTable != 'device') {
            $result[FHtml::t('Time')] = $date;
        }

        if ($showTable)
            return FHtml::showArrayAsTable($result);
        else
            return "<div style='font-size: 90%; color: grey'>" . FHtml::showArrayAsTable($result, 'label') . "</div>";
    }
}
