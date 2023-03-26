<?php

namespace backend\modules\system\models;

use common\components\FHtml;


class ObjectLink extends ObjectLinkBase
{
    const LOOKUP = [
        'type' => [
            ['id' => ObjectLink::TYPE_TAG, 'name' => 'tag'],
            ['id' => ObjectLink::TYPE_NEWS, 'name' => 'news'],
            ['id' => ObjectLink::TYPE_PAPER, 'name' => 'paper'],
        ],
    ];

    const COLUMNS_UPLOAD = ['image' ];

    public $order_by = 'id desc, sort_order asc';

    const OBJECTS_META = [];
    const OBJECTS_RELATED = [];

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

    public function getPreviewFields() {
        return ['name'];
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