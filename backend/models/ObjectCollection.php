<?php

namespace backend\models;

use common\components\FHtml;


class ObjectCollection extends ObjectCollectionBase
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


    public function getModelRelatedObjects()
    {
    	parent::getModelRelatedObjects();
        return array_merge(self::OBJECTS_RELATED, [$this->object_type]);
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }
}