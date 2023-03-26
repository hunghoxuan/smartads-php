<?php

namespace backend\modules\system\models;

use common\components\FHtml;


class ObjectContent extends ObjectContentBase
{
    const LOOKUP = [
    ];

    const COLUMNS_UPLOAD = ['image'];

    public $order_by = 'sort_order asc, is_active desc, created_date desc';


    const OBJECTS_META = [];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
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