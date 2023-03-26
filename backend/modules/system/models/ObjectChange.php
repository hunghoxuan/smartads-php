<?php

namespace backend\modules\system\models;

use common\components\FHtml;


class ObjectChange extends ObjectChangeBase
{
    const LOOKUP = [
    ];

    const COLUMNS_UPLOAD = [];



    const OBJECTS_META = [];

    public static function getLookupArray($column)
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
}