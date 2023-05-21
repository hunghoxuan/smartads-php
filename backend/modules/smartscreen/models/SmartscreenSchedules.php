<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/*This is the customized model class for table "smartscreen_schedules".
 */

class SmartscreenSchedules extends SmartscreenSchedulesSearch
{
    private $is_refresh;

    const COLUMNS_CUSTOM = ['list_content', 'smartscreen_files', 'smartscreen_schedules'];

    public function getIsRefresh()
    {
        return $this->is_refresh;
    }

    public static function getDurationKindArray()
    {
        return [
            //self::DURATION_KIND_LOOP => FHtml::t('common', 'Loops'),
            self::DURATION_KIND_SECOND => FHtml::t('common', 'Second'),
            self::DURATION_KIND_MINUTES => FHtml::t('common', 'Minutes'),
        ];
    }

    public function setIsRefresh($value)
    {
        $this->is_refresh = $value;
    }

    const LOOKUP = [
        'channel_id' => '@smartscreen_channels'
    ];

    const COLUMNS_UPLOAD = [];

    public $order_by = '';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public function fields()
    {
        $fields = array_merge(parent::fields(), self::OBJECTS_RELATED);

        foreach (self::COLUMNS_UPLOAD as $field) {
            $this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
        }
        return $fields;
    }

    public function prepareCustomFields()
    {
        parent::prepareCustomFields();
    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

    public function getDirtyAttributes($names = null)
    {
        $values  = parent::getDirtyAttributes($names);
        if ($this->isNewRecord) {
            if (isset($values['id']))
                unset($values['id']);
        }
        return $values;
    }

    public function isCampaign()
    {
        return empty($this->start_time) || $this->type == Smartscreen::SCHEDULE_TYPE_CAMPAIGN;
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->id = null;
        }

        if (empty($this->device_id))
            $this->device_id = FHtml::NULL_VALUE;

        if ($this->date == '0000-00-00')
            $this->date = null;

        if ($this->date_end == '0000-00-00')
            $this->date_end = null;

        if (!empty($this->name) && $this->type == Smartscreen::SCHEDULE_TYPE_CAMPAIGN)
            $this->{self::FIELD_NAME} = $this->name;

        if (isset($this->is_active))
            $this->{self::FIELD_STATUS} = $this->is_active;

        if (!empty($this->campaign_id))
            $this->{self::FIELD_CAMPAIGN_ID} = $this->campaign_id;

        if (!empty($this->date) && $this->date == $this->date_end)
            $this->days = '';

        if (!$this->isCampaign() && empty($this->duration))
            $this->duration = Smartscreen::getDefaultDuration();

        if (isset($_POST['SmartscreenSchedules']['end_time']))
            $this->end_time = $_POST['SmartscreenSchedules']['end_time'];

        $changed = $this->getChangedContent();

        if (isset($changed['duration']) && array_keys($changed['duration'])[0] != array_values($changed['duration'])[0]) {
            $this->end_time = Smartscreen::getNextStartTime($this->start_time,  $this->duration);
        } else if (!empty($this->end_time)) {
            $this->duration = Smartscreen::getDurationBetween($this->start_time, 0, $this->end_time);
        }
        if (empty($this->content_id))
            $this->content_id = null;

        $result = parent::beforeSave($insert);
        return $result;
    }

    public function afterFind()
    {
        if (!isset($this->list_content) && $this->type == Smartscreen::SCHEDULE_TYPE_BASIC)
            $this->list_content = $this->smartscreenFiles();

        if (!empty($this->duration) && empty($this->kind))
            $this->kind = self::DURATION_KIND_MINUTES;

        if (empty($this->_times)) {
            $this->_times = [['_start_time' => $this->start_time, '_duration' => $this->duration]];
        }

        if (empty($this->start_time)) {
            $this->name = $this->{self::FIELD_NAME};
        }
        $this->campaign_id = $this->{self::FIELD_CAMPAIGN_ID};
        if ($this->channel_id == FHtml::NULL_VALUE)
            $this->channel_id = null;

        if ($this->device_id == FHtml::NULL_VALUE)
            $this->device_id = null;

        $this->is_active = $this->{self::FIELD_STATUS};
        if (is_string($this->layout_id)) {
            $this->name = $this->layout_id;
        }
        $this->end_time = Smartscreen::getNextStartTime($this->start_time, $this->duration, 1, null, true);
        return parent::afterFind();
    }

    public function setContent($value)
    {
        $this->list_content = $value;
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

    public function afterSave($insert, $runValidation)
    {
        //if it is campaign
        if (!$this->isCampaign()) {
            $media_files = $this->smartscreenFiles;
            if (empty($this->list_content) && is_array($media_files)) {
                foreach ($media_files as $media_file)
                    $media_file->delete();
            } else {
                if (is_string($this->list_content)) {
                    $list_content = FHtml::decode($this->list_content);
                } else {
                    $list_content = $this->list_content;
                }

                if (is_array($list_content)) {
                    $ids = array_column($list_content, 'id');
                    foreach ($media_files as $media_file) {
                        if (!in_array($media_file->id, $ids)) {
                            $media_file->delete();
                        }
                    }
                }
            }
        }

        if (!empty($this->start_time))
            $this->end_time = Smartscreen::getNextStartTime($this->start_time, $this->duration, 1, null, true);
        Smartscreen::clearCache();
        return parent::afterSave($insert, $runValidation);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmartscreenFiles()
    {
        return $this->hasMany(SmartscreenFile::className(), ['object_id' => 'id'])->andWhere(['object_type' => self::tableName()])->orderBy('sort_order');
    }

    public function smartscreenFiles()
    {
        if (!empty($this->content_id) && is_numeric($this->content_id))
            return SmartscreenFile::findAll(['object_id' => $this->content_id, 'object_type' => SmartscreenContent::tableName()], "sort_order asc");

        return SmartscreenFile::findAll(['object_id' => $this->id, 'object_type' => $this->getTableName()], "sort_order asc");
    }

    public function afterDelete()
    {
        Smartscreen::clearCache();
        SmartscreenFile::deleteAll(['object_id' => $this->id, 'object_type' => self::tableName()]);
        return parent::afterDelete();
    }

    public function getReturnUrl()
    {
        $params = Smartscreen::getCurrentParams(null, '--');
        return FHtml::createUrl('smartscreen/smartscreen-schedules/index', $params);
    }
}
