<?php

namespace backend\modules\smartscreen\models;

use backend\modules\smartscreen\Smartscreen;
use common\components\FHtml;


class SmartscreenQueue extends SmartscreenQueueBase
{
    public $order_by = 'device_id asc, sort_order asc, name asc';

    const LOOKUP = [

    ];

    const COLUMNS_UPLOAD = [
    ];

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

    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function beforeSave($insert)
    {
        if (!isset($this->is_active))
            $this->is_active = 1;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}