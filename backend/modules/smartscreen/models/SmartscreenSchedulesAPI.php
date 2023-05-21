<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use common\components\FHtml;


/**



 * This is the customized model class for table "smartscreen_schedules".
 */
class SmartscreenSchedulesAPI extends SmartscreenSchedulesSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'device_id', 'content_id', 'start_time', 'date', 'duration', 'loop', 'data', 'download_files', 'audio'];

    public function checkCustomField($name)
    {

        if (in_array($name,  self::COLUMNS_API))
            return true;

        return parent::checkCustomField($name);
    }

    public function fields()
    {
        $fields = $this::COLUMNS_API;
        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }

    public function getDevice()
    {
        return $this->hasOne(SmartscreenStationAPI::className(), ['id' => 'device_id']);
    }

    public function getLayout()
    {
        $result = $this->hasOne(SmartscreenLayoutsAPI::className(), ['id' => 'layout_id']);
        return $result;
    }

    public function getFrame()
    {
        return $this->hasOne(SmartscreenFrameAPI::className(), ['id' => 'frame_id']);
    }

    public function getContent()
    {
        return $this->hasOne(SmartscreenContentAPI::className(), ['id' => 'content_id']);
    }

    private $_data;
    public function getData()
    {
        if (!isset($this->_data))
            $this->_data = Smartscreen::getScheduleData($this);
        return $this->_data;
    }

    public function getData1()
    {
        if (!isset($this->_data))
            $this->_data = Smartscreen::getScheduleData($this);
        return $this->_data;
    }

    public function setData($value)
    {
        $this->_data = $value;
    }

    public function getDownloadFiles()
    {
        $data = $this->getData();
        return Smartscreen::getScheduleFiles($data);
    }

    public function getAudio()
    {
        return Smartscreen::getScheduleBackgroundAudio($this);
    }

    public function afterFind()
    {
        $this->date2 = $this->date;
        $this->start_time2 = $this->start_time;
        $this->device_id = !is_array($this->device_id) ? $this->device_id : FHtml::encode($this->device_id);
        $this->is_active = $this->{self::FIELD_STATUS};
        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if (!empty($this->id)) {
            $this->isNewRecord = false;
        }

        if ($this->isNewRecord) {
            $this->is_active = true;
            $this->id = null;
        }

        if (empty($this->device_id))
            $this->device_id = null;

        if ($this->date == '0000-00-00')
            $this->date = null;

        if ($this->date_end == '0000-00-00')
            $this->date_end = null;

        if (!empty($this->name))
            $this->{self::FIELD_NAME} = $this->name;

        if (isset($this->is_active))
            $this->{self::FIELD_STATUS} = $this->is_active;

        if (!empty($this->campaign_id))
            $this->{self::FIELD_CAMPAIGN_ID} = $this->campaign_id;

        if (!empty($this->date) && $this->date == $this->date_end)
            $this->days = '';

        return parent::beforeSave($insert);
    }
}
