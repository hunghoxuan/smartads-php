<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/*This is the customized model class for table "smartscreen_content".
 */

class SmartscreenContent extends SmartscreenContentSearch
{
    public $list_content;
    public $_bgcolor = '#000';
    public $_color = '#fff';
    public $_direction = '';
    public $_speed = 2;
    public $_size = 14;
    public $_font = 'Arial';
    public $_height = '';
    public $_padding = '';
    public $_background = '';
    public $_style = '';
    public $_scaleX = 1;
    public $_scaleY = 1;
    public $_margin;

    const COLUMNS_CREATED_BY = ['owner_id'];

    const COLUMNS_CUSTOM = ['list_content', 'smartscreen_content'];

    const LOOKUP = [
        'type' => ['slide', 'text', 'video', 'image', 'html', 'url']
    ];

    const COLUMNS_UPLOAD = ['url'];

    public $order_by = 'sort_order asc,is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    const TYPE_HIS = 'his';
    const TYPE_HIS_VIMES = 'his_vimes';
    const TYPE_TEXT = 'text';
    const TYPE_HTML = 'html';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_URL = 'url';
    const TYPE_SLIDE = 'slide';

    public static function getLookupArray($column = '')
    {
        // if ($column == 'type') {
        //     return array_merge(Smartscreen::settingHISEnabled() ? [self::TYPE_HIS_VIMES, self::TYPE_HIS] : [],  self::LOOKUP['type']);
        // }
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

    public function beforeSave($insert)
    {
        if ($this->type == self::TYPE_TEXT) {
            $data = $_POST['SmartscreenContent'];
            $this->_size = isset($data['_size']) ? $data['_size'] : '';
            $this->_speed = isset($data['_speed']) ? $data['_speed'] : '';
            $this->_direction = isset($data['_direction']) ? $data['_direction'] : '';
            $this->_bgcolor = isset($data['_bgcolor']) ? $data['_bgcolor'] : '';
            $this->_color = isset($data['_color']) ? $data['_color'] : '';
            $this->_font = isset($data['_font']) ? $data['_font'] : '';

            $this->_height = isset($data['_height']) ? $data['_height'] : '';
            $this->_padding = isset($data['_padding']) ? $data['_padding'] : '';
            $this->_background = isset($data['_background']) ? $data['_background'] : '';
            $this->_style = isset($data['_style']) ? $data['_style'] : '';
            $this->_scaleX = isset($data['_scaleX']) ? $data['_scaleX'] : '';
            $this->_scaleY = isset($data['_scaleY']) ? $data['_scaleY'] : '';
            $this->_margin = isset($data['_margin']) ? $data['_margin'] : '';

            if (is_array($this->_style))
                $this->_style = implode(';', $this->_style);

            $this->kind = implode(':', [$this->_size, $this->_speed, $this->_direction, $this->_bgcolor, $this->_color, $this->_font, $this->_height, $this->_padding, $this->_background, $this->_style, $this->_scaleX, $this->_scaleY, $this->_margin]);
            if (empty($this->description))
                $this->description = $this->title;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function findAllCached()
    {
        $result = Smartscreen::Cache(self::tableName());
        if (isset($result) && !empty($result))
            return $result;

        $result = static::findAll(['is_active' => 1], 'type asc, title asc');
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
        Smartscreen::clearCache();
        if ($this->is_default) {
            FHtml::Cache("schedules_default", null);
        }

        $media_files = $this->smartscreenFiles;
        if (empty($this->list_content)) {
            foreach ($media_files as $media_file)
                $media_file->delete();
        } else {
            $ids = array_column($this->list_content, 'id');

            foreach ($media_files as $media_file) {
                if (!in_array($media_file->id, $ids))
                    $media_file->delete();
            }
        }

        return parent::afterSave($insert, $runValidation);
    }

    public function afterFind()
    {
        $this->description = trim($this->description);
        if (!isset($this->list_content))
            $this->list_content = $this->smartscreenFiles;

        if ($this->type == self::TYPE_TEXT) {
            $data = explode(':', $this->kind);
            if (is_array($data)) {
                $this->_size = !isset($data[0]) ? '' : $data[0];
                $this->_speed = !isset($data[1]) ? '' : $data[1];
                $this->_direction = !isset($data[2]) ? '' : $data[2];
                $this->_bgcolor = !isset($data[3]) ? '' : $data[3];
                $this->_color = !isset($data[4]) ? '' : $data[4];
                $this->_font = !isset($data[5]) ? '' : $data[5];

                $this->_height = !isset($data[6]) ? '' : $data[6];
                $this->_padding = !isset($data[7]) ? '' : $data[7];
                $this->_background = !isset($data[8]) ? '' : $data[8];
                $this->_style = !isset($data[9]) ? '' : $data[9];
                if (is_string($this->_style))
                    $this->_style = explode(';', $this->_style);
                $this->_scaleX = !isset($data[10]) ? '' : $data[10];
                $this->_scaleY = !isset($data[11]) ? '' : $data[11];
                $this->_margin = !isset($data[12]) ? '' : $data[12];
            }
        }
        return parent::afterFind();
    }

    public function getContent($bg_size = 'cover')
    {
        if ($this->type == self::TYPE_HTML) {
            return FHtml::showHtml($this->description);
        } else if ($this->type == self::TYPE_URL) {
            return "<iframe allowfullscreen=true frameborder='0' width='100%' height='100%' src='$this->description' />";
        }

        $background = !empty($this->_background) ? $this->_background : FHtml::getCurrentMainColor();
        $content = $this->description;
        if ($this->type == self::TYPE_TEXT) {
            $content = Smartscreen::getMarqueeContent($this->description, $this->_background, $this->_size, $this->_height, $this->_padding, $this->_margin, $this->_bgcolor, $this->_speed, $this->_color, $this->_direction, $this->_font);
        } else if ($this->type == self::TYPE_IMAGE) {
            if (is_array($this->list_content) && count($this->list_content) == 1) {
                $url = !empty($this->list_content[0]['file']) ? FHtml::getFileURL($this->list_content[0]['file'], 'smartscreen-file') : '';
                $content = "<img src='$url' width='100%' height='100%' style='object-fit: cover;width: 100%;height: 100%' />";
                //return '<html style="height:100%;width:100%"><body style="height:100%;width:100%;background-color:#3c78d8"><div style="width:100%;height:100%;background-image:url(\'http://192.168.0.96/SmartAds-Stech-PHP/\\applications\\smartads\\upload\\smartscreen-file\\16027595460_video.jpg\'); background-size:cover;background-position: left;background-repeat:repeat;" /></body></html>';
            }
        }
        if (!empty($content))
            return Smartscreen::getHtmlContent($content, true, $background);
        return $content;
    }

    public function setContent($value)
    {
        $this->list_content = $value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmartscreenFiles()
    {
        return $this->hasMany(SmartscreenFile::className(), ['object_id' => 'id'])->andWhere(['object_type' => self::tableName()])->orderBy('sort_order asc');
    }

    public function smartscreenFiles()
    {
        return SmartscreenFile::findAll(['object_id' => $this->id, 'object_type' => $this->getTableName()], "sort_order asc");
    }

    public function afterDelete()
    {
        Smartscreen::clearCache();
        SmartscreenFile::deleteAll(['object_id' => $this->id, 'object_type' => self::tableName()]);
        return parent::afterDelete();
    }

    public static function findAllForCombo($condition = [], $id_field = 'id', $display_name = 'name', $order_by = 'type asc, title asc')
    {
        $models = static::findAllCached($condition, $order_by);
        $arr = ['' => FHtml::NULL_VALUE];
        foreach ($models as $model) {
            $status = $model->is_active ? '' : '(inactive)';
            $arr[] = [$model->id => "[ $model->type ] $model->title (id: $model->id) $status"];
        }
        return array_values($arr);
    }
}
