<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "smartscreen_layouts_frame".
 */
class SmartscreenLayoutsFrame extends SmartscreenLayoutsFrameSearch
{
    const LOOKUP = [];

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

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        Smartscreen::clearCache();
        return parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function afterDelete()
    {
        Smartscreen::clearCache();
        parent::afterDelete(); // TODO: Change the autogenerated stub
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

    public function getLayouts()
    {
        return $this->hasMany(SmartscreenLayouts::className(), ['id' => 'layout_id'])
            ->viaTable('smartscreen_layouts_frame', ['frame_id' => 'id']);
    }
}
